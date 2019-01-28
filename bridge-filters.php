<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php
        include('session.php');
        if(isset($_SESSION['login_user']))
         {
          echo '<h3>', 'Welcome ', $_SESSION['login_user'], '</h3>';
         }
        else {
            echo 'session is invalid'."<br>";
            die("Name parameter missing");
            
        } 
        echo '<p><a href = "logout.php">Sign Out</a></p>';
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">

    </head>
    <body>
        <script src="js/bootstrap.min.js"></script>
        <H3>add or remove filter:</H3>
        <form method="post">
        <p>Action (if adding):
        <input type="text" name="action" size="40"></p>
        <p>Chain (if adding):
        <input type="text" name="chain" size="40"></p>
        <p>Source MAC address:
        <input type="text" name="src-mac-address" size="40"></p>
        <p><input type="submit" name="add_filter" id="add_filter" value="Add filter"/> <input type="submit" name="remove_filter" id="remove_filter" value="Remove filter"/></p>
    </form>

        <?php
        require('include/routeros_api.class.php');
        
        $dt = date("Y-m-d H:i:s");

        if  (isset($_GET['id']))  {
                $sql = "select router_ip, router_pwd, router_login from routers where id =".$_GET['id'];
                $stmt = $pdocon->prepare($sql);
                $stmt->execute();
                $row= $stmt->fetch(PDO::FETCH_ASSOC); 
                    $router_ip = $row['router_ip'];
                    $router_pwd = $row['router_pwd'];
                    $router_login = $row['router_login'];
                    error_log($dt . ' User ' . $_SESSION['login_user'] . ' select for configuring router with ip: ' . $row['router_ip'] . PHP_EOL , 3, $logfile);


                }        
        
        
        function add_filter($router_ip, $router_login, $router_pwd, $logfile)
            {
                $API = new RouterosAPI();
                if ($API->connect($router_ip, $router_login, $router_pwd)) {
                $API->comm("/interface/bridge/filter/add", array(
                "action"  => $_POST['action'],
                "chain" => $_POST['chain'],
                "src-mac-address"      => $_POST['src-mac-address'].'/FF:FF:FF:FF:FF:FF',
                "place-before" => '0'  
                ));
                echo '<br>', "Added new filter for mac: ", $_POST['src-mac-address'];
                error_log(date("Y-m-d H:i:s") . ' User ' . $_SESSION['login_user'] . 
                        ' added on: ' . $router_ip .
                        ' new filter for MAC '. $_POST['src-mac-address'] . 
                        PHP_EOL , 3, $logfile);
                $API->disconnect();
                }
            }
        
        function remove_filter($router_ip, $router_login, $router_pwd, $logfile)
            {
                $API = new RouterosAPI();
                if ($API->connect($router_ip, $router_login, $router_pwd)) {                   
                    $ARRAY = $API->comm("/interface/bridge/filter/print", array(
                    ".proplist" => ".id",
                    "?src-mac-address" => $_POST['src-mac-address'].'/FF:FF:FF:FF:FF:FF',
                    ));
                    $API->write('/interface/bridge/filter/remove', false);
                    $API->write('=.id=' . $ARRAY[0]['.id']);
                    $READ = $API->read();
                    echo '<br>', "removed old filter for mac: ", $_POST['src-mac-address'];
                    error_log(date("Y-m-d H:i:s") . ' User ' . $_SESSION['login_user'] . 
                        ' removed on: ' . $router_ip .
                        ' old filter for MAC '. $_POST['src-mac-address'] . 
                        PHP_EOL , 3, $logfile);
                    $API->disconnect();
                }
            }
            
            if ( array_key_exists('add_filter',$_POST) && isset($_POST['action']) && isset($_POST['chain']) && isset($_POST['src-mac-address'])) {
                add_filter($router_ip, $router_login, $router_pwd, $logfile);
                }
            elseif ( array_key_exists('remove_filter',$_POST) && isset($_POST['src-mac-address'])) {
                remove_filter($router_ip, $router_login, $router_pwd, $logfile);
                }    
                
        $API = new RouterosAPI();
               
        if ($API->connect($router_ip, $router_login, $router_pwd)) {
            $API->write('/ip/arp/print'); 
            $READ = $API->read(false);
            $ARRAY = $API->parseResponse($READ);
            $API->disconnect();
            echo '<H3>current list of ARP entries on router: '. $router_ip. '</H3>';
            echo "<table id='ARP list' border='1'>\n";
            foreach($ARRAY as $item) {
                echo "<tr>\n";
                echo "  <td class='mark'>ID " .  $item['.id'] . "</td>\n" . 
                       "<td class='mark'>" . $item['address'] . "</td>\n" . ' ' . 
                       "<td class='mark'>" . $item['mac-address'] . "</td>\n" . 
                       "<td class='mark'>" . $item['interface'] . "</td>\n"; 
                echo "</tr>\n<tr>\n";
            }
            echo "</table>\n";  
        }
        

        ?>  
    </body>
</html>

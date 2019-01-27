<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
   include('session.php');
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>

    <body>
      <h1>Welcome <?php echo $login_session; ?></h1> 
      <h2><a href = "logout.php">Sign Out</a></h2>
        <?php
        if(isset($_SESSION['login_user']))
         {
          echo 'Welcome ', $_SESSION['login_user'] ;
         }
        else {
            echo 'session is invalid'."<br>";
            die("Name parameter missing");
            
        } 

        ?>
      
      
    <p>Add router:</p>
    <form method="post">
    <p>Router name:
        <input type="text" name="router" size="100"></p>
    <p>Router IP:
        <input type="text" name="router_ip" size="100"></p>
    <p>Router login:
        <input type="text" name="router_login" size="100"></p>    
    <p>Router password:
        <input type="text" name="router_password" size="100"></p>      
    <p><input type="submit" value="Add"/></p>
    </form>

    <form action="logout.php" method="post">
    <p><input type="submit" value="Logout"/></p>
    </form>

        
    <?php
    echo '<H3>Router list:</H3>';
    $dt = date("Y-m-d H:i:s");
    $sql = "select id, router, router_ip from routers order by router";
    $stmt = $pdocon->prepare($sql);
    $stmt->execute();
    while ($row= $stmt->fetch(PDO::FETCH_ASSOC)) {
        $linkaddress = 'bridge-filters.php?id='.$row['id'];
        echo  "<a href='".$linkaddress."'> Select this router: </a>", ' ', $row['id'], '  ', $row['router'], ' ', $row['router_ip'] , "<br>"; 
    }

    if ( isset($_POST['router']) && isset($_POST['router_ip']) && isset($_POST['router_login']) && isset($_POST['router_pwd'])) {
        
        $sql = "INSERT INTO routers
        (router, router_ip, router_login, router_pwd) VALUES ( :rt, :rtip, :rtlgn, :rtpwd)";
      
        $stmt = $pdocon->prepare($sql);
    $stmt->execute(array(   
        ':rt' => $_POST['router'],
        ':rtip' => $_POST['router_ip'],
        ':rtlgn' => $_POST['router_login'],
        ':rtpwd' => $_POST['router_pwd'])
    );
    echo 'New router added';
    error_log($dt . ' User ' . $_SESSION['login_user'] . ' added new router with ip: ' . $_POST['router_ip'] . PHP_EOL , 3, $logfile);

}
?>
    </body>
</html>

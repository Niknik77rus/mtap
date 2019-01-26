<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <H3>add new filter on router: 192.168.88.1</H3>'
        <form method="post">
        <p>Action:
        <input type="text" name="action" size="40"></p>
        <p>Chain:
        <input type="text" name="chain"></p>
        <p>Source MAC address:
        <input type="text" name="src-mac-address"></p>
        <p><input type="submit" value="Add filter"/></p>
    </form>

        <?php
        require('include/routeros_api.class.php');
        
        function add_filter()
            {
                $API = new RouterosAPI();
                $API->debug = true;
                if ($API->connect('192.168.88.1', 'nnk', 'Winter@2017')) {
                $API->comm("/interface/bridge/filter/add", array(
                "comment"     => "test1",
                "action"  => $_POST['action'],
                "chain" => $_POST['chain'],
                "src-mac-address"      => $_POST['src-mac-address']
                ));
                
                echo "Added new filter";
                $API->disconnect();
                }
            }

            if ( isset($_POST['action']) && isset($_POST['chain']) && isset($_POST['src-mac-address'])) {
                add_filter();
                }
        
        echo '<H3>current list of filtering rules from router: 192.168.88.1</H3>';
        
        $API = new RouterosAPI();
        $API->debug = false;
        if ($API->connect('192.168.88.1', 'nnk', 'Winter@2017')) {
            $API->write('/interface/bridge/filter/print'); 
            $READ = $API->read(false);
            $ARRAY = $API->parseResponse($READ);
            #print_r($ARRAY);
            #echo $ARRAY;
            $API->disconnect();
            echo '<H3>current list of filtering rules from router: 192.168.88.1</H3>';
            foreach($ARRAY as $item) {
                echo '<br>' .  $item['.id'] . ' ' . $item['action'] . ' ' . $item['src-mac-address']; 
                #echo '<pre>'; var_dump($item);
            }
        }
        echo '<H3>current list of ARP entries on router: 192.168.88.1</H3>';
        if ($API->connect('192.168.88.1', 'nnk', 'Winter@2017')) {
            $API->write('/ip/arp/print'); 
            $READ = $API->read(false);
            $ARRAY = $API->parseResponse($READ);
            #print_r($ARRAY);
            #echo $ARRAY;
            $API->disconnect();
            foreach($ARRAY as $item) {
                echo '<br>' .  $item['.id'] . ' ' . $item['address'] . ' ' . $item['mac-address'] . ' ' . $item['interface']; 
                #echo $item['action'];
                #echo '<pre>'; var_dump($item);
            }
        }
        

        ?>  
    </body>
</html>

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
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">

    </head>
    <body>
        <script src="js/bootstrap.min.js"></script>

        
        
        <div class="container">
<h1>Please Log In</h1>
<form method="POST">
<label for="name">User Name</label>
<input type="text" name="username" id="nam"><br/>
<label for="password">Password</label>
<input type="text" name="password" id="password"><br/>
<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>

</p>
</div>
        <?php
        require_once 'include/config.php';
        session_start();
        if($_SERVER["REQUEST_METHOD"] == "POST") {
        $myusername = $_POST['username'];
        $mypassword = $_POST['password'];
        $dt = date("Y-m-d H:i:s");
        $sql = "SELECT user FROM users WHERE user = :myusername and password = :mypassword";
        $sth = $pdocon->prepare($sql);
        $sth->execute(
                array(
                    ':myusername' => $myusername,
                    ':mypassword' => $mypassword));
        
        $count = $sth->rowCount();
        if($count === 1) {
         $_SESSION['login_user'] = $myusername;
         error_log($dt . ' User ' . $_POST['username'] . ' logged in successfully' . PHP_EOL , 3, $logfile);
         header("location: routers.php");
      }else {
         $error = "Your Login Name or Password is invalid";
         echo $error;
      }
        }
        ?>
    </body>
</html>

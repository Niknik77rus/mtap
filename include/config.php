<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$pdocon = new PDO('mysql:host=localhost;port=3306;dbname=mtap', 
   'usrmtapp', 'pwdmtapp');
// See the "errors" folder for details...
$pdocon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$logfile = '/home/nnk/mtap.log'; 
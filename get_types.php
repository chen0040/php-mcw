<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('MySQLHandler.php');

$handler=new MySQLHandler();
$handler->connect();
$handler->select_database();

$sQuery='SELECT DISTINCT type FROM mcarticles';

$rResult=mysql_query($sQuery) or die(mysql_error());

while($rRow=mysql_fetch_array($rResult))
{
    echo '<option value='.$rRow['type'].'>'.$rRow['type'].'</option>';
}

$handler->disconnect();

?>

<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('MObj.php');

if(!isset($_POST['id']))
{
    $content='{
    "msg": "id has not been defined!"
    }';
    echo $content;
    return;
}

$id=$_POST['id'];

$admin=MObj::hasLoginned() && MObj::hasRole("admin");



$sQuery='DELETE FROM mccommand WHERE id='.$id;

$handler=new MySQLHandler();
$executed=$handler->connect();
$handler->select_database();
if(!$handler->table_exists("mccommand"))
{
    $content='{
    "msg": "Command base does not exist!"
    }';
    echo $content;
    return;
}

mysql_query($sQuery) or die(mysql_error());

if($executed)
{
    $handler->disconnect();
}

$content='{
    "msg": "executed"
}';


echo $content;
?>

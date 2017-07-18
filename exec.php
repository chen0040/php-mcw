<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('MObj.php');
require_once('MySQLHandler.php');

//$_POST['id']='21';

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

if($admin==false)
{
    $content='{
    "msg": "The user has not enough authoritative right to perform the action!"
    }';
    echo $content;
    return;
}

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

$sQuery='SELECT * FROM mccommand WHERE id='.$id;

$result=mysql_query($sQuery) or die(mysql_error());

$row=mysql_fetch_array($result);

$mcquery=$row['mcquery'];
$sQueries=preg_split('/\[xs:;\]/', $mcquery, -1, PREG_SPLIT_NO_EMPTY);

foreach($sQueries as $sQuery)
{
    mysql_query($sQuery) or die(mysql_error());
}

$sQuery='UPDATE mccommand SET mccommited=1 WHERE id='.$id;
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


<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require_once('MySQLHandler.php');

if(isset($_POST['id']))
{
    $handler=new MySQLHandler();
    $handler->connect();
    $handler->select_database();

    $sQuery="UPDATE mcmembership SET muser='".$_POST['username']."', mpwd='".$_POST['password']."', mrole='".$_POST['role']."' WHERE id='".$_POST['id']."'";

    mysql_query($sQuery) or die(mysql_error());
    
    $handler->disconnect();

    $content='
    {
        "msg": "updated"
    }
    ';

    echo $content;
}
?>

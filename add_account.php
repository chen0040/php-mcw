<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require_once('MySQLHandler.php');

//$_POST['name']='AddAccount';
//$_POST['password']='50001';
//$_POST['role']='user';

if(isset($_POST['username']))
{
    $handler=new MySQLHandler();
    $handler->connect();
    $handler->select_database();

    $sQuery="INSERT INTO mcmembership (muser, mpwd, mrole) VALUES ('".$_POST['username']."', '".$_POST['password']."', '".$_POST['role']."')";


    mysql_query($sQuery) or die(mysql_error());

    $sQuery="SELECT * FROM mcmembership WHERE muser='".$_POST['username']."' AND mpwd='".$_POST['password']."' AND mrole='".$_POST['role']."'";

    $sResult=mysql_query($sQuery) or die(mysql_error());
    $aRow=mysql_fetch_array($sResult);

    $result='';
    $result .= "[";
    $result .= '"<img src=\"dataTables/media/images/details_open.png\"><!--' . addslashes($aRow['id']) . '-->",';
    $result .= '"'.addslashes($aRow['muser']).'",';
    $result .= '"'.addslashes($aRow['mpwd']).'",';
    $result .= '"'.addslashes($aRow['mrole']).'"';
    $result .= "]";

    $handler->disconnect();

    $content='
    {
        "msg": "added",
        "account": '.$result.'
    }
    ';

    echo $content;
}

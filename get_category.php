<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of getArticle
 *
 * @author Xianshun
 */
session_start();
require_once("MySQLHandler.php");

$category_type=$_GET['category_type'];

if(isset($_POST['id']))
{
    $handler=new MySQLHandler();
    $handler->connect();
    $handler->select_database();

    $sQuery= "SELECT * FROM mc".$category_type."dictionary WHERE id=".mysql_real_escape_string( $_POST['id'] )."";

    $rResult=mysql_query($sQuery) or die(mysql_error());
    $rRow=mysql_fetch_array($rResult);
    $detail=mysql_real_escape_string($rRow['detail']);
    $id=mysql_real_escape_string($rRow['id']);
    $parentid=mysql_real_escape_string($rRow['parentid']);
    $description=mysql_real_escape_string(htmlspecialchars_decode($rRow['description']));

    $handler->disconnect();

    $content='{
    detail: "'.$detail.'",
    description: "'.$description.'",
    id: '.$id.',
    parentid: "'.$parentid.'"
    }';
    echo $content;
}
?>

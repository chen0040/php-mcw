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

if(isset($_POST['id']))
{
    $handler=new MySQLHandler();
    $handler->connect();
    $handler->select_database();

    $sQuery= "SELECT * FROM mcarticles WHERE id=".mysql_real_escape_string( $_POST['id'] )."";

    $rResult=mysql_query($sQuery) or die(mysql_error());
    $rRow=mysql_fetch_array($rResult);
    $title=mysql_real_escape_string($rRow['title']);
    $id=mysql_real_escape_string($rRow['id']);
    $year=mysql_real_escape_string($rRow['year']);
    $type=mysql_real_escape_string($rRow['type']);
    $pages=mysql_real_escape_string($rRow['pages']);
    $loc=mysql_real_escape_string($rRow['loc']);
    $abstract=mysql_real_escape_string(htmlspecialchars_decode($rRow['abstract']));
    $comment=mysql_real_escape_string(htmlspecialchars_decode($rRow['comment']));
    $keywords=mysql_real_escape_string($rRow['keywords']);

    $handler->disconnect();

    $content='{
    title: "'.$title.'",
    year: "'.$year.'",
    id: '.$id.',
    type: "'.$type.'",
    loc: "'.$loc.'",
    pages: "'.$pages.'",
    comment: "'.$comment.'",
    abstract: "'.$abstract.'",
    keywords: "'.$keywords.'"
    }';
    echo $content;
}
?>

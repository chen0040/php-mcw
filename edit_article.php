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
 $_POST['title']="A case study of memetic algorithms for constraint optimization";
 $_POST['id']='83';
 $_POST['year']='2009';
 $_POST['type']='article';
 $_POST['pages']='';
 $_POST['abstract']='';
 $_POST['keywords']='';
 $_POST['loc']='Testing';
 $_POST['comment']='Testing';
 
require_once('Command.php');
require_once("MySQLHandler.php");

if(isset($_POST['id']))
{
    $handler=new MySQLHandler();
    $handler->connect();
    
    $title=mysql_real_escape_string($_POST['title']);
    $id=mysql_real_escape_string($_POST['id']);
    $year=mysql_real_escape_string($_POST['year']);
    $type=mysql_real_escape_string($_POST['type']);
    $pages=mysql_real_escape_string($_POST['pages']);
    $loc=mysql_real_escape_string($_POST['loc']);
    $abstract=mysql_real_escape_string(htmlspecialchars($_POST['abstract']));
    $comment=mysql_real_escape_string(htmlspecialchars($_POST['comment']));
    $keywords=mysql_real_escape_string($_POST['keywords']);

    $handler->select_database();

    $sQuery= "UPDATE mcarticles SET title='$title', year='$year', type='$type', pages='$pages', loc='$loc', abstract='$abstract', comment='$comment', keywords='$keywords' WHERE id='".$id."'";

    if(Command::isEditable())
    {
        mysql_query($sQuery) or die(mysql_error());
        $content='{
            msg: "updated"
        }';
        echo $content;
    }
    else
    {
        $comments=Command::validate();
        if(strcmp($comments, '') != 0)
        {
            echo $comments;
            $handler->disconnect();
            return;
        }

        $action="Update article";
        $detail="Update article [$title]<br />Year: $year<br />Type:$type<br />Pages:$pages<br />Issue:$loc<br />Abstract:<br />$abstract<br />Comment:<br />$comment<br />Tag:<br />$keywords";
        echo Command::queue($sQuery, $action, $detail);
    }

    $handler->disconnect();

    
}
?>

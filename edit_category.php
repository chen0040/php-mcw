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
require_once("Command.php");

// $_POST['id']='14';
// $_POST['detail']='Bin Packing';
// $_POST['parentid']='100000';
// $_POST['description']='Hello World';
 
require_once("MySQLHandler.php");

$category_type=$_GET['category_type'];

if(isset($_POST['id']))
{
    $handler=new MySQLHandler();
    $handler->connect();
    
    $parentid=mysql_real_escape_string($_POST['parentid']);
    $id=mysql_real_escape_string($_POST['id']);
    $detail=mysql_real_escape_string($_POST['detail']);
    $description=mysql_real_escape_string(htmlspecialchars($_POST['description']));

    
    $handler->select_database();

    $sQuery= "UPDATE mc".$category_type."dictionary SET parentid='$parentid', detail='$detail', description='$description' WHERE id='".$id."'";

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
        if(strcmp($comments, '')!=0)
        {
            echo $comments;
            $handler->disconnect();
            return;
        }

        $parent_detail='root';
        if(strcmp($parentid, '0') != 0)
        {
            $result=mysql_query('SELECT detail FROM mc'.$category_type.'dictionary where id="'.$parentid.'"') or die(mysql_error());
            $row=mysql_fetch_array($result);
            $parent_detail=$row[0];
        }
        
        $detail="Update [$detail]<br />Description: [$description]<br />Category: [$parent_detail]";
        $action="Update category in [".$category_type."]";

        echo Command::queue($sQuery, $action, $detail);
    }

    $handler->disconnect();

    
}
?>

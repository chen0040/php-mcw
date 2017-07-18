<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('Command.php');
require_once('MySQLHandler.php');

//$_POST['id']='2';

if(isset($_POST['id']))
{    
    $handler=new MySQLHandler();
    $handler->connect();
    $handler->select_database();

    if(Command::isEditable())
    {
        $sQuery="DELETE FROM mcarticles WHERE id='".$_POST['id']."'";
        mysql_query($sQuery) or die(mysql_error());

        $category_types=Array();
        $category_types[]='application';
        $category_types[]='design';
        $category_types[]='memetic';
        $category_types[]='author';
        $category_types[]='journal';
        $category_types[]='definition';

        foreach($category_types as $ct)
        {
            $sQuery="DELETE FROM mc".$ct."s WHERE articleid='".$_POST['id']."'";
            mysql_query($sQuery) or die(mysql_error());
        }

        $content='
        {
            "msg": "deleted"
        }
        ';

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

        $action="Delete from [article]";

        $sQuery="DELETE FROM mcarticles WHERE id='".$_POST['id']."'[xs:;]";
        $category_types=Array();
        $category_types[]='application';
        $category_types[]='design';
        $category_types[]='memetic';
        $category_types[]='author';
        $category_types[]='journal';
        $category_types[]='definition';
        foreach($category_types as $ct)
        {
            $sQuery.="DELETE FROM mc".$ct."s WHERE articleid='".$_POST['id']."'[xs:;]";
        }
        
        $mcdetail='';
        $r=mysql_query('SELECT title from mcarticles where id="'.$_POST['id'].'"') or die(mysql_error());
        $rr=mysql_fetch_array($r);
        $title=$rr[0];
        $mcdetail.=mysql_real_escape_string('Delete "'.$title.'" from article');

        echo Command::queue($sQuery, $action, $mcdetail);
    }
    
    $handler->disconnect();
}
?>

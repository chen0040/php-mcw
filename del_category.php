<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('Command.php');
require_once('MySQLHandler.php');

$category_type=$_GET['category_type'];

if(isset($_POST['id']))
{
    $handler=new MySQLHandler();
    $handler->connect();
    $handler->select_database();

    $sQuery="SELECT mcarticles.title FROM mc".$category_type."s, mcarticles WHERE mc".$category_type."s.articleid=mcarticles.id AND mc".$category_type."s.".$category_type."id='".$_POST['id']."'";
    $rResult=mysql_query($sQuery) or die(mysql_error());
    $fc=mysql_num_rows($rResult);

    if($fc != 0)
    {
        $sArticles='';
        $index=0;
        while($rRow=mysql_fetch_array($rResult))
        {
            $sArticles.=mysql_real_escape_string('['.$index.']'.$rRow['title']."\n");
            $index++;
        }
        $handler->disconnect();
        echo '
            {
                "msg": "[Deletion Failure]: Currently some articles are using this '.$category_type.' type\n'.$sArticles.'"
            }
        ';
        return;
    }

    if(Command::isEditable())
    {
        $sQuery="DELETE FROM mc".$category_type."dictionary WHERE id='".$_POST['id']."'";
        mysql_query($sQuery) or die(mysql_error());

        $sQuery="DELETE FROM mc".$category_type."s WHERE articleid='".$_POST['id']."'";
        mysql_query($sQuery) or die(mysql_error());

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

        $action="Delete category from [".$category_type."]";

        $mcdetail='';

        $r=mysql_query('SELECT detail from mc'.$category_type.'dictionary where id="'.$_POST['id'].'"') or die(mysql_error());
        $rr=mysql_fetch_array($r);
        $title=$rr[0];
        $mcdetail.=mysql_real_escape_string('Delete "'.$title.'" from ['.$category_type.']');

        $sQuery="DELETE FROM mc".$category_type."dictionary WHERE id='".$_POST['id']."'[xs:;]";
        $sQuery.="DELETE FROM mc".$category_type."s WHERE articleid='".$_POST['id']."'";

        echo Command::queue($sQuery, $action, $mcdetail);
    }
    
    $handler->disconnect();
}
?>

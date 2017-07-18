<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('Command.php');
require_once('MySQLHandler.php');

if(isset($_POST['title']))
{
    $handler=new MySQLHandler();
    $handler->connect();
    $handler->select_database();

    $title=mysql_real_escape_string($_POST['title']);
    $year=mysql_real_escape_string($_POST['year']);
    $type=mysql_real_escape_string($_POST['type']);
    $abstract=mysql_real_escape_string(htmlspecialchars($_POST['abstract']));
    $comment=mysql_real_escape_string(htmlspecialchars($_POST['comment']));
    $keywords=mysql_real_escape_string($_POST['keywords']);
    $pages=mysql_real_escape_string($_POST['pages']);
    $loc=mysql_real_escape_string($_POST['loc']);
    
    $sQuery="INSERT INTO mcarticles (title, year, type, abstract, comment, keywords, pages, loc) VALUES ('".$title."', '".$year."', '".$type."', '".$abstract."', '".$comment."', '".$keywords."', '".$pages."', '".$loc."')";

    if(Command::isEditable())
    {
        mysql_query($sQuery) or die(mysql_error());

        $sQuery="SELECT * FROM mcarticles WHERE title='".$title."' AND year='".$year."' AND type='".$type."' AND abstract='".$abstract."' AND keywords='".$keywords."' AND pages='".$pages."'";

        $sResult=mysql_query($sQuery) or die(mysql_error());
        $aRow=mysql_fetch_array($sResult);

        $result='';
        $result .= "[";
        $result .= '"<img src=\"dataTables/media/images/details_open.png\"><!--' . addslashes($aRow['id']) . '-->",';
        $result .= '"'.addslashes($aRow['title']).'",';
        $result .= '"'.addslashes($aRow['loc']).'",';
        $result .= '"'.addslashes($aRow['pages']).'",';
        $result .= '"'.addslashes($aRow['year']).'",';
        $result .= '"'.addslashes($aRow['type']).'"';
        $result .= "]";

        $content='
        {
            "msg": "added",
            "article": '.$result.'
        }
        ';

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

        $action="Add article";
        $detail="Add article [$title]<br />Year: $year<br />Type: $type<br />Abstract:<br />$abstract<br />Comment:<br />$comments<br />Pages: $pages<br />Tag: $loc";

        echo Command::queue($sQuery, $action, $detail);
        
    }

    $handler->disconnect();  
}
?>
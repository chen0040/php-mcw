<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('Command.php');
require_once('MySQLHandler.php');

//$_GET['category_type']='memetic';
//$_POST['detail']='test';
//$_POST['description']='this is a test';
//$_POST['parentid']='0';

$category_type=$_GET['category_type'];

if(isset($_POST['detail']))
{
    if(strcmp($_POST['detail'], '')==0)
    {
        echo '
            {
                "msg": "detail cannot be empty"
            }
        ';
        return;
    }
    
    $handler=new MySQLHandler();
    $handler->connect();
    $handler->select_database();

    $detail=mysql_real_escape_string($_POST['detail']);
    $description=mysql_real_escape_string(htmlspecialchars($_POST['description']));
    $parentid=mysql_real_escape_string($_POST['parentid']);

    $sQuery="INSERT INTO mc".$category_type."dictionary (detail, description, parentid) VALUES ('".$detail."', '".$description."', '".$parentid."')";

    if(Command::isEditable())
    {
        mysql_query($sQuery) or die(mysql_error());

        $sQuery="SELECT * FROM mc".$category_type."dictionary WHERE detail='".$detail."' AND parentid='".$parentid."'";

        $sResult=mysql_query($sQuery) or die(mysql_error());
        $aRow=mysql_fetch_array($sResult);

        $result='';
        $result .= "[";
        $result .= '"<img src=\"dataTables/media/images/details_open.png\"><!--' . addslashes($aRow['id']) . '-->",';
        $result .= '"'.addslashes($aRow['detail']).'"';
        $result .= "]";

        $content='
        {
            "msg": "added",
            "category": '.$result.'
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

        $action="Add category to [".$category_type."]";

        $mcdetail='';

        if(strcmp($parentid, '0')==0)
        {
            $mcdetail.=mysql_real_escape_string('Add "'.$detail.'" to the root of '.$category_type);
        }
        else
        {
            $r=mysql_query('SELECT detail from mc'.$category_type.'dictionary where id="'.$parentid.'"') or die(mysql_error());
            $rr=mysql_fetch_array($r);
            $parent_detail=$rr[0];
            $mcdetail.=mysql_real_escape_string('Add "'.$detail.'" to the "'.$parent_detail.'" of '.$category_type);
        }
        
        echo Command::queue($sQuery, $action, $mcdetail);
    }

    $handler->disconnect();

}
?>

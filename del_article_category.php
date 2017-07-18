<?php
require_once('Command.php');
require_once('MySQLHandler.php');

$category_type=$_GET['category_type'];

//$_POST['articleid']='83';
//$_POST[$category_type.'id']='1';

if($_POST['articleid'])
{
    $content='';

    $handler=new MySQLHandler();
    $handler->connect();
    $handler->select_database();

    if(Command::isEditable())
    {
        $sQuery='DELETE FROM mc'.$category_type.'s WHERE '.$category_type.'id="'.$_POST[''.$category_type.'id'].'" AND articleid="'.$_POST['articleid'].'"';
        mysql_query($sQuery) or die(mysql_error());
    }

    $sQuery="SELECT DISTINCT mc".$category_type."dictionary.detail, mc".$category_type."s.".$category_type."id FROM mc".$category_type."dictionary, mc".$category_type."s
                 WHERE mc".$category_type."s.".$category_type."id=mc".$category_type."dictionary.id
                 AND mc".$category_type."s.articleid='".$_POST['articleid']."'
    ";

    $sResult=mysql_query($sQuery) or die(mysql_error());

    while($sRow=mysql_fetch_array($sResult))
    {
        $content.=('<option value="'.$sRow[$category_type.'id'].'">'.mysql_real_escape_string($sRow['detail']).'</option>');
    }

    $handler->disconnect();

    echo $content;
}
?>
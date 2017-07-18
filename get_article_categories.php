<?php
session_start();
require_once('MySQLHandler.php');
require_once('CDTree.php');

$category_type=$_GET['category_type'];

$html_type='';
if(isset($_GET['html_type']))
{
    $html_type=$_GET['html_type'];
}
//$_POST['id']='83';

if($_POST['id'])
{
    $content='';

    $tree=new CDTree();
    $tree->createMySQLQueryTree("mc".$category_type."dictionary", "id", "detail", "parentid");
    
    $handler=new MySQLHandler();
    $handler->connect();
    $handler->select_database();

    $sQuery="SELECT mc".$category_type."dictionary.detail, mc".$category_type."s.".$category_type."id FROM mc".$category_type."dictionary, mc".$category_type."s
                 WHERE mc".$category_type."s.".$category_type."id=mc".$category_type."dictionary.id
                 AND mc".$category_type."s.articleid='".$_POST['id']."'
    ";

    $sResult=mysql_query($sQuery) or die(mysql_error());

    while($sRow=mysql_fetch_array($sResult))
    {
        if(strcmp($html_type, '')==0)
        {
            $content.=('<option value="'.$sRow[$category_type.'id'].'">'.mysql_real_escape_string($sRow['detail']).'</option>');
        }
        else
        {
            if(strcmp($html_type, 'select')==0)
            {
                $content.=('<option value="'.$sRow[$category_type.'id'].'">'.mysql_real_escape_string($sRow['detail']).'</option>');
            }
            else if(strcmp($html_type, 'link')==0)
            {
                $id=$sRow[$category_type.'id'];
                $detail=mysql_real_escape_string($sRow['detail']);
                $path=$tree->getPath($id);
                $nc=count($path);

                $expansion='';
                if($nc > 0)
                {
                    for($i=$nc-1; $i >= 0; $i-=1)
                    {
                        if($i==0)
                        {
                            $expansion.=('<ul><li style="font-weight:bold;list-style-type:square">'.$path[$i]);
                        }
                        else
                        {
                            $expansion.=('<ul><li style="list-style-type:square">'.$path[$i]);
                        }
                    }
                    
                    for($i=$nc-1; $i >= 0; $i-=1)
                    {
                        $expansion.=('</li></ul>');
                    }
                }
                
                $content.=('<a href="mcw_'.$category_type.'s.php?'.$category_type.'id[]='.$id.'" class="info">'.$detail.'<span>'.$expansion.'</span></a><br />');
            }
        }
    }

    $handler->disconnect();

    echo $content;
}
?>
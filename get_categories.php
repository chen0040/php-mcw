<?php
  
require_once('CDTree.php');

$category_type=$_GET['category_type'];

$apptree=new CDTree();
$apptree->createMySQLQueryTree("mc".$category_type."dictionary", "id", "detail", "parentid");

if(isset($_GET['html_type']))
{
    $html_type=$_GET['html_type'];
    if(strcmp($html_type, 'select')==0)
    {
        echo $apptree->tree2select(ucfirst($category_type));
    }
}
else
{
    echo $apptree->tree2select(ucfirst($category_type));
}
?>
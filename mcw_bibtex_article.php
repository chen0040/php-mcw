<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('MySQLHandler.php');

$article_id=$_GET['article_id'];

$handler=new MySQLHandler();
$handler->connect();
$handler->select_database();

$sQuery='SELECT * FROM mcarticles where id="'.$article_id.'"';

$result=mysql_query($sQuery) or die(mysql_error());
$row=mysql_fetch_array($result);

$type=$row['type'];
$title=$row['title'];
$pages=$row['pages'];
$year=$row['year'];
$issue=$row['loc'];
$note=$row['keywords'];

$sQuery='SELECT DISTINCT mcauthordictionary.detail FROM mcauthors, mcauthordictionary WHERE mcauthors.authorid=mcauthordictionary.id AND mcauthors.articleid="'.$article_id.'"';

$authors=Array();

$result=mysql_query($sQuery) or die(mysql_error());
if($result)
{
    while(($row=mysql_fetch_row($result)))
    {
        $authors[]=$row[0];
    }
}

$sQuery='SELECT DISTINCT mcjournaldictionary.detail FROM mcjournals, mcjournaldictionary WHERE mcjournals.journalid=mcjournaldictionary.id AND mcjournals.articleid="'.$article_id.'"';

$journal='';
$result=mysql_query($sQuery) or die(mysql_error());
if($result)
{
    $row=mysql_fetch_row($result);

    $journal=$row[0];

}

$handler->disconnect();

$serial='mcw'.date("Ymd_his");

echo '@'.$type.'{'.$serial.',<br />';
echo 'title={'.$title.'},<br />';
echo 'pages={'.$pages.'},<br />';
echo 'year={'.$year.'},<br />';
echo 'note={'.$note.'},<br />';
$author_txt='';
foreach($authors as $author)
{
    if(strcmp($author_txt, '')==0)
    {
        $author_txt.=$author;
    }
    else
    {
        $author_txt.=(' and '.$author);
    }
}
echo 'author={'.$author_txt.'},<br />';
if(strcmp($issue, '')!=0)
{
    if(preg_match('/(ISBN|isbn)( |=)(-|\d)+/', $issue, $match))
    {
        $isbn=preg_replace('/(ISBN|isbn)( |=)([-|\d]+)/', 'isbn={$3},<br />', $match[0]);
        echo $isbn;
    }

    if(preg_match('/(V|v)olume( |=)(\d+)/', $issue, $match))
    {
        $volume=preg_replace('/(V|v)olume( |=)(\d+)/', 'volume={$3},<br />', $match[0]);
        echo $volume;
    }
    if(preg_match('/(N|n)umber( |=)(\d+)/', $issue, $match))
    {
        $number=preg_replace('/(N|n)umber( |=)(\d+)/', 'number={$3},<br />', $match[0]);
        echo $number;
    }
    
}

if(strcmp($type, 'article')==0)
{
    echo 'journal={'.$journal.'}<br />';
}
else if(strcmp($type, 'inproceedings')==0)
{
    echo 'booktitle={'.$journal.'}<br />';
}
else if(strcmp($type, 'techreport')==0)
{
    echo 'institute={'.$journal.'}<br />';
}
else if(strcmp($type, 'phdthesis')==0)
{
    echo 'school={'.$journal.'}<br />';
}
else
{
    echo 'journal={'.$journal.'}<br />';
}
echo '}<br />';
?>

<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require_once('MySQLHandler.php');

if(isset($_POST['selected_skin']))
{
    $selected_skin=$_POST['selected_skin'];
    if(file_exists('theme.xml'))
    {
        $doc = new DOMDocument();
        $doc->load("theme.xml");

        $roots=$doc->getElementsByTagName("theme");
        foreach($roots as $root)
        {
            $root->setAttribute("skin", $selected_skin);
            break;
        }

        $handler=new MySQLHandler();
        $handler->connect();
        $doc->save(mysql_real_escape_string(getcwd().'\\theme.xml'));
        $handler->disconnect();
        echo '{
            "msg": "updated"
        }';
    }
    else
    {
        echo '{
            "msg": "failed"
        }';
    }
}
?>

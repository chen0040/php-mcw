<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require_once('MySQLHandler.php');

if(isset($_POST['username']) && isset($_POST['password']))
{
    $username=$_POST['username'];
    $password=$_POST['password'];

    $handler=new MySQLHandler();
    $handler->connect();
    $handler->select_database();

    $sQuery='SELECT mrole FROM mcmembership where muser="'.$username.'" AND mpwd="'.$password.'"';
    $rResult=mysql_query($sQuery) or die(mysql_error());
    $rRow=mysql_fetch_array($rResult);
    $count=mysql_num_rows($rResult);

    if($count > 0)
    {
        $role=$rRow['mrole'];
        $_SESSION['user']=$username;
        $_SESSION['role']=$role;
        echo '{
            "msg": "logined"
        }';
    }
    else
    {
        echo '{
            "msg": "Invalid username or password"
        }';
    }

    $handler->disconnect();
    
}
else
{
    echo '{
        "msg": "Invalid username or password";
    }';
}
?>

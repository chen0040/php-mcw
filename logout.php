<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
unset($_SESSION['user']);
unset($_SESSION['role']);

header("Location: index.php");
?>

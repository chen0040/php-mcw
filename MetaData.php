<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MetaData
 *
 * @author Xianshun
 */
 require_once("MObj.php");
 
class MetaData extends MObj{
    //put your code here
    public function __construct()
    {
        parent::__construct("MetaData");
    }

    public function render()
    {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
    }
}
?>

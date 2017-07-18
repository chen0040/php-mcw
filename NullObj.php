<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NullObj
 *
 * @author Xianshun
 */
 require_once("MObj.php");
 
class NullObj extends MObj{
    //put your code here
    public function __construct()
    {
        parent::__construct("NullObj");
    }
}
?>

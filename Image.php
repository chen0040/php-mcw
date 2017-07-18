<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Image
 *
 * @author Xianshun
 */
 require_once("Mobj.php");
 
class Image extends MObj{
    //put your code here
    private $mSrc;
    private $mStyle;
    public function __construct($id, $src, $style)
    {
        parent::__construct($id);
        $this->mSrc=$src;
        $this->mStyle=$style;
    }

    public function setSource($src)
    {
        $this->mSrc=$src;
    }
    
    public function render()
    {
        echo '<img src="' . $this->mSrc . '" border="0" style="'.$this->mStyle.'" />';
    }
}
?>

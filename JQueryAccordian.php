<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JQueryLabel
 *
 * @author AB
 */
require_once('MObj.php');
class JQueryAccordian extends MObj{
    //put your code here
    protected $mPanels;
    public function __construct($id)
    {
        parent::__construct($id);

        $this->mPanels=Array();
    }

    public function setPanel($title, $content)
    {
        $this->mPanels[$title]=$content;
    }

    public function render_header()
    {
        $content='$(function(){
            $("#'.$this->mId.'").accordion({header: "h3"});
        });';
        echo $content;
    }

    public function render()
    {
        $content='<div id="'.$this->mId.'">';
        foreach($this->mPanels as $title => $pc)
        {
            $content.=('<h3><a href="#">'.$title.'</a></h3>');
            $content.=('<div>'.$pc.'</div>');
        }
        $content.='</div>';
        echo $content;
    }
}
?>

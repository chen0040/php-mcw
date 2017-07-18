<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JQueryButton
 *
 * @author Xianshun
 */
 require_once("MObj.php");
 
class JQueryLinkButton extends MObj{
    //put your code here
    private $mText;
    private $mLink;
    private $mHighlighted;
    private $mDetail;
   
    public function __construct($id, $text, $link, $detail)
    {
        parent::__construct($id);
        $this->mText=$text;
        $this->mLink=$link;
        $this->mDetail=$detail;
        
        $this->addCSS("codezone/css/button.css");
        $this->addScript("codezone/js/button.js");
    }

    public function getId()
    {
        return $this->mId;
    }

    public function getText()
    {
        return $this->mText;
    }

    public function highlight($highlighted)
    {
        $this->mHighlighted=$highlighted;
        
    }

    public function setText($text)
    {
        $this->mText=$text;
    }

    public function render()
    {        
        echo '<a title="' . $this->mDetail . '" id="' . $this->mId . '" href="'.$this->mLink.'">'. $this->mText . '</a>';
    }

    public function render_with_format($format)
    {
        $state='ui-state-default';
        if($this->mHighlighted)
        {
            $state='ui-state-active';
        }
        $this->mStyle='fg-button '.$state.' ui-corner-all';
        
        $img='';
        if(strcmp($this->mDetail, '')!=0)
        {
            $img='<img src="'.$this->mDetail.'" border="0" /> ';
        }
        echo '<a class="' . $this->mStyle . '" id="' . $this->mId . '" '.$format.' href="'.$this->mLink.'" title="Go to homepage">'. $img . $this->mText . '</a>';
    }

    public function render_header()
    {
        /*
        $content='
        $(function(){
            $("#' . $this->mId . '").click(function(){
                window.location="' . $this->mLink . '";
            });
        });
        ';

        if($this->mHighlighted)
        {

            $content = $content . '$(function(){$("#' . $this->mId . '").css({"color": "#ff0000", "fontWeight": "bold"})});';
        }

        
        echo $content;*/
    }
}
?>

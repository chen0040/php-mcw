<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JQueryMenuBar
 *
 * @author Xianshun
 */
 require_once('MObj.php');
 require_once('JQueryLinkButton.php');
 
class JQueryMenuBar extends MObj{
    //put your code here
    private $mButtons=Array();
    private $mTitle;
    private $mImg;
    private $mVertical;
    
    public function __construct($id, $vertical)
    {
        parent::__construct($id);
		
        $this->mTitle='';
        $this->mImg='';

        $this->mVertical=$vertical;
    }

    public function setTitle($title)
    {
        $this->mTitle=$title;
    }

    public function setTitleIcon($ico)
    {
        $this->mImg=$ico;
    }

    public function highlight($text)
    {
        foreach($this->mButtons as $key => $button)
        {
            if(strcmp($button->getText(), $text)==0)
            {
                $button->highlight(true);
            }
            else
            {
                $button->highlight(false);
            }
        }
    }

    public function render()
    {
        if(strcmp($this->mTitle, '')!=0)
        {
            $img='';
            if(strcmp($this->mImg, '')!=0)
            {
                $img='<img src="'.$this->mImg.'" border="0" align="middle" /> ';
            }
            echo '<div class="ui-state-default ui-corner-all" style="padding-top:0px;padding-bottom:0px;padding-left:5px">'.$img.$this->mTitle.'</div>';
        }
        
        if(count($this->mButtons) > 0)

        
        if($this->mVertical)
        {
            foreach($this->mButtons as $key => $value)
            {
                $value->render_with_format('style="width: 100%;text-align:left"');
                echo '<br />';
            }
        }
        else
        {
		
            echo '<ul id="' . $this->mId . '" class="menu2">';
            foreach($this->mButtons as $key => $value)
            {
				echo '<li>';
                $value->render();
				echo '</li>';
            }
            echo '</ul>';
        }
        
    }

    public function render_header()
    {
        foreach($this->mButtons as $key => $button)
        {
            $button->render_header();
        }
    }

    public function addMenuItem($menuId, $text, $link, $img)
    {
        $button=new JQueryLinkButton($menuId, $text, $link, $img);
        $filenames=$button->getScripts();
        if(isset($filenames))
        {
            foreach($filenames as $key => $value)
            {
                $this->addScript($value);
            }
        }
        $filenames=$button->getCSS();
        if(isset($filenames))
        {
            foreach($filenames as $key => $value)
            {
                $this->addCSS($value);
            }
        }
        
        $this->mButtons[count($this->mButtons)]=$button;
    }

    
}
?>

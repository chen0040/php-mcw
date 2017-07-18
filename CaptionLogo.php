<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CaptionLogo
 *
 * @author Xianshun
 */

require_once('Image.php');
class CaptionLogo extends Image{
    //put your code here
    public function __construct($id)
    {
        parent::__construct($id, "", "");
		
		$this->addScript("jquery/jquery.corner.js");
    }

	public function render_header()
    {
        echo '$(function(){
            $("#'.$this->mId.'_title").corner("bevel top");
        });';
    }
	
    public function render()
    {
        
        echo '<div id="'.$this->mId.'_title" style="padding-top:5px;padding-bottom:5px;padding-left:5px;background: url(codezone/css/images/top_bar.png);">';
        if($this->hasLoginned())
        {

            echo '<table border="0" cellspacing="0" cellpadding="0" width="100%">'; //1000px
            echo '<tr><td>';
            echo '&nbsp;&nbsp;&nbsp;Welcome! <b>' . $this->getUser() . '</b> ';
            echo '</td><td align="right">';
            echo 'Today is <b>' . date("Y/m/d") .'</b>&nbsp;&nbsp;&nbsp;';
            echo '</td></tr>';
            echo '</table>';
        }
        else
        {
            echo '<table border="0" cellspacing="0" cellpadding="0" width="100%">'; //1000px
            echo '<tr><td>';
            echo '&nbsp;&nbsp;&nbsp;Welcome! <b>' . $_SERVER['REMOTE_ADDR'] . '</b> ';
            echo '</td><td align="right">';
            echo 'Today is <b>' . date("Y/m/d"). '</b>&nbsp;&nbsp;&nbsp;';
            echo '</td></tr>';
            echo '</table>';
        }
        
        echo '</div>';
    }
}
?>

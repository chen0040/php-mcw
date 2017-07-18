<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Footer
 *
 * @author Xianshun
 */
require_once('MObj.php');
class Footer extends MObj{
    //put your code here
    public function __construct($id)
    {
        parent::__construct($id);
        $this->addScript("jquery/jquery.corner.js");
    }
	
	public function render_header()
    {
        echo '$(function(){
            $("#'.$this->mId.'_title").corner("bevel bottom");
        });';
    }

    public function render()
    {
        //background-image:url(themes/black-tie/images/ui-bg_diagonals-thick_8_333333_40x40.png);
	echo '<hr />';
        echo '<div id="'.$this->mId.'_title" style="height:30px;background-image:url(codezone/css/images/footer2.png);background-color:#0174DD;color:white" >';
	//echo '<table><tr><td>Contact: Chen Xianshun</td></tr><tr><td>Email: <a href="mailto:chen0469@ntu.edu.sg">chen0469@ntu.edu.sg</a></td></tr></table>';
	echo '</div>';
        //echo '<br /><img id="'.$this->mId.'" src="codezone/css/images/footer.png" />';
        
    }
}
?>

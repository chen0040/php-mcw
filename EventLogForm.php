<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EventLogForm
 *
 * @author Xianshun
 */
require_once('JQueryPanel.php');

class EventLogForm extends JQueryPanel{
    //put your code here
    public function __construct($id)
    {
        parent::__construct($id);

        $this->setIcon("codezone/css/images/Automator-icon.png");

        $this->setTitle('<b>Recent Events</b>');
        $this->setWidth(250);
    }

    public function render_header()
    {
        echo '$(function(){
            
        });';
    }

    public function render()
    {
        $format="color: black;";
        if($this->mWidth != -1)
        {
            $format.='width:'.$this->mWidth.'px;';
        }
        
        $img='';
        if(strcmp($this->mImg, '')!=0)
        {
            $img='<img src="'.$this->mImg.'" border="0" align="middle" /> ';
        }

        echo '<form id="'.$this->mId.'_formEventLog" method="post" action="" />';
        echo '<table width="100%" bgcolor="white" style="'.$format.'">';
        echo '<tr><td class="ui-state-default ui-corner-all">';
        echo $img.$this->mTitle;
        echo '</td></tr>';
        
        echo '<tr><td style="border-style:solid; border-width: 1px; border-color:silver" ><table border="0" style="color: black;" bgcolor="white" cellpadding="2">';
        
        echo '<tr><td colspan="2"><button class="fg-button ui-state-default ui-corner-all" id="'.$this->mId.'_btnMoreEvent">More</button></td></tr>';
        echo '</table>';
        echo '</td></tr>';
        echo '</table>';
        echo '</form>';
    }
}
?>

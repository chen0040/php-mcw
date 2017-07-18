<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GUIDesignPanel
 *
 * @author Xianshun
 */
require_once('MObj.php');
class GUIDesignPanel extends MObj{
    //put your code here
    private $mTitle;
    private $mImg;
    public function __construct($id)
    {
        parent::__construct($id);
        $this->mImg='';
        $this->mTitle='<b>GUI Settings</b>';
    }
    public function setTitle($title)
    {
        $this->mTitle=$title;
    }
   
    public function setIcon($img)
    {
        $this->mImg=$img;
    }

    public function render_header()
    {
        $skins_text='';
        $selected_skin='';
        if(file_exists('theme.xml'))
        {
            $doc = new DOMDocument();
            $doc->load("theme.xml");

            $roots=$doc->getElementsByTagName("theme");
            foreach($roots as $root)
            {
                $selected_skin=$root->getAttribute("skin");
                break;
            }
        }
        $content='$(function() {
		$("#'.$this->mId.'_tabs").tabs();
                $("#'.$this->mId.'_skins option[value=\''.$selected_skin.'\']").attr("selected", true);
                
                $("#'.$this->mId.'_btnUpdateSkin").click(function(){
                    var pselected_skin=$("#'.$this->mId.'_skins").val();
                    if(pselected_skin != null)
                    {
                        //var selected_text=$("#'.$this->mId.'_skins :selected").text();
                        
                        $.post("edit_skin.php", {selected_skin: pselected_skin}, function(data){
                            if(data.msg=="updated")
                            {
                                alert(data.msg);
                                location.reload(true);
                            }
                            else
                            {
                                alert(data.msg);
                            }
                        }, "json");
                    }
                    return false;
                });
	});
        ';
        echo $content;
    }

    public function render_hidden()
    {
        
    }

    public function render()
    {
        echo '<div class="ui-state-default ui-corner-all" style="padding-top:5px;padding-bottom:5px;padding-left:5px"><img src="codezone/css/images/Automator-icon.png" border="0" align="middle" /> '.$this->mTitle.'</div>';

        echo '<div id="'.$this->mId.'_tabs">';
        echo '<ul>';
        echo '<li><a href="#'.$this->mId.'_tabs-1">Skin</a></li>';
        echo '<li><a href="#'.$this->mId.'_tabs-2">Nunc tincidunt</a></li>';
        echo '</ul>';
        

        echo '<div id="'.$this->mId.'_tabs-1">';
        echo '<form id="'.$this->mId.'_tabs-1f" method="post" action="'.basename($_SERVER['PHP_SELF']).'">';
        echo '<table border="0" cellspacing="2" cellpadding="2">';
        echo '<tr><td>Skin:</td><td><select id="'.$this->mId.'_skins">'; 
        
        if(file_exists('theme.xml'))
        {
            $doc = new DOMDocument();
            $doc->load("theme.xml");

            $skins = $doc->getElementsByTagName("skin");
            foreach( $skins as $skin )
            {
                echo ('<option value=\''.$skin->getAttribute("name").'\' >'.$skin->getAttribute("name").'</option>');
            }
        }
        echo '</select>';
        echo '</td><td><button class="fg-button ui-state-default ui-corner-all" id="'.$this->mId.'_btnUpdateSkin">Update</button></td></tr>';
        echo '</table>';
        echo '</form>';
        echo '</div>';
        
        echo '<div id="'.$this->mId.'_tabs-2">';
        echo '</div>';

        echo '</div>';
    }
}
?>

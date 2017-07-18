<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoginForm
 *
 * @author Xianshun
 */
require('JQueryPanel.php');
class LoginForm extends JQueryPanel{
    //put your code here
    public function __construct($id)
    {
        parent::__construct($id);

        $this->setIcon("codezone/css/images/Automator-icon.png");

        $this->setTitle('<b>Login</b>');
        $this->setWidth(250);
    }

    public function render_header()
    {
        echo '$(function(){
            $("#'.$this->mId.'_btnLogin").click(function(){
                var pusername=$(\'input[name="'.$this->mId.'_username"]\').val();
                var ppassword=$(\'input[name="'.$this->mId.'_password"]\').val();
                $.post("login.php", {username: pusername, password: ppassword}, function(data){
                    if(data.msg=="logined")
                    {
                        location.reload(true);
                    }
                    else
                    {
                        alert(data.msg);
                    }
                }, "json");

                return false;
            });
        });';
    }

    public function render()
    {
        if($this->hasLoginned())
        {
            return;
        }
        
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

        echo '<form id="'.$this->mId.'_formLogin" method="post" action="" />';
        echo '<table width="100%" bgcolor="white" style="'.$format.'">';
        echo '<tr><td class="ui-state-default ui-corner-all">';
        echo $img.$this->mTitle;
        echo '</td></tr>';
        
        echo '<tr><td style="border-style:solid; border-width: 1px; border-color:silver" ><table border="0" style="color: black;" bgcolor="white" cellpadding="2">';
        echo '<tr><td>Username: </td><td><input type="text" name="'.$this->mId.'_username" value="" style="width:100%" /></td></tr>';
        echo '<tr><td>Password: </td><td><input type="password" name="'.$this->mId.'_password" value="" style="width:100%" /></td></tr>';
        echo '<tr><td colspan="2"><button class="fg-button ui-state-default ui-corner-all" id="'.$this->mId.'_btnLogin">Login</button></td></tr>';
        echo '</table>';
        echo '</td></tr>';
        echo '</table>';
        echo '</form>';
    }
}
?>

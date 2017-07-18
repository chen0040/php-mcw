<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AccountList
 *
 * @author AB
 */
 require_once('JQueryDataTable.php');

class AccountList extends JQueryDataTable {
    //put your code here
    public function __construct($id)
    {
        parent::__construct($id);

        $this->setAjaxSource("list_accounts.php");
        $this->addField("(+)", '{ "sClass": "center", "bSortable": false }');
        $this->addField("Name");
        $this->addField("Password");
        $this->addField("Role");
    }

    public function getScript_onRowOpened()
    {
        $content="
        function " . $this->mId . "_onRowOpened(oData, iRow)
        {
            var name=oData[1];
            var password=oData[2];
            var role=oData[3];
            var sRet='<table border=\"0\"><tr><td><b>Name: </b></td><td>'+name+'</td></tr><tr><td><b>Password: </b></td><td>'+password+'</td></tr><tr><td><b>Role: </b></td><td>'+role+'</td></tr></table>';


            return sRet;

        }
        ";

        return $content;
    }

    public function render_header()
    {
        parent::render_header();
        $content= '$(function(){';
        $content.='
        $("#'. $this->mId .'_dlgInstruction").dialog({
            autoOpen: false,
            width: 500,
            modal: true,
            buttons: {
                "Close": function() {
                     $(this).dialog("close");
                 }
            }
        });

        $("#'. $this->mId .'_dlgAccount").dialog({
            autoOpen: false,
            width: 500,
            modal: true,
            buttons: {
                "OK": function() {
                    var action=$("#'.$this->mId.'_dlgAccount_action").text();
                    var pid=$("input[name=\"'.$this->mId.'_dlgAccount_id\"]").val();
                    var pname=$("input[name=\"'.$this->mId.'_dlgAccount_name\"]").val();
                    var ppassword=$("input[name=\"'.$this->mId.'_dlgAccount_password\"]").val();
                    var prole=$("input[name=\"'.$this->mId.'_dlgAccount_role\"]").val();

                    if(action=="Edit Account")
                    {
                        $.post("edit_account.php",
                            {id: pid, username: pname, password: ppassword, role: prole},
                            function(data){
                                if(data.msg=="updated")
                                {
                                    var anSelected = ' . $this->mId . '_fnGetSelected(var_' . $this->mId . ');
                                    if(anSelected.length>0)
                                    {
                                        var iRow = var_' . $this->mId . '.fnGetPosition( anSelected[0] );
                                        var_'.$this->mId.'.fnUpdate(pname, iRow, 1);
                                        var_'.$this->mId.'.fnUpdate(ppassword, iRow, 2);
                                        var_'.$this->mId.'.fnUpdate(prole, iRow, 3);
                                    }
                                }
                                else
                                {
                                    alert("Update Failed");
                                }
                        }, "json");
                    }
                    else if(action=="Add Account")
                    {
                        $.post("add_account.php",
                            {username: pname, password: ppassword, role: prole},
                            function(data){
                                if(data.msg=="added")
                                {
                                    var_'.$this->mId.'.fnAddData(data.account);
                                }
                                else
                                {
                                    alert("Update Failed");
                                }
                        }, "json");
                    }

                    $(this).dialog("close");
                },
                "Close": function() {
                    $(this).dialog("close");
                }
            }
        });';
        $content.='
        $("#' . $this->mId . '_AddAccount").click(function(){
            var name="";
            var password="";
            var role="";
            var hiddenId="";
            $("#'.$this->mId.'_dlgAccount_action").text("Add Account");
            $("input[name=\"'.$this->mId.'_dlgAccount_id\"]").val(hiddenId);
            $("input[name=\"'.$this->mId.'_dlgAccount_name\"]").val(name);
            $("input[name=\"'.$this->mId.'_dlgAccount_password\"]").val(password);
            $("input[name=\"'.$this->mId.'_dlgAccount_role\"]").val(role);

            $("#'. $this->mId .'_dlgAccount").dialog("open");
        });
        $("#' . $this->mId . '_EditAccount").click(function(){
            var anSelected = ' . $this->mId . '_fnGetSelected(var_' . $this->mId . ');
            if(anSelected.length>0)
            {
                var iRow = var_' . $this->mId . '.fnGetPosition( anSelected[0] );
                var oData=var_' . $this->mId . '.fnGetData(iRow);
                var hiddenId=oData[0];
                hiddenId=hiddenId.replace("<img src=\"dataTables/media/images/details_open.png\" /><!--", "");
                hiddenId=hiddenId.replace("-->", "");

                var name=oData[1];
                var password=oData[2];
                var role=oData[3];
                $("#'.$this->mId.'_dlgAccount_action").text("Edit Account");
                $("input[name=\"'.$this->mId.'_dlgAccount_id\"]").val(hiddenId);
                $("input[name=\"'.$this->mId.'_dlgAccount_name\"]").val(name);
                $("input[name=\"'.$this->mId.'_dlgAccount_password\"]").val(password);
                $("input[name=\"'.$this->mId.'_dlgAccount_role\"]").val(role);


                $("#'. $this->mId .'_dlgAccount").dialog("open");
            }
            else
            {
                alert("Please select a account first!");
            }
        });
        $("#' . $this->mId . '_DelAccount").click(function(){
            var anSelected = ' . $this->mId . '_fnGetSelected(var_' . $this->mId . ');
            if(anSelected.length>0)
            {
                var iRow = var_' . $this->mId . '.fnGetPosition( anSelected[0] );
                var oData=var_' . $this->mId . '.fnGetData(iRow);

                var hiddenId=oData[0];
                var name=oData[1];
                hiddenId=hiddenId.replace("<img src=\"dataTables/media/images/details_open.png\" /><!--", "");
                hiddenId=hiddenId.replace("-->", "");

                if(confirm("Do you want to delete account ["+name+"]?"))
                {
                    $.post("del_account.php", {id: hiddenId}, function(data){
                        var_' . $this->mId . '.fnDeleteRow( iRow );
                    }, "json");
                }
            }
            else
            {
                alert("Please select a account first!");
            }

            return false;
        });
        $("#' . $this->mId . '_Instruction").click(function(){

            $("#'. $this->mId .'_dlgInstruction").dialog("open");
            return false;
        });
        ';

        $content.='});';
        echo $content;
    }

    public function render()
    {
        echo ' <br /><div class="ui-state-default ui-corner-all" style="color:white;padding-top:5px;padding-bottom:5px;padding-left:5px"><img src="codezone/css/images/Automator-icon.png" border="0" align="middle" /> <b>Accounts</b></div>';
        echo '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="height:400px;min-height:400px;">';
        echo '<tr><td width="15%" valign="top">';
        echo '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
        echo '<tr><td><button class="fg-button ui-state-default ui-corner-all" id="' . $this->mId . '_AddAccount" style="width:100%"><img src="codezone/css/images/buttons/add.png" border="0" align="middle" /> Add Account</button></td></tr>';
        echo '<tr><td><button class="fg-button ui-state-default ui-corner-all" id="' . $this->mId . '_EditAccount" style="width:100%"><img src="codezone/css/images/buttons/edit.gif" border="0" align="middle" /> Edit Account</button></td></tr>';
        echo '<tr><td><button class="fg-button ui-state-default ui-corner-all" id="' . $this->mId . '_DelAccount" style="width:100%"><img src="codezone/css/images/buttons/del.png" border="0" align="middle" /> Del Account</button></td></tr>';
        echo '<tr><td><button class="fg-button ui-state-default ui-corner-all" id="' . $this->mId . '_Instruction" style="width:100%"><img src="codezone/css/images/buttons/about.png" border="0" align="middle" /> Instruction</button></td></tr>';
        echo '</table>';
        echo '</td><td valign="top">';
        parent::render();
        echo '</td></tr></table>';
    }

    public function render_hidden()
    {
        echo '
        <div id="'. $this->mId .'_dlgInstruction" title="Instruction">
            <ol>
                <li>Click "Add Account" to add account to the account list</li>
                <li>Click "Edit Account" to edit the selected account in the account list</li>
                <li>Click "Del Account" to delete the selected account in the account list</li>
            </ol>
        </div>
        ';
        echo '<div id="'. $this->mId .'_dlgAccount" title="Account">';
        echo '
        <form>
        <input type="hidden" name="'.$this->mId.'_dlgAccount_id" />
        <table>
        <tr>
        <td>Action: </td><td><span id="'.$this->mId.'_dlgAccount_action" /></td>
        </tr>
        <tr>
        <td>Name:</td>
        <td><input type="text" name="'.$this->mId.'_dlgAccount_name" /></td>
        </tr>
        <tr>
        <td>Password:</td>
        <td><input type="password" name="'.$this->mId.'_dlgAccount_password" /></td>
        </tr>
        <tr>
        <td>Role:</td>
        <td><input type="text" name="'.$this->mId.'_dlgAccount_role" /></td>
        </tr>
        </table>
        </form>';
        echo '</div>';
    }



}
?>

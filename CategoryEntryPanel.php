<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CategoryEntryPanel
 *
 * @author Xianshun
 */
require_once('JQueryDataTable.php');

class CategoryEntryPanel extends JQueryDataTable{
    //put your code here
    protected $mCategory;
    protected $mCategoryName;
    
    public function __construct($id, $category, $category_name)
    {
        parent::__construct($id);
        $this->mCategory=$category;
        $this->mCategoryName=$category_name;

        //echo "json_table.php?id=mc".$this->mCategory."dictionary.id&fc=1&f0=mc".$this->mCategory."dictionary.detail&tc=1&t0=mc".$this->mCategory."dictionary";
        $this->setAjaxSource("json_table.php?id=mc".$this->mCategory."dictionary.id&fc=1&f0=mc".$this->mCategory."dictionary.detail&tc=1&t0=mc".$this->mCategory."dictionary");
        $this->addField("(+)", '{ "sClass": "center", "bSortable": false }');
        $this->addField("Detail");

        $this->setTitle($this->mCategoryName." Entry Panel: Editor");
        $this->setTopOffset(1);
    }

    public function render()
    {
        echo '<table cellspacing="0" cellpadding="0" border="0" width="100%">';
        echo '<tr><td colspan="2">';
        $this->render_title();
        echo '</td></tr>';
        echo '<tr>';
        echo '<td valign="top">';
        $this->render_table();
        echo '</td>';
        echo '<td valign="top">';
        echo '<table cellspacing="0" cellpadding="0" border="0" style="width:150px">';
        echo '<tr><td><button class="fg-button ui-state-default ui-corner-all" id="' . $this->mId . '_AddCategory" style="width:100%"><img src="codezone/css/images/buttons/add.png" align="left" border="0" /> Add '.$this->mCategoryName.'</button></td></tr>';
        echo '<tr><td><button class="fg-button ui-state-default ui-corner-all" id="' . $this->mId . '_DelCategory" style="width:100%"><img src="codezone/css/images/buttons/del.png" align="left" border="0" /> Del '.$this->mCategoryName.'</button></td></tr>';
        echo '<tr><td><button class="fg-button ui-state-default ui-corner-all" id="' . $this->mId . '_Instruction" style="width:100%"><img src="codezone/css/images/buttons/about.png" align="left" border="0" /> Instruction</button></td></tr>';
        echo '</table>';
        echo '</td>';
        echo '</tr></table>';
    }

    public function render_hidden()
    {
        $content='';
        //dlgCategory
        $content.='<div id="'.$this->mId.'_dlgCategory" title="'.$this->mCategoryName.'">';
        $content.='<form id="'.$this->mId.'_dlgCategory_form" >';
        $content.='<table>';
        $content.='<tr>';
        $content.='<td>Detail: </td><td><input type="text" name="'.$this->mId.'_dlgCategory_detail" size="80" />';
        $content.='</td></tr>';
        $content.='<tr><td>';
        $content.='Category: </td><td><select id="'.$this->mId.'_dlgCategory_parent" />';
        $content.='</td></tr>';
        $content.='<tr><td colspan="2">Description:</td></tr>';
        $content.='<tr><td colspan="2"><textarea rows="8" cols="100" name="'.$this->mId.'_dlgCategory_description" id="'.$this->mId.'_dlgCategory_description"></textarea></td></tr>';
        $content.='</table>';
        $content.='</form>';
        $content.='</div>';

        $content.='
        <div id="'. $this->mId .'_dlgInstruction" title="Instruction">
            <ol>
                <li>Click "Add '.$this->mCategoryName.'" to add '.$this->mCategory.' to the article list</li>
                <li>Click "Delete '.$this->mCategoryName.'" to delete the selected '.$this->mCategory.' in the article list</li>
            </ol>
        </div>';

       echo $content;
    }

    public function render_header()
    {
        parent::render_header();
        $content= '
        $(function(){
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
            $("#'.$this->mId.'_Instruction").click(function(){
                $("#'. $this->mId .'_dlgInstruction").dialog("open");
                return false;
            });
        });';
        $content.='
        $(function(){
            $("#'.$this->mId.'_dlgCategory").dialog({

                autoOpen: false,
                width: 1000,
                modal: true,
                buttons: {
                    "OK": function() {
                        var pdetail=$("input[name=\''.$this->mId.'_dlgCategory_detail\']").val();
                        var pdescription=$("textarea#'.$this->mId.'_dlgCategory_description").val();
                        var selected_val=$("#'.$this->mId.'_dlgCategory_parent").val();
                        if(selected_val != null)
                        {
          
                            $.post("add_category.php?category_type='.$this->mCategory.'", {detail: pdetail, parentid: selected_val, description: pdescription}, function(data){
                                if(data.msg=="added")
                                {
                                    var_'.$this->mId.'.fnAddData(data.category);
                                    alert(data.msg);
                                }
                                else
                                {
                                    alert(data.msg);
                                }
                            }, "json");
                        }
                        $(this).dialog("close");
                    },
                    "Close": function() {
                        $(this).dialog("close");
                    }
                }
            });

            $("#'.$this->mId.'_AddCategory").click(function(){
                $("input[name=\''.$this->mId.'_dlgCategory_detail\']").val("");
                $("textarea#'.$this->mId.'_dlgCategory_description").val("");

                $("#'.$this->mId.'_dlgCategory_parent").val("");

                $.post("get_categories.php?category_type='.$this->mCategory.'", {},
                        function(data){
                            $("#'.$this->mId.'_dlgCategory_parent").html(data);
                            
                        }, "html");

                $("#'.$this->mId.'_dlgCategory").dialog("open");
                return false;
            });

            $("#'.$this->mId.'_DelCategory").click(function(){
                var anSelected = ' . $this->mId . '_fnGetSelected(var_' . $this->mId . ');
                if(anSelected.length>0)
                {
                    var iRow = var_' . $this->mId . '.fnGetPosition( anSelected[0] );
                    var oData=var_' . $this->mId . '.fnGetData(iRow);

                    var hiddenId=oData[0];
                    var detail=oData[1];
                    hiddenId=hiddenId.replace("<img src=\"dataTables/media/images/details_open.png\" /><!--", "");
                    hiddenId=hiddenId.replace("-->", "");

                    if(confirm("Do you want to delete '.$this->mCategory.'["+detail+"]?"))
                    {
                        $.post("del_category.php?category_type='.$this->mCategory.'", {id: hiddenId}, function(data){
                            if(data.msg=="deleted")
                            {
                                var_' . $this->mId . '.fnDeleteRow( iRow );
                            }
                            else
                            {
                                alert(data.msg);
                            }
                        }, "json");
                    }
                }
                else
                {
                    alert("Please select an '.$this->mCategory.' first!");
                }

                return false;
            });
        });
        ';
        echo $content;
    }
    
    public function getScript_onRowOpened()
    {
        $content="
        function " . $this->mId . "_onRowOpened(oData, iRow)
        {
            var hiddenData=oData[0];
            hiddenData=hiddenData.replace('<img src=\"dataTables/media/images/details_open.png\" /><!--', '');
            hiddenData=hiddenData.replace('-->', '');
            var detail=oData[1];

            var sRet='<div id=\"" . $this->mId . "_f'+hiddenData+'\">Loading</div>';

            $.ajax({
                url: 'get_category.php?category_type=".$this->mCategory."',
                type: 'POST',
                data: 'id='+hiddenData,
                dataType: 'json',
                success: function(data){
                    var sG='" . $this->mId . "_f'+hiddenData;
                    var sId=sG+'_id';
                    var sRowId=sG+'_rowId';
                    var sDetail=sG+'_detail';
                    var sCategory=sG+'_category';
                    var sDescription=sG+'_description';
                    var sUpdateBtn=sG+'_update';
                    var sForm='<form id=\"" . $this->mId . "_g'+hiddenData+'\" >';
                    //sForm+='iRow: '+iRow+'<br />';
                    sForm+='<input type=\"hidden\" name=\"'+sRowId+'\" id=\"'+sRowId+'\" value=\"'+iRow+'\" />';
                    sForm+='<input type=\"hidden\" name=\"'+sId+'\" id=\"'+sId+'\" value=\"'+hiddenData+'\" />';
                    sForm+='<table>';
                    sForm+='<tr><td colspan=\"2\"><a href=\"mcw_".$this->mCategory."s.php?".$this->mCategory."id[]='+hiddenData+'\" target=\"_blank\"><img src=\"codezone/css/images/buttons/buttonfind.gif\" border=\"0\" style=\"padding-right:10px\" alt=\"View\" title=\"View\" /></a></td></tr>';
                    sForm+='<tr><td>Detail: </td><td><input type=\"text\" name=\"'+sDetail+'\" id=\"'+sDetail+'\" value=\"'+detail+'\" size=\"80\"/></td></tr>';
                    sForm+='<tr><td>Category: </td><td><select id=\"'+sCategory+'\"></select></td></tr>';
                    sForm+='<tr><td colspan=\"2\">Description: </td></tr>';
                    sForm+='<tr><td colspan=\"2\"><textarea rows=\"20\" cols=\"90\" name=\"'+sDescription+'\" id=\"'+sDescription+'\"></textarea></td></tr>';
                    sForm+='<tr><td><button class=\"fg-button ui-state-default ui-corner-all\" id=\"'+sUpdateBtn+'\">Update</button>';
                    sForm+='</td></tr>';
                    sForm+='</table>';
                    sForm+='</form>';
                    $('#".$this->mId."_f'+hiddenData).html(sForm);

                    $('textarea#'+sDescription).val(data.description);
                   
                    var parentid=data.parentid;
                    $.post('get_categories.php?category_type=".$this->mCategory."', {id: hiddenData},
                        function(data){
                            $('#'+sCategory).html(data);
                            $('#'+sCategory+' option[value='+parentid+']').attr('selected', true);
                        }, 'html');


                    $('#'+sUpdateBtn).click(function(){
                        var hiddenId=$('input[name=\"'+sId+'\"]').val();
                        var rowId=parseInt($('input[name=\"'+sRowId+'\"]').val());
                        var pdetail=$('input[name=\"'+sDetail+'\"]').val();
                        var pdescription=$('textarea#'+sDescription).val();
                        var pparentid=$('#'+sCategory).val();
                        //alert(hiddenId);
                        //alert(rowId);
                        //alert(pdescription);
                        //alert(pdetail);
                        //alert(pparentid);

                        //alert('id='+hiddenId+'&detail='+pdetail+'&description='+pdescription+'&parentid='+pparentid);


                        $.post(
                            'edit_category.php?category_type=".$this->mCategory."',
                            {id: hiddenId, detail: pdetail, description: pdescription, parentid: pparentid},
                            function(rdata){
                                if(rdata.msg=='updated')
                                {
                                    var_".$this->mId.".fnUpdate(pdetail, rowId, 1, false);
                                }
                                alert(rdata.msg);
                            }, 'json');
                         return false;
                    });
            }});

            return sRet;

        }
        ";
        return $content;
    }
}
?>

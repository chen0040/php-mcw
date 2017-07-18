<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ArticleEntryPanel
 *
 * @author Xianshun
 */
require_once('JQueryDataTable.php');

class ArticleEntryPanel extends JQueryDataTable{
    //put your code here
    public function __construct($id)
    {
        parent::__construct($id);

        //$this->addScript("jqform/jquery.form.js");
        //$this->addScript("jqform/jquery.addtolist.latest.js");

        $this->setAjaxSource("json_table.php?id=mcarticles.id&fc=5&f0=mcarticles.title&f1=mcarticles.keywords&f2=mcarticles.pages&f3=mcarticles.year&f4=mcarticles.type&tc=1&t0=mcarticles");
        $this->addField("(+)", '{ "sClass": "center", "bSortable": false }');
        $this->addField("Title");
        $this->addField("Tag");
        $this->addField("Pages");
        $this->addField("Year");
        $this->addField("Type");

        $this->setTitle("Article Entry Panel: Editor");
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
        echo '<tr><td><button class="fg-button ui-state-default ui-corner-all" id="' . $this->mId . '_AddArticle" style="width:100%"><img src="codezone/css/images/buttons/add.png" align="left" border="0" /> Add Article</button></td></tr>';
        echo '<tr><td><button class="fg-button ui-state-default ui-corner-all" id="' . $this->mId . '_DelArticle" style="width:100%"><img src="codezone/css/images/buttons/del.png" align="left" border="0" /> Delete Article</button></td></tr>';
        echo '<tr><td><button class="fg-button ui-state-default ui-corner-all" id="' . $this->mId . '_Instruction" style="width:100%"><img src="codezone/css/images/buttons/about.png" align="left" border="0" /> Instruction</button></td></tr>';
        echo '</table>';
        echo '</td>';
        echo '</tr></table>';
    }

    public function render_hidden()
    {
        $content='';
        //dlgApplication
        $content.='<div id="'.$this->mId.'_dlgApplication" title="Application">';
        $content.='<form id="'.$this->mId.'_dlgApplication_form" >';
        $content.='<input type="text" name="'.$this->mId.'_dlgApplication_title" size="80" /><hr />';
        $content.='<input type="hidden" name="'.$this->mId.'_dlgApplication_article_id" id="'.$this->mId.'_dlgApplication_article_id" value="" /><hr />';
        $content.='<table style="width:100%;font-size:12px">';
        $content.='<tr><td colspan="2"><a href="mcw_edit_categories.php?category=application" target="_blank"><img src="codezone/css/images/buttons/edit.gif" border="0" align="right" style="padding-right:10px" alt="Edit" title="Edit" /> </a></td></tr>';
        $content.='<tr><td><button class="fg-button ui-state-default ui-corner-all" style="width:100%" id="'.$this->mId.'_DelApplication"><img src="codezone/css/images/buttons/del.png" align="middle" border="0" /> Remove Selected Application Type</button>';
        $content.='</td><td><button class="fg-button ui-state-default ui-corner-all" style="width:100%" id="'.$this->mId.'_AddApplication"><img src="codezone/css/images/buttons/add.png" align="middle" border="0" /> Add Available Application Type</button></td></tr>';
        $content.='<tr><td width="50%" valign="top">';
        $content.='<select id="'.$this->mId.'_list_article_applications" size="15" style="width:100%"/>';
        $content.='</td><td width="50%" valign="top">';
        $content.='<select id="'.$this->mId.'_list_applications" size="15" style="width:100%" />';
        $content.='</td></tr>';
        $content.='</table>';
        $content.='</form>';
        $content.='</div>';



        //dlgDesign
        $content.='<div id="'.$this->mId.'_dlgDesign" title="Design">';
        $content.='<form id="'.$this->mId.'_dlgDesign_form" >';
        $content.='<input type="text" name="'.$this->mId.'_dlgDesign_title" size="80" /><hr />';
        $content.='<input type="hidden" name="'.$this->mId.'_dlgDesign_article_id" id="'.$this->mId.'_dlgDesign_article_id" value="" />';
        $content.='<table style="width:100%;font-size:12px">';
        $content.='<tr><td colspan="2"><a href="mcw_edit_categories.php?category=design" target="_blank"><img src="codezone/css/images/buttons/edit.gif" border="0" align="right" style="padding-right:10px" alt="Edit" title="Edit" /> </a></td></tr>';
        $content.='<tr><td><button class="fg-button ui-state-default ui-corner-all" style="width:100%" id="'.$this->mId.'_DelDesign"><img src="codezone/css/images/buttons/del.png" align="middle" border="0" /> Remove Selected Design Type</button>';
        $content.='</td><td><button class="fg-button ui-state-default ui-corner-all" style="width:100%" id="'.$this->mId.'_AddDesign"><img src="codezone/css/images/buttons/add.png" align="middle" border="0" /> Add Available Design Type</button></td></tr>';
        $content.='<tr><td width="50%" valign="top">';
        $content.='<select id="'.$this->mId.'_list_article_designs" size="15" style="width:100%"/>';
        $content.='</td><td width="50%" valign="top">';
        $content.='<select id="'.$this->mId.'_list_designs" size="15" style="width:100%" />';
        $content.='</td></tr>';
        $content.='</table>';
        $content.='</form>';
        $content.='</div>';

        //dlgMemetic
        $content.='<div id="'.$this->mId.'_dlgMemetic" title="Memetic">';
        $content.='<form id="'.$this->mId.'_dlgMemetic_form" >';
        $content.='<input type="text" name="'.$this->mId.'_dlgMemetic_title" size="80" /><hr />';
        $content.='<input type="hidden" name="'.$this->mId.'_dlgMemetic_article_id" id="'.$this->mId.'_dlgMemetic_article_id" value="" />';
        $content.='<table style="width:100%;font-size:12px">';
        $content.='<tr><td colspan="2"><a href="mcw_edit_categories.php?category=memetic" target="_blank"><img src="codezone/css/images/buttons/edit.gif" border="0" align="right" style="padding-right:10px" alt="Edit" title="Edit" /> </a></td></tr>';
        $content.='<tr><td><button class="fg-button ui-state-default ui-corner-all" style="width:100%" id="'.$this->mId.'_DelMemetic"><img src="codezone/css/images/buttons/del.png" align="middle" border="0" /> Remove Selected Memetic Type</button>';
        $content.='</td><td><button class="fg-button ui-state-default ui-corner-all" style="width:100%" id="'.$this->mId.'_AddMemetic"><img src="codezone/css/images/buttons/add.png" align="middle" border="0" /> Add Available Memetic Type</button></td></tr>';
        $content.='<tr><td width="50%" valign="top">';
        $content.='<select id="'.$this->mId.'_list_article_memetics" size="15" style="width:100%"/>';
        $content.='</td><td width="50%" valign="top">';
        $content.='<select id="'.$this->mId.'_list_memetics" size="15" style="width:100%" />';
        $content.='</td></tr>';
        $content.='</table>';
        $content.='</form>';
        $content.='</div>';

        //dlgAuthor
        $content.='<div id="'.$this->mId.'_dlgAuthor" title="Author">';
        $content.='<form id="'.$this->mId.'_dlgAuthor_form" >';
        $content.='<input type="text" name="'.$this->mId.'_dlgAuthor_title" size="80" /><hr />';
        $content.='<input type="hidden" name="'.$this->mId.'_dlgAuthor_article_id" id="'.$this->mId.'_dlgAuthor_article_id" value="" />';
        $content.='<table style="width:100%;font-size:12px">';
        $content.='<tr><td colspan="2"><a href="mcw_edit_categories.php?category=author" target="_blank"><img src="codezone/css/images/buttons/edit.gif" border="0" align="right" style="padding-right:10px" alt="Edit" title="Edit" /> </a></td></tr>';
        $content.='<tr><td><button class="fg-button ui-state-default ui-corner-all" style="width:100%" id="'.$this->mId.'_DelAuthor"><img src="codezone/css/images/buttons/del.png" align="middle" border="0" /> Remove Selected Author Type</button>';
        $content.='</td><td><button class="fg-button ui-state-default ui-corner-all" style="width:100%" id="'.$this->mId.'_AddAuthor"><img src="codezone/css/images/buttons/add.png" align="middle" border="0" /> Add Available Author Type</button></td></tr>';
        $content.='<tr><td width="50%" valign="top">';
        $content.='<select id="'.$this->mId.'_list_article_authors" size="15" style="width:100%"/>';
        $content.='</td><td width="50%" valign="top">';
        $content.='<select id="'.$this->mId.'_list_authors" size="15" style="width:100%" />';
        $content.='</td></tr>';
        $content.='</table>';
        $content.='</form>';
        $content.='</div>';

        //dlgJournal
        $content.='<div id="'.$this->mId.'_dlgJournal" title="Journal">';
        $content.='<form id="'.$this->mId.'_dlgJournal_form" >';
        $content.='<input type="text" name="'.$this->mId.'_dlgJournal_title" size="80" /><hr />';
        $content.='<input type="hidden" name="'.$this->mId.'_dlgJournal_article_id" id="'.$this->mId.'_dlgJournal_article_id" value="" />';
        $content.='<table style="width:100%;font-size:12px">';
        $content.='<tr><td colspan="2"><a href="mcw_edit_categories.php?category=journal" target="_blank"><img src="codezone/css/images/buttons/edit.gif" border="0" align="right" style="padding-right:10px" alt="Edit" title="Edit" /> </a></td></tr>';
        $content.='<tr><td><button class="fg-button ui-state-default ui-corner-all" style="width:100%" id="'.$this->mId.'_DelJournal"><img src="codezone/css/images/buttons/del.png" align="middle" border="0" /> Remove Selected Journal Type</button>';
        $content.='</td><td><button class="fg-button ui-state-default ui-corner-all" style="width:100%" id="'.$this->mId.'_AddJournal"><img src="codezone/css/images/buttons/add.png" align="middle" border="0" /> Add Available Journal Type</button></td></tr>';
        $content.='<tr><td width="50%" valign="top">';
        $content.='<select id="'.$this->mId.'_list_article_journals" size="15" style="width:100%"/>';
        $content.='</td><td width="50%" valign="top">';
        $content.='<select id="'.$this->mId.'_list_journals" size="15" style="width:100%" />';
        $content.='</td></tr>';
        $content.='</table>';
        $content.='</form>';
        $content.='</div>';

        //dlgDefinition
        $content.='<div id="'.$this->mId.'_dlgDefinition" title="Definition">';
        $content.='<form id="'.$this->mId.'_dlgDefinition_form" >';
        $content.='<input type="text" name="'.$this->mId.'_dlgDefinition_title" size="80" /><hr />';
        $content.='<input type="hidden" name="'.$this->mId.'_dlgDefinition_article_id" id="'.$this->mId.'_dlgDefinition_article_id" value="" />';
        $content.='<table style="width:100%;font-size:12px">';
        $content.='<tr><td colspan="2"><a href="mcw_edit_categories.php?category=definition" target="_blank"><img src="codezone/css/images/buttons/edit.gif" border="0" align="right" style="padding-right:10px" alt="Edit" title="Edit" /> </a></td></tr>';
        $content.='<tr><td><button class="fg-button ui-state-default ui-corner-all" style="width:100%" id="'.$this->mId.'_DelDefinition"><img src="codezone/css/images/buttons/del.png" align="middle" border="0" /> Remove Selected Definition Type</button>';
        $content.='</td><td><button class="fg-button ui-state-default ui-corner-all" style="width:100%" id="'.$this->mId.'_AddDefinition"><img src="codezone/css/images/buttons/add.png" align="middle" border="0" /> Add Available Definition Type</button></td></tr>';
        $content.='<tr><td width="50%" valign="top">';
        $content.='<select id="'.$this->mId.'_list_article_definitions" size="15" style="width:100%"/>';
        $content.='</td><td width="50%" valign="top">';
        $content.='<select id="'.$this->mId.'_list_definitions" size="15" style="width:100%" />';
        $content.='</td></tr>';
        $content.='</table>';
        $content.='</form>';
        $content.='</div>';
        
        //dlgInstruction
        $content.='
        <div id="'. $this->mId .'_dlgInstruction" title="Instruction">
            <ol>
                <li>Click "Add Article" to add article to the article list</li>
                <li>Click "Delete Article" to delete the selected article in the article list</li>
            </ol>
        </div>';

        //dlgArticle
        $content.='
            <div id="'.$this->mId.'_dlgArticle" title="Article">
            <form id="'.$this->mId.'_dlgArticle_form" >
            <table border="0" cellspacing="0" cellpadding="0">
            <tr><td>Title: </td><td><input size="80" type="text" value="" name="'.$this->mId.'_dlgArticle_title" id="'.$this->mId.'_dlgArticle_title" /></td></tr>
            <tr><td>Year: </td><td><input type="text" value="" name="'.$this->mId.'_dlgArticle_year" id="'.$this->mId.'_dlgArticle_year" /></td></tr>
            <tr><td>Type: </td><td><input type="text" value="" name="'.$this->mId.'_dlgArticle_type" id="'.$this->mId.'_dlgArticle_type" /></td></tr>
            <tr><td>Issue: </td><td><input type="text" value="" name="'.$this->mId.'_dlgArticle_loc" id="'.$this->mId.'_dlgArticle_loc" /></td></tr>
            <tr><td>Pages: </td><td><input type="text" value="" name="'.$this->mId.'_dlgArticle_pages" id="'.$this->mId.'_dlgArticle_pages" /></td></tr>
            <tr><td>Tag: </td><td><input type="text" value="" name="'.$this->mId.'_dlgArticle_keywords" id="'.$this->mId.'_dlgArticle_keywords" /></td></tr>

            </table>
            <table border="0" cellspacing="0" cellpadding="0">
            <tr><td>
            abstract</td><td>
            <textarea rows="8" cols="100" name="'.$this->mId.'_dlgArticle_abstract" id="'.$this->mId.'_dlgArticle_abstract"></textarea>
            </td></tr>
            <tr><td>
            comment</td><td>
            <textarea rows="8" cols="100" name="'.$this->mId.'_dlgArticle_comment" id="'.$this->mId.'_dlgArticle_comment"></textarea>
            </td></tr>
            </table>
            </form>
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
            $("#'.$this->mId.'_dlgArticle").dialog({

                autoOpen: false,
                width: 1000,
                modal: true,
                buttons: {
                    "OK": function() {
                        var ptitle=$("input[name=\''.$this->mId.'_dlgArticle_title\']").val();
                        var pyear=$("input[name=\''.$this->mId.'_dlgArticle_year\']").val();
                        var ptype=$("input[name=\''.$this->mId.'_dlgArticle_type\']").val();
                        var ppages=$("input[name=\''.$this->mId.'_dlgArticle_pages\']").val();
                        var ploc=$("input[name=\''.$this->mId.'_dlgArticle_loc\']").val();
                        var pcomment=$("textarea#'.$this->mId.'_dlgArticle_comment").val();
                        var pabstract=$("textarea#'.$this->mId.'_dlgArticle_abstract").val();
                        var pkeywords=$("input[name=\''.$this->mId.'_dlgArticle_keywords\']").val();
                        $.post("add_article.php", {title: ptitle, year: pyear, type: ptype, pages: ppages, loc: ploc, abstract: pabstract, comment: pcomment, keywords: pkeywords}, function(data){
                            if(data.msg=="added")
                            {
                                var_'.$this->mId.'.fnAddData(data.article);
                            }
                            alert(data.msg);
                        }, "json");

                        $(this).dialog("close");
                    },
                    "Close": function() {
                        $(this).dialog("close");
                    }
                }
            });
            $("#'.$this->mId.'_AddArticle").click(function(){
                $("#'.$this->mId.'_dlgArticle").dialog("open");
                return false;
            });
            $("#'.$this->mId.'_DelArticle").click(function(){
                var anSelected = ' . $this->mId . '_fnGetSelected(var_' . $this->mId . ');
                if(anSelected.length>0)
                {
                    var iRow = var_' . $this->mId . '.fnGetPosition( anSelected[0] );
                    var oData=var_' . $this->mId . '.fnGetData(iRow);

                    var hiddenId=oData[0];
                    var title=oData[1];
                    hiddenId=hiddenId.replace("<img src=\"dataTables/media/images/details_open.png\" /><!--", "");
                    hiddenId=hiddenId.replace("-->", "");

                    if(confirm("Do you want to delete contact ["+title+"]?"))
                    {
                        $.post("del_article.php", {id: hiddenId}, function(data){
                            if(data.msg=="deleted")
                            {
                                var_' . $this->mId . '.fnDeleteRow( iRow );
                            }
                            alert(data.msg);
                        }, "json");
                    }
                }
                else
                {
                    alert("Please select an article first!");
                }

                return false;
            });
        });
        ';

        //dlgApplication
        $content.='$(function(){
            $("#'.$this->mId.'_dlgApplication").dialog({
                
                autoOpen: false,
                width: 700,
                modal: true,
                buttons: {
                    "Close": function() {
                        $(this).dialog("close");
                    }
                }
            });

            $("#'.$this->mId.'_AddApplication").click(function(){
                var selected_val=$("#'.$this->mId.'_list_applications").val();
                if(selected_val != null)
                {
                    var selected_text=$("#'.$this->mId.'_list_applications :selected").text();
                    //alert(selected_val + ": "+selected_text);

                    var article_id=$("input[name=\''.$this->mId.'_dlgApplication_article_id\']").val();

                    $.post("add_article_category.php?category_type=application", {applicationid: selected_val, articleid: article_id},
                        function(data){
                            $("#'.$this->mId.'_list_article_applications").html(data);
                        }, "html"
                    );
                }
                else
                {
                    alert("Please select a application type from the right panel to add!");
                }
                return false;
            });

            $("#'.$this->mId.'_DelApplication").click(function(){
                var selected_val=$("#'.$this->mId.'_list_article_applications").val();
                if(selected_val != null)
                {
                    var article_id=$("input[name=\''.$this->mId.'_dlgApplication_article_id\']").val();

                    var selected_text=$("#'.$this->mId.'_list_article_applications :selected").text();

                     $.post("del_article_category.php?category_type=application", {applicationid: selected_val, articleid: article_id},
                        function(data){
                            $("#'.$this->mId.'_list_article_applications").html(data);
                        }, "html"
                    );
                }
                else
                {
                    alert("Please select a application type from the left panel to remove!");
                }
                return false;
            });
        });';

        //dlgDesign
        $content.='$(function(){
            $("#'.$this->mId.'_dlgDesign").dialog({

                autoOpen: false,
                width: 700,
                modal: true,
                buttons: {
                    "Close": function() {
                        $(this).dialog("close");
                    }
                }
            });

            $("#'.$this->mId.'_AddDesign").click(function(){
                var selected_val=$("#'.$this->mId.'_list_designs").val();
                if(selected_val != null)
                {
                    var selected_text=$("#'.$this->mId.'_list_designs :selected").text();
                    //alert(selected_val + ": "+selected_text);

                    var article_id=$("input[name=\''.$this->mId.'_dlgDesign_article_id\']").val();

                    $.post("add_article_category.php?category_type=design", {designid: selected_val, articleid: article_id},
                        function(data){
                            $("#'.$this->mId.'_list_article_designs").html(data);
                        }, "html"
                    );
                }
                else
                {
                    alert("Please select a design type from the right panel to add!");
                }
                return false;
            });

            $("#'.$this->mId.'_DelDesign").click(function(){
                var selected_val=$("#'.$this->mId.'_list_article_designs").val();
                if(selected_val != null)
                {
                    var article_id=$("input[name=\''.$this->mId.'_dlgDesign_article_id\']").val();

                    var selected_text=$("#'.$this->mId.'_list_article_designs :selected").text();

                     $.post("del_article_category.php?category_type=design", {designid: selected_val, articleid: article_id},
                        function(data){
                            $("#'.$this->mId.'_list_article_designs").html(data);
                        }, "html"
                    );
                }
                else
                {
                    alert("Please select a design type from the left panel to remove!");
                }
                return false;
            });
        });';
        
        //dlgMemetic
        $content.='$(function(){
            $("#'.$this->mId.'_dlgMemetic").dialog({

                autoOpen: false,
                width: 700,
                modal: true,
                buttons: {
                    "Close": function() {
                        $(this).dialog("close");
                    }
                }
            });

            $("#'.$this->mId.'_AddMemetic").click(function(){
                var selected_val=$("#'.$this->mId.'_list_memetics").val();
                if(selected_val != null)
                {
                    var selected_text=$("#'.$this->mId.'_list_memetics :selected").text();
                    //alert(selected_val + ": "+selected_text);

                    var article_id=$("input[name=\''.$this->mId.'_dlgMemetic_article_id\']").val();

                    $.post("add_article_category.php?category_type=memetic", {memeticid: selected_val, articleid: article_id},
                        function(data){
                            $("#'.$this->mId.'_list_article_memetics").html(data);
                        }, "html"
                    );
                }
                else
                {
                    alert("Please select a memetic type from the right panel to add!");
                }
                return false;
            });

            $("#'.$this->mId.'_DelMemetic").click(function(){
                var selected_val=$("#'.$this->mId.'_list_article_memetics").val();
                if(selected_val != null)
                {
                    var article_id=$("input[name=\''.$this->mId.'_dlgMemetic_article_id\']").val();

                    var selected_text=$("#'.$this->mId.'_list_article_memetics :selected").text();

                     $.post("del_article_category.php?category_type=memetic", {memeticid: selected_val, articleid: article_id},
                        function(data){
                            $("#'.$this->mId.'_list_article_memetics").html(data);
                        }, "html"
                    );
                }
                else
                {
                    alert("Please select a memetic type from the left panel to remove!");
                }
                return false;
            });
        });';

        //dlgJournal
        $content.='$(function(){
            $("#'.$this->mId.'_dlgJournal").dialog({

                autoOpen: false,
                width: 700,
                modal: true,
                buttons: {
                    "Close": function() {
                        $(this).dialog("close");
                    }
                }
            });

            $("#'.$this->mId.'_AddJournal").click(function(){
                var selected_val=$("#'.$this->mId.'_list_journals").val();
                if(selected_val != null)
                {
                    var selected_text=$("#'.$this->mId.'_list_journals :selected").text();
                    //alert(selected_val + ": "+selected_text);

                    var article_id=$("input[name=\''.$this->mId.'_dlgJournal_article_id\']").val();

                    $.post("add_article_category.php?category_type=journal", {journalid: selected_val, articleid: article_id},
                        function(data){
                            $("#'.$this->mId.'_list_article_journals").html(data);
                        }, "html"
                    );
                }
                else
                {
                    alert("Please select a journal type from the right panel to add!");
                }
                return false;
            });

            $("#'.$this->mId.'_DelJournal").click(function(){
                var selected_val=$("#'.$this->mId.'_list_article_journals").val();
                if(selected_val != null)
                {
                    var article_id=$("input[name=\''.$this->mId.'_dlgJournal_article_id\']").val();

                    var selected_text=$("#'.$this->mId.'_list_article_journals :selected").text();

                     $.post("del_article_category.php?category_type=journal", {journalid: selected_val, articleid: article_id},
                        function(data){
                            $("#'.$this->mId.'_list_article_journals").html(data);
                        }, "html"
                    );
                }
                else
                {
                    alert("Please select a journal type from the left panel to remove!");
                }
                return false;
            });
        });';

        //dlgDefinition
        $content.='$(function(){
            $("#'.$this->mId.'_dlgDefinition").dialog({

                autoOpen: false,
                width: 700,
                modal: true,
                buttons: {
                    "Close": function() {
                        $(this).dialog("close");
                    }
                }
            });

            $("#'.$this->mId.'_AddDefinition").click(function(){
                var selected_val=$("#'.$this->mId.'_list_definitions").val();
                if(selected_val != null)
                {
                    var selected_text=$("#'.$this->mId.'_list_definitions :selected").text();
                    //alert(selected_val + ": "+selected_text);

                    var article_id=$("input[name=\''.$this->mId.'_dlgDefinition_article_id\']").val();

                    $.post("add_article_category.php?category_type=definition", {definitionid: selected_val, articleid: article_id},
                        function(data){
                            $("#'.$this->mId.'_list_article_definitions").html(data);
                        }, "html"
                    );
                }
                else
                {
                    alert("Please select a definition type from the right panel to add!");
                }
                return false;
            });

            $("#'.$this->mId.'_DelDefinition").click(function(){
                var selected_val=$("#'.$this->mId.'_list_article_definitions").val();
                if(selected_val != null)
                {
                    var article_id=$("input[name=\''.$this->mId.'_dlgDefinition_article_id\']").val();

                    var selected_text=$("#'.$this->mId.'_list_article_definitions :selected").text();

                     $.post("del_article_category.php?category_type=definition", {definitionid: selected_val, articleid: article_id},
                        function(data){
                            $("#'.$this->mId.'_list_article_definitions").html(data);
                        }, "html"
                    );
                }
                else
                {
                    alert("Please select a definition type from the left panel to remove!");
                }
                return false;
            });
        });';

        //dlgAuthor
        $content.='$(function(){
            $("#'.$this->mId.'_dlgAuthor").dialog({

                autoOpen: false,
                width: 700,
                modal: true,
                buttons: {
                    "Close": function() {
                        $(this).dialog("close");
                    }
                }
            });

            $("#'.$this->mId.'_AddAuthor").click(function(){
                var selected_val=$("#'.$this->mId.'_list_authors").val();
                if(selected_val != null)
                {
                    var selected_text=$("#'.$this->mId.'_list_authors :selected").text();
                    //alert(selected_val + ": "+selected_text);

                    var article_id=$("input[name=\''.$this->mId.'_dlgAuthor_article_id\']").val();

                    $.post("add_article_category.php?category_type=author", {authorid: selected_val, articleid: article_id},
                        function(data){
                            $("#'.$this->mId.'_list_article_authors").html(data);
                        }, "html"
                    );
                }
                else
                {
                    alert("Please select a author type from the right panel to add!");
                }
                return false;
            });

            $("#'.$this->mId.'_DelAuthor").click(function(){
                var selected_val=$("#'.$this->mId.'_list_article_authors").val();
                if(selected_val != null)
                {
                    var article_id=$("input[name=\''.$this->mId.'_dlgAuthor_article_id\']").val();

                    var selected_text=$("#'.$this->mId.'_list_article_authors :selected").text();

                     $.post("del_article_category.php?category_type=author", {authorid: selected_val, articleid: article_id},
                        function(data){
                            $("#'.$this->mId.'_list_article_authors").html(data);
                        }, "html"
                    );
                }
                else
                {
                    alert("Please select a author type from the left panel to remove!");
                }
                return false;
            });
        });';

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

            var sRet='<div id=\"" . $this->mId . "_f'+hiddenData+'\">Loading</div>';

            $.ajax({
                url: 'get_article.php',
                type: 'POST',
                data: 'id='+hiddenData,
                dataType: 'json',
                success: function(data){
                    var sG='" . $this->mId . "_f'+hiddenData;
                    var sId=sG+'_id';
                    var sRowId=sG+'_rowId';
                    var sTitle=sG+'_title';
                    var sYear=sG+'_year';
                    var sLoc=sG+'_locs';
                    var sPages=sG+'_pages';
                    var sType=sG+'_type';
                    var sComment=sG+'_comment';
                    var sAbstract=sG+'_abstract';
                    var sKeywords=sG+'_keywords';
                    var sUpdateBtn=sG+'_update';
                    var sApplicationBtn=sG+'_application';
                    var sDesignBtn=sG+'_design';
                    var sAuthorBtn=sG+'_author';
                    var sJournalBtn=sG+'_journal';
                    var sDefinitionBtn=sG+'_definition';
                    var sMemeticBtn=sG+'_memetic';
                    var sForm='<form id=\"" . $this->mId . "_g'+hiddenData+'\" >';
                    //sForm+='iRow: '+iRow+'<br />';
                    sForm+='<input type=\"hidden\" name=\"'+sRowId+'\" id=\"'+sRowId+'\" value=\"'+iRow+'\" />';
                    sForm+='<input type=\"hidden\" name=\"'+sId+'\" id=\"'+sId+'\" value=\"'+hiddenData+'\" />';
                    sForm+='<table border=\"0\">';
                    sForm+='<tr><td>Title: </td><td><input size=\"100\" type=\"textfield\" value=\"'+data.title+'\" name=\"'+sTitle+'\" id=\"'+sTitle+'\" /></td><td><input type=\"hidden\" value=\"'+data.id+'\" name=\"'+sId+'\" id=\"'+sId+'\"/></td></tr>';
                    sForm+='<tr><td>Year: </td><td><input type=\"textfield\" value=\"'+data.year+'\" name=\"'+sYear+'\" id=\"'+sYear+'\" /></td></tr>';
                    sForm+='<tr><td>Type: </td><td><input type=\"textfield\" value=\"'+data.type+'\" name=\"'+sType+'\" id=\"'+sType+'\" /></td></tr>';
                    sForm+='<tr><td>Issue: </td><td><input type=\"textfield\" value=\"'+data.loc+'\" name=\"'+sLoc+'\" id=\"'+sLoc+'\" /></td></tr>';
                    sForm+='<tr><td>Pages: </td><td><input type=\"textfield\" value=\"'+data.pages+'\" name=\"'+sPages+'\" id=\"'+sPages+'\" /></td></tr>';
                    sForm+='<tr><td>Tag: </td><td><input type=\"textfield\" value=\"'+data.keywords+'\" name=\"'+sKeywords+'\" id=\"'+sKeywords+'\" /></td></tr>';
                    sForm+='</table>';
                    sForm+='<table>';
                    sForm+='<tr><td>Abstract:</td></tr>';
                    sForm+='<tr><td><textarea rows=\"20\" cols=\"90\" name=\"'+sAbstract+'\" id=\"'+sAbstract+'\"></textarea></td></tr>';
                    sForm+='<tr><td>Comment:</td></tr>';
                    sForm+='<tr><td><textarea rows=\"8\" cols=\"90\" name=\"'+sComment+'\" id=\"'+sComment+'\"></textarea></td></tr>';
                    sForm+='<tr><td><button class=\"fg-button ui-state-default ui-corner-all\" id=\"'+sUpdateBtn+'\">Update</button>';
                    sForm+='<button class=\"fg-button ui-state-default ui-corner-all\" id=\"'+sApplicationBtn+'\">Application</button>';
                    sForm+='<button class=\"fg-button ui-state-default ui-corner-all\" id=\"'+sDesignBtn+'\">Design Issue</button>';
                    sForm+='<button class=\"fg-button ui-state-default ui-corner-all\" id=\"'+sMemeticBtn+'\">Memetic</button>';
                    sForm+='<button class=\"fg-button ui-state-default ui-corner-all\" id=\"'+sAuthorBtn+'\">Author</button>';
                    sForm+='<button class=\"fg-button ui-state-default ui-corner-all\" id=\"'+sJournalBtn+'\">Journal</button>';
                    sForm+='<button class=\"fg-button ui-state-default ui-corner-all\" id=\"'+sDefinitionBtn+'\">Definition</button>';
                    sForm+='</td></tr>';
                    sForm+='</table>';
                    sForm+='</form>';
                    $('#".$this->mId."_f'+hiddenData).html(sForm);
                    $('textarea#'+sAbstract).val(data.abstract);
                    $('textarea#'+sComment).val(data.comment);
                    $('#'+sUpdateBtn).click(function(){
                        var hiddenId=$('input[name=\"'+sId+'\"]').val();
                        var rowId=parseInt($('input[name=\"'+sRowId+'\"]').val());
                        var ptitle=$('input[name=\"'+sTitle+'\"]').val();
                        var pyear=$('input[name=\"'+sYear+'\"]').val();
                        var ptype=$('input[name=\"'+sType+'\"]').val();
                        var ppages=$('input[name=\"'+sPages+'\"]').val();
                        var ploc=$('input[name=\"'+sLoc+'\"]').val();
                        var pcomment=$('textarea#'+sComment).val();
                        var pabstract=$('textarea#'+sAbstract).val();
                        var pkeywords=$('input[name=\"'+sKeywords+'\"]').val();
                        
                        $.post(
                            'edit_article.php',
                            {id:hiddenId, title:ptitle, year:pyear, type:ptype, pages: ppages, loc: ploc, abstract: pabstract, comment: pcomment, keywords: pkeywords},
                            function(rdata){
                                if(rdata.msg=='updated')
                                {
                                    var_".$this->mId.".fnUpdate(ptitle, rowId, 1, false);
                                    var_".$this->mId.".fnUpdate(pkeywords, rowId, 2, false);
                                    var_".$this->mId.".fnUpdate(ppages, rowId, 3, false);
                                    var_".$this->mId.".fnUpdate(pyear, rowId, 4, false);
                                    var_".$this->mId.".fnUpdate(ptype, rowId, 5, false);
                                }
                                alert(rdata.msg);
                            }, 'json');
                         return false;
                    });

                    //dlgApplication
                    $('#'+sApplicationBtn).click(function(){
                        var sDlgApplication=\"#".$this->mId."_dlgApplication\";

                        $('input[name=\"".$this->mId."_dlgApplication_title\"]').val(data.title);
                        $('input[name=\"".$this->mId."_dlgApplication_article_id\"]').val(hiddenData);

                        $.post('get_categories.php?category_type=application', {id: hiddenData},
                        function(data){
                            $('#".$this->mId."_list_applications').html(data);
                        }, 'html');

                        $.post('get_article_categories.php?category_type=application', {id: hiddenData},
                        function(data){
                            $('#".$this->mId."_list_article_applications').html(data);
                        }, 'html');

                        if($(sDlgApplication).dialog('isOpen'))
                        {
                            $(sDlgApplication).dialog('moveToTop');
                        }
                        else
                        {
                            $(sDlgApplication).dialog('open');
                        }
                        return false;
                    });

                    //dlgDesign
                    $('#'+sDesignBtn).click(function(){
                        var sDlgDesign=\"#".$this->mId."_dlgDesign\";

                        $('input[name=\"".$this->mId."_dlgDesign_title\"]').val(data.title);
                        $('input[name=\"".$this->mId."_dlgDesign_article_id\"]').val(hiddenData);

                        $.post('get_categories.php?category_type=design', {id: hiddenData},
                        function(data){
                            $('#".$this->mId."_list_designs').html(data);
                        }, 'html');

                        $.post('get_article_categories.php?category_type=design', {id: hiddenData},
                        function(data){
                            $('#".$this->mId."_list_article_designs').html(data);
                        }, 'html');

                        if($(sDlgDesign).dialog('isOpen'))
                        {
                            $(sDlgDesign).dialog('moveToTop');
                        }
                        else
                        {
                            $(sDlgDesign).dialog('open');
                        }
                        return false;
                    });

                    //dlgMemetic
                    $('#'+sMemeticBtn).click(function(){
                        var sDlgMemetic=\"#".$this->mId."_dlgMemetic\";

                        $('input[name=\"".$this->mId."_dlgMemetic_title\"]').val(data.title);
                        $('input[name=\"".$this->mId."_dlgMemetic_article_id\"]').val(hiddenData);

                        $.post('get_categories.php?category_type=memetic', {id: hiddenData},
                        function(data){
                            $('#".$this->mId."_list_memetics').html(data);
                        }, 'html');

                        $.post('get_article_categories.php?category_type=memetic', {id: hiddenData},
                        function(data){
                            $('#".$this->mId."_list_article_memetics').html(data);
                        }, 'html');

                        if($(sDlgMemetic).dialog('isOpen'))
                        {
                            $(sDlgMemetic).dialog('moveToTop');
                        }
                        else
                        {
                            $(sDlgMemetic).dialog('open');
                        }
                        return false;
                    });

                    //dlgAuthor
                    $('#'+sAuthorBtn).click(function(){
                        var sDlgAuthor=\"#".$this->mId."_dlgAuthor\";

                        $('input[name=\"".$this->mId."_dlgAuthor_title\"]').val(data.title);
                        $('input[name=\"".$this->mId."_dlgAuthor_article_id\"]').val(hiddenData);

                        $.post('get_categories.php?category_type=author', {id: hiddenData},
                        function(data){
                            $('#".$this->mId."_list_authors').html(data);
                        }, 'html');

                        $.post('get_article_categories.php?category_type=author', {id: hiddenData},
                        function(data){
                            $('#".$this->mId."_list_article_authors').html(data);
                        }, 'html');

                        if($(sDlgAuthor).dialog('isOpen'))
                        {
                            $(sDlgAuthor).dialog('moveToTop');
                        }
                        else
                        {
                            $(sDlgAuthor).dialog('open');
                        }
                        return false;
                    });

                    //dlgJournal
                    $('#'+sJournalBtn).click(function(){
                        var sDlgJournal=\"#".$this->mId."_dlgJournal\";

                        $('input[name=\"".$this->mId."_dlgJournal_title\"]').val(data.title);
                        $('input[name=\"".$this->mId."_dlgJournal_article_id\"]').val(hiddenData);

                        $.post('get_categories.php?category_type=journal', {id: hiddenData},
                        function(data){
                            $('#".$this->mId."_list_journals').html(data);
                        }, 'html');

                        $.post('get_article_categories.php?category_type=journal', {id: hiddenData},
                        function(data){
                            $('#".$this->mId."_list_article_journals').html(data);
                        }, 'html');

                        if($(sDlgJournal).dialog('isOpen'))
                        {
                            $(sDlgJournal).dialog('moveToTop');
                        }
                        else
                        {
                            $(sDlgJournal).dialog('open');
                        }
                        return false;
                    });

                    //dlgDefinition
                    $('#'+sDefinitionBtn).click(function(){
                        var sDlgDefinition=\"#".$this->mId."_dlgDefinition\";

                        $('input[name=\"".$this->mId."_dlgDefinition_title\"]').val(data.title);
                        $('input[name=\"".$this->mId."_dlgDefinition_article_id\"]').val(hiddenData);

                        $.post('get_categories.php?category_type=definition', {id: hiddenData},
                        function(data){
                            $('#".$this->mId."_list_definitions').html(data);
                        }, 'html');

                        $.post('get_article_categories.php?category_type=definition', {id: hiddenData},
                        function(data){
                            $('#".$this->mId."_list_article_definitions').html(data);
                        }, 'html');

                        if($(sDlgDefinition).dialog('isOpen'))
                        {
                            $(sDlgDefinition).dialog('moveToTop');
                        }
                        else
                        {
                            $(sDlgDefinition).dialog('open');
                        }
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

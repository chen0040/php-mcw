<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ArticleViewer
 *
 * @author Xianshun
 */
 require_once('JQueryDataTable.php');
 
class ArticleViewer extends JQueryDataTable {
    //put your code here
    protected $mEditable;
    
    public function __construct($id)
    {
        parent::__construct($id);
        $this->setAjaxSource("json_table.php?id=mcarticles.id&fc=2&f0=mcarticles.title&f1=mcarticles.keywords&tc=1&t0=mcarticles");
        
        $this->addField("(+)", '{ "sClass": "center", "bSortable": false }');
        $this->addField("Title");
        $this->addField("Tag");
        //$this->addField("Pages");
        //$this->addField("Year");
        //$this->addField("Type");

        /*
        $this->addCSS("jquery-tooltip/css/global.css");
        $this->addCSS("jquery-tooltip/js/jtip.js");
         * 
         */

        $this->mEditable=false;
    }

    public function setEditable($editable)
    {
        $this->mEditable=$editable;
    }

    public function isEditable()
    {
        return $this->mEditable;
    }
    
    private function getComment()
    {
        if($this->hasLoginned())
        {
            $content="
                sTable += '<tr><td colspan=\"2\"><b>Comment</b></td></tr>';
                sTable += '<tr><td colspan=\"2\">'+data.comment+'</td></tr>';
            ";
            return $content;
        }
        return '';
    }

    public function getScript_onRowOpened()
    {
        $editLink='';

      
        $this->setTitle($this->getTitle()."<a href='mcw_edit_categories.php' target='_blank' ><img src='codezone/css/images/buttons/edit.gif' border='0' align='right' style='padding-right:10px' alt='Edit' title='Edit'/> </a>");
        $editLink.="<a href=\"mcw_edit_article.php?article_id='+hiddenData+'\" target=\"_blank\"><img src=\"codezone/css/images/buttons/edit.gif\" border=\"0\" align=\"right\" style=\"padding-right:10px\" alt=\"Edit\" title=\"Edit\" /></a>";


        $editLink.="<a href=\"mcw_bibtex_article.php?article_id='+hiddenData+'\" target=\"_blank\"><img src=\"codezone/css/images/icons/bibtex.png\" border=\"0\" align=\"right\" style=\"padding-right:10px\" alt=\"BibTex\" title=\"BibTex\" /></a>";
        
        $content="
        function " . $this->mId . "_onRowOpened(oData)
        {
            var hiddenData=oData[0];
            hiddenData=hiddenData.replace('<img src=\"dataTables/media/images/details_open.png\" /><!--', '');
            hiddenData=hiddenData.replace('-->', '');

            var sId='".$this->mId."_f'+hiddenData;
            var sOut='<div id='+sId+'>Loading...</div>';

            $.post('get_article.php', {id: hiddenData},
            function(data){
                var sTable = '<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\" style=\"padding:0px;\" >';
                sTable += '<tr><td colspan=\"2\"><b>'+data.title+'</b> ".$editLink."</td></tr>';
                sTable += '<tr><td colspan=\"2\"><b>Abstract</b></td></tr>';
                sTable += '<tr><td colspan=\"2\">'+data.abstract+'</td></tr>';
                ".
                $this->getComment()
                ."
                sTable += '<tr><td colspan=\"2\"><b>Issue:</b> '+data.loc+'</td></tr>';
                sTable += '<tr><td colspan=\"2\"><b>Year:</b> '+data.year+'</td></tr>';
                sTable += '<tr><td colspan=\"2\"><b>Type:</b> '+data.type+'</td></tr>';
                sTable += '<tr><td colspan=\"2\"><b>Pages:</b> '+data.pages+'</td></tr>';
                sTable += '<tr><td colspan=\"2\"><b>Tag:</b> '+data.keywords+'</td></tr>';
                sTable += '<tr><td><b>Authors:</b></td><td><b>Journal:</b></td></tr>';
                sTable += '<tr>';
                sTable += '<td valign=\"top\"><div id=\"".$this->mId."_f'+hiddenData+'_list_authors\" style=\"width:400px;\" /></td>';
                sTable += '<td valign=\"top\"><div id=\"".$this->mId."_f'+hiddenData+'_list_journals\" style=\"width:400px;\" /></td>';
                sTable += '</tr>';
                sTable += '<tr><td><b>Applications:</b></td><td><b>Design Issue:</b></td></tr>';
                sTable += '<tr>';
                sTable += '<td valign=\"top\"><div id=\"".$this->mId."_f'+hiddenData+'_list_applications\" size=\"3\" style=\"width:400px;\" /></td>';
                sTable += '<td valign=\"top\"><div id=\"".$this->mId."_f'+hiddenData+'_list_designs\" size=\"3\" style=\"width:400px;\" /></td>';
                sTable += '</tr>';
                sTable += '<tr><td><b>Memetic Issue:</b></td><td><b>Definitions:</b></td></tr>';
                sTable += '<td valign=\"top\"><div id=\"".$this->mId."_f'+hiddenData+'_list_memetics\" size=\"3\" style=\"width:400px;\" /></td>';
                sTable += '<td valign=\"top\"><div id=\"".$this->mId."_f'+hiddenData+'_list_definitions\" size=\"3\" style=\"width:400px;\" /></td>';
                sTable += '</tr>';
                sTable += '</table>';
                $('#'+sId).html(sTable);

                $.post('get_article_categories.php?category_type=application&html_type=link', {id: hiddenData},
                        function(data){
                            $('#".$this->mId."_f'+hiddenData+'_list_applications').html(data);
                        }, 'html');
                $.post('get_article_categories.php?category_type=design&html_type=link', {id: hiddenData},
                        function(data){
                            $('#".$this->mId."_f'+hiddenData+'_list_designs').html(data);
                        }, 'html');
                $.post('get_article_categories.php?category_type=memetic&html_type=link', {id: hiddenData},
                        function(data){
                            $('#".$this->mId."_f'+hiddenData+'_list_memetics').html(data);
                        }, 'html');
                $.post('get_article_categories.php?category_type=author&html_type=link', {id: hiddenData},
                        function(data){
                            $('#".$this->mId."_f'+hiddenData+'_list_authors').html(data);
                        }, 'html');
                $.post('get_article_categories.php?category_type=journal&html_type=link', {id: hiddenData},
                        function(data){
                            $('#".$this->mId."_f'+hiddenData+'_list_journals').html(data);
                        }, 'html');
                $.post('get_article_categories.php?category_type=definition&html_type=link', {id: hiddenData},
                        function(data){
                            $('#".$this->mId."_f'+hiddenData+'_list_definitions').html(data);
                        }, 'html');
            }, \"json\");

            return sOut;
        }
        ";
        return $content;
    }
}
?>

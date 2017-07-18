
<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ArticleAdvSearcher
 *
 * @author Xianshun
 */
require_once("ArticleViewer.php");
require_once("MySQLHandler.php");

class ArticleAdvSearcher extends ArticleViewer{
    //put your code here
    public function __construct($id)
    {
        parent::__construct($id);

        $this->setTitle("Search Results");

        $ajax_source='json_table.php?id=mcarticles.id&fc=2&f0=mcarticles.title&f1=mcarticles.keywords';
       
        $tables=Array();

        $filter_types=Array();
        $filter_types[]="application";
        $filter_types[]="memetic";
        $filter_types[]="design";
        $filter_types[]="journal";
        $filter_types[]="author";
        $joins=Array();

        $tables["mcarticles"]=Array();
        $article_filter_types=Array();
        $article_filter_types[]="year";
        $article_filter_types[]="type";
        foreach($article_filter_types as $article_filter_type)
        {
            $fc_name=$this->mId.'_'.$article_filter_type.'_filter';
            if(isset($_POST[$fc_name]))
            {
                $fp=$_POST[$fc_name];
                $fpc=count($fp);
                for($i=0; $i < $fpc; ++$i)
                {
                    $tables["mcarticles"][$article_filter_type][$i]=$fp[$i];
                }
            }
        }
        
        foreach($filter_types as $filter_type)
        {
            $fc_name=$this->mId.'_'.$filter_type.'_filter';
            if(isset($_POST[$fc_name]))
            {
                $fp=$_POST[$fc_name];
                $fpc=count($fp);
                if($fpc > 0)
                {
                    $table_name='mc'.$filter_type.'s';
                    $param_name=$filter_type.'id';
                    
                    
                    
                    $value_index=0;
                    for($i=0; $i<$fpc; ++$i)
                    {
                        if($fp[$i] != 0)
                        {
                            if(!isset($tables[$table_name]))
                            {
                                $tables[$table_name]=Array();
                            }
                            
                            if(!isset($tables[$table_name][$param_name]))
                            {
                                $tables[$table_name][$param_name]=Array();
                            }
                            
                            $tables[$table_name][$param_name][$value_index]=$fp[$i];
                            $value_index++;
                        }
                    }
                    
                    if($value_index!=0)
                    {
                        $joins[]="mcarticles.id[".$table_name.".articleid";
                    }
                }
            }
        }

        if(count($tables)>0)
        {
            $ajax_source.=('&tc='.count($tables));
            $table_index=0;
            foreach($tables as $table_name => $params)
            {
                $ajax_source.=('&t'.$table_index.'='.$table_name);
                $tc=count($params);
                if($tc > 0)
                {
                    $ajax_source.=('&t'.$table_index.'c='.$tc);
                    $param_index=0;
                    foreach($params as $param_name => $pvalues)
                    {
                        $ajax_source.=('&t'.$table_index.'p'.$param_index.'='.$param_name);

                        $vc=count($pvalues);
                        $ajax_source.=('&t'.$table_index.'p'.$param_index.'c='.$vc);
                        for($value_index=0; $value_index < $vc; ++$value_index)
                        {
                            $ajax_source.=('&t'.$table_index.'p'.$param_index.'v'.$value_index.'='.$pvalues[$value_index]);
                        }
                        $param_index++;
                    }
                }
                $table_index++;
            }

            $jc=count($joins);
            if($jc>0)
            {
                $ajax_source.=('&jc='.$jc);
                for($i=0; $i < $jc; ++$i)
                {
                    $ajax_source.=('&j'.$i.'='.$joins[$i]);
                }
            }
        }

        //echo $ajax_source;
        $this->setAjaxSource($ajax_source);
    }

    private function render_searcher_section($data_source)
    {
        $content='<select id="'.$this->mId.'_'.$data_source.'_filter" name="'.$this->mId.'_'.$data_source.'_filter[]" multiple size="10" style="width:100%"></select>';
        echo $content;
    }

    public function render_header()
    {
        parent::render_header();
        $this->render_filter_selected('application');
        $this->render_filter_selected('design');
        $this->render_filter_selected('memetic');
        $this->render_filter_selected('year');
        $this->render_filter_selected('type');
        $this->render_filter_selected('author');
        $this->render_filter_selected('journal');
        
        echo '$(function(){
            $.post("get_categories.php?category_type=application", {},
                        function(data){
                            $("#'.$this->mId.'_application_filter").html(data);
                            '.$this->mId.'_application_on_selected();
                        }, "html");
            $.post("get_categories.php?category_type=design", {},
                        function(data){
                            $("#'.$this->mId.'_design_filter").html(data);
                            '.$this->mId.'_design_on_selected();
                        }, "html");
            $.post("get_categories.php?category_type=memetic", {},
                        function(data){
                            $("#'.$this->mId.'_memetic_filter").html(data);
                            '.$this->mId.'_memetic_on_selected();
                        }, "html");
            $.post("get_categories.php?category_type=author", {},
                        function(data){
                            $("#'.$this->mId.'_author_filter").html(data);
                            '.$this->mId.'_author_on_selected();
                        }, "html");
            $.post("get_categories.php?category_type=journal", {},
                        function(data){
                            $("#'.$this->mId.'_journal_filter").html(data);
                            '.$this->mId.'_journal_on_selected();
                        }, "html");
            $.post("get_years.php", {},
                        function(data){
                            $("#'.$this->mId.'_year_filter").html(data);
                            '.$this->mId.'_year_on_selected();
                        }, "html");
            $.post("get_types.php", {},
                        function(data){
                            $("#'.$this->mId.'_type_filter").html(data);
                            '.$this->mId.'_type_on_selected();
                        }, "html");
        });';
    }

    private function render_filter_selected($data_source)
    {
        echo 'function '.$this->mId.'_'.$data_source.'_on_selected(){';
        if(isset($_POST[$this->mId.'_'.$data_source.'_filter']))
        {
            $application_filter=$_POST[$this->mId.'_'.$data_source.'_filter'];
            foreach($application_filter as $item)
            {
                echo '$("#'.$this->mId.'_'.$data_source.'_filter option[value='.$item.']").attr("selected", true);';
            }
        }
        echo '}';
    }

   

    public function render()
    {
        echo '<form id="'.$this->mId.'_form" method="POST" action="'.$_SERVER['PHP_SELF'].'">';
        
        echo '<table bgcolor="white" border="0" cellpadding="0" cellspacing="0" width="100%">';
        echo '<tr><td colspan="5"><img src="codezone/css/images/buttons/buttonfind.gif" align="middle" /> Select Search Criteria and press Search button <input type="submit" name="btnSearch" value="Search" class="ui-state-default ui-corner-all" style="padding-top:4px;padding-bottom:4px;cursor:pointer" /></td></tr>';
        echo '<tr>';
        echo '<td colspan="2" class="ui-state-default ui-corner-all"><img src="codezone/css/images/Automator-icon.png" /> Application</td>';
        echo '<td colspan="3" class="ui-state-default ui-corner-all"><img src="codezone/css/images/Automator-icon.png" /> Design Issue</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td width="50%" colspan="2">';
        $this->render_searcher_section('application');
        echo '</td>';
        echo '<td colspan="3">';
        $this->render_searcher_section('design');
        echo '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td class="ui-state-default ui-corner-all"><img src="codezone/css/images/Automator-icon.png" /> Memetic Issue</td>';
        echo '<td class="ui-state-default ui-corner-all"><img src="codezone/css/images/Automator-icon.png" /> Journal</td>';
        echo '<td class="ui-state-default ui-corner-all"><img src="codezone/css/images/Automator-icon.png" /> Year</td>';
        echo '<td class="ui-state-default ui-corner-all"><img src="codezone/css/images/Automator-icon.png" /> Type</td>';
        echo '<td class="ui-state-default ui-corner-all"><img src="codezone/css/images/Automator-icon.png" /> Author</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>';
        $this->render_searcher_section('memetic');
        echo '</td>';
        echo '<td>';
        $this->render_searcher_section('journal');
        echo '</td>';
        echo '<td width="17%">';
        $this->render_searcher_section('year');
        echo '</td>';
        echo '<td width="17%">';
        $this->render_searcher_section('type');
        echo '</td>';
        echo '<td>';
        $this->render_searcher_section('author');
        echo '</td>';
        echo '</tr>';
        echo '<tr><td colspan="5">';
        parent::render();
        echo '</td></tr>';
        echo '</table>';
        echo '</form>';
    }

    
}
?>

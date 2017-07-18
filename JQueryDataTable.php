<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JQueryDataTable
 *
 * @author Xianshun
 */
 require_once("MObj.php");
 
class JQueryDataTable extends MObj{
    //put your code here
    private $mFields;
    private $mAjaxSource;
    private $mRecordType;
    private $mTitle;
    
    public function __construct($id)
    {
        parent::__construct($id);
        $this->mRecordType="item";

        //add dataTable css
        //$this->addCSS("dataTables/media/css/demo_page.css");
        $this->addCSS("codezone/css/demo_table.css");

        //add dataTable script
        $this->addScript("dataTables/media/js/jquery.dataTables.min.js");
		
		//for rounded corner
		$this->addScript("jquery/jquery.corner.js");

        $this->mTitle='';
    }

    public function setTitle($title)
    {
        $this->mTitle=$title;
    }

    public function getTitle()
    {
        return $this->mTitle;
    }

    public function getScript_onRowDblClicked()
    {
        $content="
        function " . $this->mId . "_onRowDblClicked(oData, iRow)
        {
            var hiddenData=oData[0];
            hiddenData=hiddenData.replace('<img src=\"dataTables/media/images/details_open.png\"><!--', '');
            hiddenData=hiddenData.replace('-->', '');

            //alert(hiddenData);
        }
        ";
        return $content;
    }

    public function getScript_onRowClicked()
    {
        $content="
        function " . $this->mId . "_onRowClicked(oData, iRow)
        {
            
        }
        ";
        return $content;
    }

    public function getScript_onRowOpened()
    {
        $content="
        function " . $this->mId . "_onRowOpened(oData, iRow)
        {
            var hiddenData=oData[0];
            hiddenData=hiddenData.replace('<img src=\"dataTables/media/images/details_open.png\" /><!--', '');
            hiddenData=hiddenData.replace('-->', '');
            var sOut = '<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\" style=\"padding-left:50px;\">';
            //sOut += '<tr><td>'+hiddenData+'</td></tr>';
            sOut += '<tr><td>Abstract to come later!</td></tr>';

            sOut += '</table>';

            return sOut;
        }
        ";
        return $content;
    }

    public function addField($field, $format="null")
    {
        $this->mFields[$field]=$format;
    }

    public function getField($index)
    {
        $this->mFields[$index];
    }

    public function getFieldCount()
    {
        return count($this->mFields);
    }

    public function render_title()
    {
        if(strcmp($this->mTitle, '')!=0)
        {
			//class="ui-state-default ui-corner-all"
            echo '<div  style="padding-top:5px;padding-bottom:5px;padding-left:5px;background: url(codezone/css/images/top_bar.png);" id="'.$this->mId.'_title"><img src="codezone/css/images/Automator-icon.png" border="0" align="absmiddle" /> '.$this->mTitle.'</div>';  //#6af
        }
    }

    public function render_table()
    {
        $content='';

		$format='border-left:1px solid silver;border-right:1px solid silver';
        $content.='<table id="' . $this->mId . '"  class="display" cellpadding="0" cellspacing="0" border="0" width="100%" style="'.$format.'">';
        $content=$content . '<thead>';
        $content=$content . '<tr>';
        foreach($this->mFields as $key => $value)
        {
            $content=$content . '<th>' . $key . '</th>' . $this->linefeed();
        }
        $content=$content . '</tr>';
        $content=$content . '</thead>';
        $content=$content . '<tbody>';
        $content=$content . '<tr class="gradeA">';
        $content=$content . '<td>Loading in progress...</td>';
        $content=$content . '</tr>';
        $content=$content . '</tbody>';
        $content=$content . '</table>';

        echo $content;
    }
    
    public function render()
    {
        $this->render_title();
        $this->render_table();
    }

    public function setAjaxSource($src)
    {
        $this->mAjaxSource=$src;
    }

    public function setRecordType($val)
    {
        $this->mRecordType=$val;
    }

    public function render_header()
    {
		if(strcmp($this->mTitle, '')!=0)
        {
			 echo '$(function(){
				$("#'.$this->mId.'_title").corner("bevel top");
			});';
		}
		
        $aoColumns="";
        $count=count($this->mFields);
        $index=0;
        foreach($this->mFields as $key => $format)
        {
            $aoColumns=$aoColumns . $format;
            $index=$index + 1;
            if($index != $count)
            {
                $aoColumns = $aoColumns . ", ";
            }
        }
        
        $content="
        var var_" . $this->mId . "=null;
        ". $this->getScript_onRowDblClicked() . "
        ". $this->getScript_onRowClicked() . "
        ". $this->getScript_onRowOpened() . "
        function " . $this->mId . "_fnFormatDetails (nTr)
        {
            var iIndex = var_" . $this->mId . ".fnGetPosition(nTr);
            var aData = var_" . $this->mId . ".fnSettings().aoData[iIndex]._aData;

            return " . $this->mId. "_onRowOpened(aData, iIndex);
        }

        /* Event handler function */
        function " . $this->mId . "_fnOpenClose(oSettings)
        {
            $('td img', var_" . $this->mId . ".fnGetNodes() ).each( function () {
                $(this).click( function () {
                    var nTr = this.parentNode.parentNode;
                    if ( this.src.match('details_close') )
                    {
                        /* This row is already open - close it */
                        this.src = \"dataTables/media/images/details_open.png\";
                        /* fnClose doesn't do anything for server-side processing - do it ourselves :-) */
                        var nRemove = $(nTr).next()[0];
                        nRemove.parentNode.removeChild( nRemove );
                    }
                    else
                    {
                        /* Open this row */
                        this.src = \"dataTables/media/images/details_close.png\";
                        var_" . $this->mId . ".fnOpen( nTr, " . $this->mId . "_fnFormatDetails(nTr), 'details' );
                    }
                } );
            } );
        }
        function " . $this->mId . "_fnGetSelected( oTableLocal )
        {
            var aReturn = new Array();
            var aTrs = oTableLocal.fnGetNodes();
        	
            for ( var i=0 ; i<aTrs.length ; i++ )
            {
	            if ( $(aTrs[i]).hasClass('row_selected') )
	            {
		            aReturn.push(aTrs[i]);
	            }
            }
           
            return aReturn;
        }
        $(document).ready(function(){
                var_" . $this->mId . "=$('#" . $this->mId . "').dataTable({
                'bProcessing': true,
                'bServerSide': true,
                'sAjaxSource': '" . $this->mAjaxSource . "',
                'bJQueryUI': true,
                'bPaginate': true,
                'sPaginationType': 'full_numbers',
                'iDisplayLength' : 10,
                'bAutoWidth': false,
                'oLanguage': {
                                'sLengthMenu': 'Display _MENU_ " . $this->mRecordType . "s per page',
                                'sZeroRecords': 'No " . $this->mRecordType . " found - sorry',
                                'sInfo': 'Showing _START_ to _END_ of _TOTAL_ " . $this->mRecordType . "s',
                                'sInfoEmtpy': 'Showing 0 to 0 of 0 " . $this->mRecordType . "s',
                                'sInfoFiltered': '(filtered from _MAX_ total " . $this->mRecordType . "s)'
                        },
                'aoColumns': [" . $aoColumns . "],
                'aaSorting': [[1, 'asc']],
                'fnDrawCallback': " . $this->mId . "_fnOpenClose
            });

            $('#" . $this->mId . " tbody').click(function(event) {
                $(var_" . $this->mId . ".fnSettings().aoData).each(function (){
                        $(this.nTr).removeClass('row_selected');
                });
                $(event.target.parentNode).addClass('row_selected');
                var anSelected = " . $this->mId . "_fnGetSelected(var_" . $this->mId . ");
                if(anSelected.length > 0)
	        {
                    var iRow = var_" . $this->mId . ".fnGetPosition( anSelected[0] );
                    oData= var_" . $this->mId . ".fnGetData(iRow);
                    " . $this->mId . "_onRowClicked(oData, iRow);
                }
            });

            $('#" . $this->mId . " tbody').dblclick(function(){
                var anSelected = " . $this->mId . "_fnGetSelected(var_" . $this->mId . ");
                if(anSelected.length>0)
                {
                    var iRow = var_" . $this->mId . ".fnGetPosition( anSelected[0] );
                    var oData=var_" . $this->mId . ".fnGetData(iRow);

                    " . $this->mId . "_onRowDblClicked(oData, iRow);
                }
            });
        });
        ";
        echo $content;
    }
}
?>

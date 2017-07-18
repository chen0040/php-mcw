<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GetPapers
 *
 * @author Xianshun
 */
session_start();
require_once("MySQLHandler.php");

//$_GET['sSearch']='fast';
//$_GET['iDisplayStart']='1';
//$_GET['iDisplayLength']='10';


class JSONLoader
{
    private $mQueryParams;
    private $mEcho;
    private $mJoins;
    private $mLimit;
    private $mFields;
    private $mTables;
    private $mOrder;
    private $mId;
    
    public function __construct()
    {
        $this->mQueryParams=Array();
        $this->mTables=Array();
        $this->mFields=Array();
        $this->mJoins=Array();

        $this->mEcho="";
        if(isset($_GET['sEcho']))
        {
            $this->mEcho=$_GET['sEcho'];
        }

        $handler=new MySQLHandler();
        $handler->connect();
        $handler->select_database();

        if(isset($_GET['id']))
        {
            $this->mId=$_GET['id'];
        }

        /* fields to retrieve*/
        if(isset($_GET['fc']))
        {
            $fc=$_GET['fc'];
            if($fc !=0)
            {
                for($i=0; $i < $fc; ++$i)
                {
                    if(isset($_GET['f'.$i]))
                    {
                        $this->mFields[]=($_GET['f'.$i]);
                    }
                }
            }
        }

         /* search */
        $sSearch='';
        if(isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
        {
                $sSearch = "(";
                $fc=count($this->mFields);
                for($i=0; $i<$fc; ++$i)
                {
                    if($i != 0 && $fc != 1)
                    {
                        $sSearch.=" OR ";
                    }
                    $sSearch.=($this->mFields[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%'");
                }
                $sSearch.=")";
        }
        if(strcmp($sSearch, '')!=0)
        {
            $this->mJoins[]=$sSearch;
        }
        
        /* join table */
        if(isset($_GET['jc']))
        {
            $jc=$_GET['jc'];
            for($i=0; $i<$jc; ++$i)
            {
                if(isset($_GET['j'.$i]))
                {
                    $join=$_GET['j'.$i];
                    $join=str_replace("[", "=", $join);
                    //echo $join;
                    $this->mJoins[]=$join;
                }
            }
        }

        if(isset($_GET['tc']))
        {
            $tc=$_GET['tc'];
            for($i=0; $i<$tc; ++$i)
            {
                if(isset($_GET['t'.$i]))
                {
                    $table_name=$_GET['t'.$i];
                    $this->mTables[$table_name]=Array();
                    if(isset($_GET['t'.$i.'c']))
                    {
                        $tic=$_GET['t'.$i.'c'];
                        if($tic > 0)
                        {
                            for($j=0; $j<$tic; ++$j)
                            {
                                if(isset($_GET['t'.$i.'p'.$j]))
                                {
                                    $tipj=$_GET['t'.$i.'p'.$j];
                                    if(isset($_GET['t'.$i.'p'.$j.'c']))
                                    {
                                        $tipjc=$_GET['t'.$i.'p'.$j.'c'];
                                        if($tipjc > 0)
                                        {
                                            $this->mTables[$table_name][$tipj]=Array();
                                            for($k=0; $k < $tipjc; ++$k)
                                            {
                                                if(isset($_GET['t'.$i.'p'.$j.'v'.$k]))
                                                {
                                                    $tipjvk=$_GET['t'.$i.'p'.$j.'v'.$k];
                                                    $this->mTables[$table_name][$tipj][$k]=$tipjvk;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        /* Paging */
        $this->mLimit = "";
        if (isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1')
        {
                $this->mLimit = "LIMIT ".mysql_real_escape_string($_GET['iDisplayStart']).", ".
                        mysql_real_escape_string($_GET['iDisplayLength']);
        }

        /* Ordering */
        $this->mOrder = "";
        if ( isset( $_GET['iSortCol_0'] ) )
        {
                $this->mOrder = "ORDER BY  ";
                for ( $i=0 ; $i<mysql_real_escape_string( $_GET['iSortingCols'] ) ; $i++ )
                {
                        $this->mOrder .= $this->fnColumnToField(mysql_real_escape_string( $_GET['iSortCol_'.$i] ))."
                                ".mysql_real_escape_string( $_GET['iSortDir_'.$i] ) .", ";
                }
                $this->mOrder = substr_replace( $this->mOrder, "", -2 );
        }

        $handler->disconnect();
    }

    public function render()
    {
        $handler=new MySQLHandler();
        $handler->connect();
        $handler->select_database();

        $sOrder=$this->mOrder;
        $sLimit=$this->mLimit;

        $sFrom="FROM ";
        $table_index=0;
        foreach($this->mTables as $table_name => $params)
        {
            if($table_index != 0)
            {
                $sFrom.=', ';
            }
            $sFrom.=$table_name;

            if(count($params) > 0)
            {
                $param_index=0;
                
                foreach($params as $param_name => $param_values)
                {
                    $table_param_name=$table_name.'.'.$param_name;
                    
                    if(count($param_values) > 0)
                    {
                        $value_index=0;
                        $join='(';
                        foreach($param_values as $param_value)
                        {
                            if($value_index!=0)
                            {
                                $join.=' OR ';
                            }
                            $join.=($table_param_name.'="'.$param_value.'"');
                            $value_index++;
                        }
                        $join.=')';
                        $this->mJoins[]=$join;
                    }

                    $param_index++;
                }
            }
            
            $table_index++;
        }

        $jc=count($this->mJoins);
        $sWhere="";
        if($jc != 0)
        {
            $sWhere="WHERE ";
            for($i=0; $i < $jc; ++$i)
            {
                if($i != 0)
                {
                    $sWhere.=" AND ";
                }

                $sWhere.=$this->mJoins[$i];
            }
        }

        $sQuery = "SELECT DISTINCT ".$this->mId;
        $fc=count($this->mFields);
        for($i=0; $i < $fc; ++$i)
        {
            $sQuery.=', ';
            $sQuery.=($this->mFields[$i]);
        }
        $sQuery.=(" ".$sFrom." ".$sWhere." ".$sOrder." ".$sLimit);

        //echo $sQuery;

        $rResult=mysql_query($sQuery) or die(mysql_error());


        $sQuery = "SELECT COUNT(DISTINCT ".$this->mId.") ".$sFrom." ".$sWhere;
        $rResultFilterTotal = mysql_query($sQuery) or die(mysql_error());
        $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
        $iTotalDisplayRecords = $aResultFilterTotal[0];

        $sQuery = "SELECT COUNT(DISTINCT ".$this->mId.") ".$sFrom." ".$sWhere;
        $rResultTotal = mysql_query($sQuery) or die(mysql_error());
        $aResultTotal = mysql_fetch_array($rResultTotal);
        $iTotalRecords = $aResultTotal[0];

        $result = '{';
        $result .= '"sEcho": '. $this->mEcho .', ';
        $result .= '"iTotalRecords": '.$iTotalRecords.', ';
        $result .= '"iTotalDisplayRecords": '.$iTotalDisplayRecords.', ';
        $result .= '"aaData": [ ';

        $id=$this->mId;
        $id=substr($id, strpos($id, '.')+1);
        while ( $aRow = mysql_fetch_array($rResult))
        {
                $result .= "[";
                $result .= '"<img src=\"dataTables/media/images/details_open.png\" /><!--' . addslashes($aRow[$id]) . '-->"';
                for($i=0; $i<$fc; ++$i)
                {
                    
                    $fielditem=$this->mFields[$i];
                    $fielditem=substr($fielditem, strpos($fielditem, '.')+1);
                   
                    $result.=', ';
                    $result .= '"'.addslashes($aRow[$fielditem]).'"';
                }
                $result .= "],";
        }
        $result = substr_replace( $result, "", -1 );
        $result .= '] }';

        $handler->disconnect();

        echo $result;
    }

    private function fnColumnToField($i)
    {
         /* Note that column 0 is the details column */
        $fc=count($this->mFields);

        for($j=0; $j<$fc; ++$j)
        {
            if ( $i == 0 ||$i == 1 )
                return $this->mFields[0];
            else if ($i == $j+1)
                return $this->mFields[$j];
        }
    }
}

$p=new JSONLoader();

$p->render();
?>

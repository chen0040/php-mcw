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

function fnColumnToField( $i )
{
        /* Note that column 0 is the details column */
        if ( $i == 0 ||$i == 1 )
                return "muser";
        else if ( $i == 2 )
                return "mpwd";
        else if ( $i == 3 )
                return "mrole";
}

$sEcho="";
if(isset($_GET['sEcho']))
{
    $sEcho=$_GET['sEcho'];
}

$handler=new MySQLHandler();
$handler->connect();
$handler->select_database();

/* Filtering */
$sWhere = "";
if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
{
        $sWhere = "WHERE (muser LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ".
                        "mpwd LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ".
                        "mrole LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%')";
}

/* Paging */
$sLimit = "";
if (isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1')
{
        $sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
                mysql_real_escape_string( $_GET['iDisplayLength'] );
}

/* Ordering */
$sOrder = "";
if ( isset( $_GET['iSortCol_0'] ) )
{
        $sOrder = "ORDER BY  ";
        for ( $i=0 ; $i<mysql_real_escape_string( $_GET['iSortingCols'] ) ; $i++ )
        {
                $sOrder .= fnColumnToField(mysql_real_escape_string( $_GET['iSortCol_'.$i] ))."
                        ".mysql_real_escape_string( $_GET['iSortDir_'.$i] ) .", ";
        }
        $sOrder = substr_replace( $sOrder, "", -2 );
}

$sQuery = "
        SELECT * FROM mcmembership
        $sWhere
        $sOrder
        $sLimit
";
$rResult=mysql_query($sQuery) or die(mysql_error());

$sQuery = "
        SELECT COUNT(id) FROM mcmembership
        $sWhere
";
$rResultFilterTotal = mysql_query($sQuery) or die(mysql_error());
$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
$iTotalDisplayRecords = $aResultFilterTotal[0];

$sQuery = "
        SELECT COUNT(id)
        FROM   mcmembership
        $sWhere
";
$rResultTotal = mysql_query($sQuery) or die(mysql_error());
$aResultTotal = mysql_fetch_array($rResultTotal);
$iTotalRecords = $aResultTotal[0];

$result = '{';
$result .= '"sEcho": '. $sEcho .', ';
$result .= '"iTotalRecords": '.$iTotalRecords.', ';
$result .= '"iTotalDisplayRecords": '.$iTotalDisplayRecords.', ';
$result .= '"aaData": [ ';

while ( $aRow = mysql_fetch_array($rResult))
{
        $result .= "[";
        $result .= '"<img src=\"dataTables/media/images/details_open.png\" /><!--' . addslashes($aRow['id']) . '-->",';
        $result .= '"'.addslashes($aRow['muser']).'",';
        $result .= '"'.addslashes($aRow['mpwd']).'",';
        $result .= '"'.addslashes($aRow['mrole']).'"';
        $result .= "],";
}
$result = substr_replace( $result, "", -1 );
$result .= '] }';

$handler->disconnect();

echo $result;
?>

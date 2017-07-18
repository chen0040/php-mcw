<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PublisherPanel
 *
 * @author Xianshun
 */
require_once('MObj.php');

class PublisherPanel extends MObj{
    //put your code here
    public function __construct($id)
    {
        parent::__construct($id);
    }

    public function render_header()
    {
        if(isset($_POST['btnSearch']))
        {
            $handler=new MySQLHandler();
            $executed=$handler->connect();
            $handler->select_database();
            if($handler->table_exists("mccommand"))
            {
                //check whether table exists

                $sWhere='';
                if(isset($_POST['mcdate']) || isset($_POST['mcsender']) || isset($_POST['mcaction']) || isset($_POST['mccomm']))
                {
                    if(isset($_POST['mcaction']) && count($_POST['mcaction']) > 0)
                    {
                        $mcaction=$_POST['mcaction'];
                        $text='';
                        for($i=0; $i<count($mcaction); ++$i)
                        {
                            if($i !=  0)
                            {
                                $text.=" OR ";
                            }
                            $text.=('mcaction="'.$mcaction[$i].'"');
                        }
                        $sWhere.=('('.$text.')');
                    }
                    if(isset($_POST['mcdate']) && count($_POST['mcdate']) > 0)
                    {
                        $mcdate=$_POST['mcdate'];
                        $text='';
                        for($i=0; $i<count($mcdate); ++$i)
                        {
                            if($i !=  0)
                            {
                                $text.=" OR ";
                            }
                            $text.=('DATE(mcdate)="'.$mcdate[$i].'"');
                        }
                        if(strcmp($sWhere, '') != 0)
                        {
                            $sWhere.=' AND ';
                        }
                        $sWhere.=('('.$text.')');
                    }
                    if(MObj::hasRole("admin") && isset($_POST['mcsender']) && count($_POST['mcsender']) > 0)
                    {
                        $mcsender=$_POST['mcsender'];
                        $text='';
                        for($i=0; $i<count($mcsender); ++$i)
                        {
                            if($i !=  0)
                            {
                                $text.=" OR ";
                            }
                            $text.=('mcsender="'.$mcsender[$i].'"');
                        }
                        if(strcmp($sWhere, '') != 0)
                        {
                            $sWhere.=' AND ';
                        }
                        $sWhere.=('('.$text.')');
                    }
                    if(isset($_POST['mccomm']))
                    {
                        if(count($_POST['mccomm']) > 0)
                        {
                            $mccommited=$_POST['mccomm'];
                            $text='';
                            for($i=0; $i<count($mccommited); ++$i)
                            {
                                if($i !=  0)
                                {
                                    $text.=" OR ";
                                }
                                $text.=('mccommited="'.$mccommited[$i].'"');
                            }
                            if(strcmp($sWhere, '') != 0)
                            {
                                $sWhere.=' AND ';
                            }
                            $sWhere.=('('.$text.')');
                        }
                     }
                }
                
                if(MObj::hasLoginned())
                {
                    if(!MObj::hasRole("admin"))
                    {
                        $user=MObj::getUser();
                        if(strcmp($sWhere, '')!=0)
                        {
                            $sWhere.=' AND ';
                        }
                        $sWhere.='mcsender="'.$user.'"';
                    }
                }
                else
                {
                    $user=$_SERVER['REMOTE_ADDR'];
                    if(strcmp($sWhere, '')!=0)
                    {
                        $sWhere.=' AND ';
                    }
                    $sWhere.='mcsender="'.$user.'"';
                }
                if(strcmp($sWhere, '') != 0)
                {
                    $sWhere=' WHERE '.$sWhere;
                }

                $sQuery='SELECT DISTINCT id, mcsender, mcaction, mcdetail, mcdate, mccommited FROM mccommand'.$sWhere.' ORDER BY mcdate ASC';
                //echo $sQuery;
                $results=mysql_query($sQuery) or die(mysql_error());

                echo '$(function(){';
                while($row=mysql_fetch_array($results))
                {
                    if(MObj::hasRole("admin"))
                    {
                        echo '$("#exec_'.$row['id'].'").click(function(){
                                $.post("exec.php?", {id: '.$row['id'].'},
                                function(data){
                                    if(data.msg=="executed")
                                    {
                                        $("#exec_'.$row['id'].'").attr("disabled", "true");
                                        $("#div_'.$row['id'].'").css("color", "silver");
                                    }
                                    alert(data.msg);
                                }, "json");
                                return false;
                            });';
                    }
                    echo '$("#del_'.$row['id'].'").click(function(){
                            if(confirm("Delete action is not reversible. \nDo you want to delete this record?"))
                            {
                                $.post("del_exec.php?", {id: '.$row['id'].'},
                                function(data){
                                    if(data.msg=="executed")
                                    {
                                        $("#div_'.$row['id'].'").html("");
                                    }
                                    alert(data.msg);
                                }, "json");

                            }
                            return false;
                        });
                    ';
                }
                echo '});';

            }

            if($executed)
            {
                $handler->disconnect();
            }
        }
    }

    public function render()
    {
        echo '<form method="post" action="'.$_SERVER['REQUEST_URI'].'">';
        $this->render_search_form();
        if(isset($_POST['btnSearch']))
        {
            $handler=new MySQLHandler();
            $executed=$handler->connect();
            $handler->select_database();
            if($handler->table_exists("mccommand"))
            {
                //check whether table exists

                $sWhere='';
                if(isset($_POST['mcdate']) || isset($_POST['mcsender']) || isset($_POST['mcaction']) || isset($_POST['mccomm']))
                {
                    if(isset($_POST['mcaction']) && count($_POST['mcaction']) > 0)
                    {
                        $mcaction=$_POST['mcaction'];
                        $text='';
                        for($i=0; $i<count($mcaction); ++$i)
                        {
                            if($i !=  0)
                            {
                                $text.=" OR ";
                            }
                            $text.=('mcaction="'.$mcaction[$i].'"');
                        }
                        $sWhere.=('('.$text.')');
                    }
                    if(isset($_POST['mcdate']) && count($_POST['mcdate']) > 0)
                    {
                        $mcdate=$_POST['mcdate'];
                        $text='';
                        for($i=0; $i<count($mcdate); ++$i)
                        {
                            if($i !=  0)
                            {
                                $text.=" OR ";
                            }
                            $text.=('DATE(mcdate)="'.$mcdate[$i].'"');
                        }
                        if(strcmp($sWhere, '') != 0)
                        {
                            $sWhere.=' AND ';
                        }
                        $sWhere.=('('.$text.')');
                    }
                    if(MObj::hasRole("admin") && isset($_POST['mcsender']) && count($_POST['mcsender']) > 0)
                    {
                        $mcsender=$_POST['mcsender'];
                        $text='';
                        for($i=0; $i<count($mcsender); ++$i)
                        {
                            if($i !=  0)
                            {
                                $text.=" OR ";
                            }
                            $text.=('mcsender="'.$mcsender[$i].'"');
                        }
                        if(strcmp($sWhere, '') != 0)
                        {
                            $sWhere.=' AND ';
                        }
                        $sWhere.=('('.$text.')');
                    }
                    if(isset($_POST['mccomm']) && count($_POST['mccomm']) > 0)
                    {
                        $mccommited=$_POST['mccomm'];
                        $text='';
                        for($i=0; $i<count($mccommited); ++$i)
                        {
                            if($i !=  0)
                            {
                                $text.=" OR ";
                            }
                            $text.=('mccommited="'.$mccommited[$i].'"');
                        }
                        if(strcmp($sWhere, '') != 0)
                        {
                            $sWhere.=' AND ';
                        }
                        $sWhere.=('('.$text.')');
                    }
                }

                if(MObj::hasLoginned())
                {
                    if(!MObj::hasRole("admin"))
                    {
                        $user=MObj::getUser();
                        if(strcmp($sWhere, '')!=0)
                        {
                            $sWhere.=' AND ';
                        }
                        $sWhere.='mcsender="'.$user.'"';
                    }
                }
                else
                {
                    $user=$_SERVER['REMOTE_ADDR'];
                    if(strcmp($sWhere, '')!=0)
                    {
                        $sWhere.=' AND ';
                    }
                    $sWhere.='mcsender="'.$user.'"';
                }
                if(strcmp($sWhere, '') != 0)
                {
                    $sWhere=' WHERE '.$sWhere;
                }

                $sQuery='SELECT id, mcsender, mcaction, mcdetail, mcdate, mccommited FROM mccommand '.$sWhere.' ORDER BY mcdate ASC';
                //echo $sQuery;
                $results=mysql_query($sQuery) or die(mysql_error());

                $recIndex=1;
                while($row=mysql_fetch_array($results))
                {
                    if($row['mccommited']==0)
                    {
                       echo '<div id="div_'.$row['id'].'" style="color:black">';
                    }
                    else
                    {
                        echo '<div id="div_'.$row['id'].'" style="color:silver">';
                    }

                    $disabled="";
                    $committed="false";
                    if($row['mccommited']!=0)
                    {
                        $disabled="disabled";
                        $committed="true";
                    }
                    
                    echo '<table border="0" width="100%" style="background-color:white;">';
                    echo '<tr><td colspan="2" style="background-color:silver">Record #'.$recIndex.'('.$row['id'].')</td></tr>';
                    echo '<tr>';
                    echo '<td><b>Sender:</b></td>';
                    echo '<td>';
                    echo $row['mcsender'];
                    echo '</td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><b>Date:</b></td>';
                    echo '<td>';
                    echo $row['mcdate'];
                    echo '</td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><b>Action:</b></td>';
                    echo '<td>';
                    echo $row['mcaction'];
                    echo '</td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td><b>Committed:</b></td>';
                    echo '<td>';
                    echo $committed;
                    echo '</td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td colspan="2"><b>Detail:</b></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td colspan="2">';
                    echo $row['mcdetail'];
                    echo '</td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td colspan="2">';
                    if(MObj::hasRole("admin"))
                    {
                        echo '<button class="fg-button ui-state-default ui-corner-all" id="exec_'.$row['id'].'" '.$disabled.'>Commit</button>';
                    }
                    echo '<button class="fg-button ui-state-default ui-corner-all" id="del_'.$row['id'].'" >Delete</button>';
                    echo '</td>';
                    echo '</tr>';
                    echo '</table>';
                    echo '</div>';

                    $recIndex++;
                }
            }
            if($executed)
            {
                $handler->disconnect();
            }
        }
        echo '</form>';
    }

    private function render_search_form()
    {
         //open or create sqlite database
        $handler=new MySQLHandler();
        $executed=$handler->connect();
        $handler->select_database();
        if($handler->table_exists("mccommand"))
        {
            $senders=Array();
            if(MObj::hasRole("admin"))
            {
                $result=mysql_query("SELECT DISTINCT mcsender FROM mccommand ORDER BY mcsender ASC") or die(mysql_error());
                while($row= mysql_fetch_array($result))
                {
                    $senders[]=$row[0];
                }
            }

            $result=mysql_query("SELECT DISTINCT mcaction FROM mccommand ORDER BY mcaction ASC") or die(mysql_error());
            $actions=Array();
            while($row=  mysql_fetch_array($result))
            {
                $actions[]=$row[0];
            }

            $result=mysql_query("SELECT DISTINCT DATE(mcdate) FROM mccommand ORDER BY mcdate ASC") or die(mysql_error());
            $dates=Array();
            while($row= mysql_fetch_array($result))
            {
                $dates[]=$row[0];
            }

            $result=mysql_query("SELECT DISTINCT mccommited FROM mccommand ORDER BY mccommited ASC");
            $comms=Array();
            while($row=mysql_fetch_array($result))
            {
                $comms[]=$row[0];
            }

            echo '<table border="1" width="100%" style="background-color:white;color:black">';
            if(count($senders) > 0)
            {
                echo '<thead><tr><td>Sender</td><td>Date</td><td>Action</td><td>committed</td></tr></thead>';
            }
            else
            {
                echo '<thead><tr><td>Date</td><td>Action</td><td>committed</td></tr></thead>';
            }
            echo '<tbody>';
            echo '<tr>';
            if(count($senders) > 0)
            {
                echo '<td valign="top">';
                foreach($senders as $sender)
                {

                    if(isset($_POST['mcsender']) && in_array($sender, $_POST['mcsender']))
                    {
                        $checked='checked';
                    }
                    else
                    {
                        $checked='';
                    }
                    echo '<input type="checkbox" name="mcsender[]" value="'.$sender.'" '.$checked.'>'.$sender.'</input><br />';
                }
                echo '</td>';
            }
            
            echo '<td valign="top">';
            foreach($dates as $d)
            {
                if(isset($_POST['mcdate']) && in_array($d, $_POST['mcdate']))
                {
                    $checked='checked';
                }
                else
                {
                    $checked='';
                }
                echo '<input type="checkbox" name="mcdate[]" value="'.$d.'" '.$checked.'>'.$d.'</input><br />';
            }
            echo '</td>';
            echo '<td valign="top">';
            foreach($actions as $action)
            {
                if(isset($_POST['mcaction']) && in_array($action, $_POST['mcaction']))
                {
                    $checked='checked';
                }
                else
                {
                    $checked='';
                }
                echo '<input type="checkbox" name="mcaction[]" value="'.$action.'" '.$checked.'>'.$action.'</input><br />';
            }
            echo '</td>';
            echo '<td valign="top">';
            foreach($comms as $comm)
            {
                if(isset($_POST['mccomm']) && in_array($comm, $_POST['mccomm']))
                {
                    $checked='checked';
                }
                else
                {
                    $checked='';
                }
                if($comm==0)
                {
                    $commtxt='uncommitted';
                }
                else
                {
                    $commtxt='committed';
                }
                echo '<input type="checkbox" name="mccomm[]" value="'.$comm.'" '.$checked.'>'.$commtxt.'</input><br />';
            }
            echo '</td>';
            echo '</tr>';
            echo '</tbody>';
            echo '<tfoot>';
            echo '<tr><td colspan="4">';
            echo '<button class="fg-button ui-state-default ui-corner-all" name="btnSearch" id="btnSearch">Search</button>';
            echo '</td></tr>';
            echo '</tfoot>';
            echo '</table>';

        }
        else
        {
            echo '<h3><ul><li>Currently there is no user-submitted modification in the system for publishing</li></ul></h3>';   
        }
    }

    
}

?>

<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DBBackup
 *
 * @author Xianshun
 */
 require_once("MObj.php");
 require_once("MySQLHandler.php");
 
class DBBackup extends MObj{
    //put your code here
    private $mPostBack;
    private $mCategoryTypes;
    
    public function __construct($id)
    {
        parent::__construct($id);

        if(isset($_POST['btnBackupTxt']) || isset($_POST['btnBackupXml']))
        {
            $this->mPostBack=true;
        }
        else
        {
            $this->mPostBack=false;
        }

        $this->mCategoryTypes=Array();
        $this->mCategoryTypes[]='application';
        $this->mCategoryTypes[]='design';
        $this->mCategoryTypes[]='memetic';
        $this->mCategoryTypes[]='author';
        $this->mCategoryTypes[]='journal';
        $this->mCategoryTypes[]='definition';
    }

    public function render()
    {
        if($this->mPostBack)
        {
            echo '<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td>';
            $this->render_form();
            echo '</td></tr><tr><td>';
            $this->render_data('');
            $this->render_data('0');
            $this->render_data('1');
            echo '</td></tr></table>';
        }
        else
        {
            $this->render_form();
        }
    }

    public function render_data($index)
    {
        if($this->mPostBack)
        {
            if(isset($_POST['btnBackupTxt']))
            {
                $content='';
                $link=$this->backup_table_txt("mcarticles".$index.".txt", "mcarticles".$index);
                $content.='The mcarticles'.$index.' has been backup in the <a href="' . $link . '">' . $link . '</a><br />';
                $link=$this->backup_table_txt("mcmembership.txt", "mcmembership");
                $content.='The mcmembership'.$index.' has been backup in the <a href="' . $link . '">' . $link . '</a><br />';

                foreach($this->mCategoryTypes as $category_type)
                {
                    $link=$this->backup_table_txt("mc".$category_type."dictionary".$index.".txt", "mc".$category_type."dictionary".$index);
                    $content.='The mc'.$category_type.'dictionary'.$index.' has been backup in the <a href="' . $link . '">' . $link . '</a><br />';
                    $link=$this->backup_table_txt("mc".$category_type."s".$index.".txt", "mc".$category_type."s".$index);
                    $content.='The mc'.$category_type.'s'.$index.' has been backup in the <a href="' . $link . '">' . $link . '</a><br />';
                }
                
                echo $content;
            }
            else if(isset($_POST['btnBackupXml']))
            {
                $content='';
                $link=$this->backup_table_xml("mcarticles".$index.".xml", "mcarticles".$index,
                    array("id", "title", "pages", "loc", "type", "year", "abstract", "comment", "keywords"),
                    "article");
                $content.='The mcarticles has been backup in the <a href="' . $link . '">' . $link . '</a><br />';
                $link=$this->backup_table_xml("mcmembership".$index.".xml", "mcmembership".$index,
                    array("id", "muser", "mpwd", "mrole"),
                    "membership");
                $content.='The mcmembership'.$index.' has been backup in the <a href="' . $link . '">' . $link . '</a><br />';

                foreach($this->mCategoryTypes as $category_type)
                {
                    $link=$this->backup_table_xml("mc".$category_type."dictionary".$index.".xml", "mc".$category_type."dictionary".$index,
                        array("id", "detail", "parentid", "description"),
                        $category_type."dictionary");
                    $content.='The mc'.$category_type.'dictionary has been backup in the <a href="' . $link . '">' . $link . '</a><br />';
                    $link=$this->backup_table_xml("mc".$category_type."s".$index.".xml", "mc".$category_type."s".$index,
                        array("id", "articleid", $category_type."id"),
                        $category_type);
                    $content.='The mc'.$category_type.'s'.$index.' has been backup in the <a href="' . $link . '">' . $link . '</a><br />';
                }
                
                echo $content;
            }
        }
    }

    private function backup_table_xml($filename, $tablename, $fieldnames, $recordname)
    {
        $xml=new DOMDocument();
        $xml->formatOutput = true;

        $root = $xml->createElement($tablename);
        $xml->appendChild($root);

        $handler=new MySQLHandler();
        $handler->connect();
        $handler->select_database();

        $sQuery="SELECT * from ".$tablename;
        $rResult=mysql_query($sQuery) or die(mysql_error());
        while($rRow=mysql_fetch_array($rResult))
        {
            $record = $xml->createElement($recordname);
            foreach($fieldnames as $fieldname)
            {
                $fv=str_replace("\n", "[br]", $rRow[$fieldname]);
                //$fv=str_replace("\\n", "", $fv);

                $record->setAttribute($fieldname, addslashes($fv));
            }

            $root->appendChild($record);
        }
        $handler->disconnect();

        $xml->save($filename);

        return $filename;
    }

    private function backup_table_txt($filename, $tablename)
    {
        if(file_exists($filename))
        {
            unlink($filename);
        }

        $handler=new MySQLHandler();
        $handler->connect();
        $handler->select_database();

        //$sQuery="LOAD DATA INFILE '".$filename."' INTO TABLE articles";
        $sQuery="SELECT * into OUTFILE '".mysql_real_escape_string(getcwd()."\\".$filename)."' FROM ".$tablename;
        mysql_query($sQuery) or die(mysql_error());
        $handler->disconnect();

        return $filename;
    }

    private function render_form()
    {
        $content='<form method="post" action="' . basename($_SERVER['PHP_SELF']) . '">
        <table border="0" align="left">
        <tr><td>Username: </td><td><input type="text" name="txtUsername" value="chen0469" /></td></tr>
        <tr><td>Password: </td><td><input type="password" name="txtPassword" value="" /></td></tr>
        <tr><td colspan="2"><input type="submit" name="btnBackupTxt" value="Backup to Txt" /></td></tr>
        <tr><td colspan="2"><input type="submit" name="btnBackupXml" value="Backup to Xml" /></td></tr>
        </table>
        </form>
        ';

        echo $content;
    }
}
?>

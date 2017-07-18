<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DBSetup
 *
 * @author Xianshun
 */
require_once("MObj.php");
require_once("MySQLHandler.php");

class DBSetup extends MObj{
    //put your code here
    private $mPostBack;
    private $mCategoryTypes;
    public function __construct($id)
    {
        parent::__construct($id);
        if(isset($_POST['btnSetupTxt']) || isset($_POST['btnSetupXml']))
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

    private function lockSetupCfg()
    {
        $handler=new MySQLHandler();
        $handler->connect();
        $filename=mysql_real_escape_string(getcwd()."\\setup.cfg");
        $handler->disconnect();
        if(file_exists($filename))
        {
            $doc = new DOMDocument();
            $doc->load($filename);

            $setups = $doc->getElementsByTagName("setup");
            foreach($setups as $setup)
            {
                $setup->setAttribute("enabled", "false");
            }

            $doc->save($filename);
        }
    }

    public function render()
    {
        if($this->mPostBack)
        {
            $this->setupDB();
            echo '<table><tr><td>';
            $this->render_form();
            echo '</td></tr><tr><td>';
            $this->render_data();
            echo '</td></tr></table>';
            $this->lockSetupCfg();
        }
        else
        {
            $this->render_form();
        }
    }

    public function render_data()
    {
        if(isset($_POST['btnSetupTxt']))
        {
            echo '<a href="mcarticles.txt">mcarticles</a> has been created<br />';
            echo '<a href="mcmembership.txt">mcmembership</a> has been created<br />';

            foreach($this->mCategoryTypes as $category_type)
            {
                echo '<a href="mc'.$category_type.'dictionary.txt">m'.$category_type.'dictionary</a> has been created<br />';
                echo '<a href="mc'.$category_type.'s.txt">mc'.$category_type.'</a> has been created<br />';
            }
        }
        else if(isset($_POST['btnSetupXml']))
        {
            echo '<a href="mcarticles.xml">mcarticles</a> has been created<br />';
            echo '<a href="mcmembership.xml">mcmembership</a> has been created<br />';

            foreach($this->mCategoryTypes as $category_type)
            {
                echo '<a href="mc'.$category_type.'dictionary.xml">m'.$category_type.'dictionary</a> has been created<br />';
                echo '<a href="mc'.$category_type.'xml">mc'.$category_type.'</a> has been created<br />';
            }
        }
    }

    private function setupDB()
    {
        $this->create_table_articles();
        $this->create_table_membership();

        foreach($this->mCategoryTypes as $category_type)
        {
            $this->create_table_category_dictionary($category_type);
            $this->create_table_categories($category_type);
        }

        if(isset($_POST['btnSetupTxt']))
        {
            $this->populate_table_txt("mcarticles.txt", "mcarticles");
            $this->populate_table_txt("mcmembership.txt", "mcmebership");

            foreach($this->mCategoryTypes as $category_type)
            {
                $this->populate_table_txt("mc".$category_type."s.txt", "mc".$category_type."s");
                $this->populate_table_txt("mc".$category_type."dictionary.txt", "mc".$category_type."dictionary");
            }
        }
        else if(isset($_POST['btnSetupXml']))
        {
            $this->populate_table_xml("mcarticles.xml", "article",
                array("id", "title", "pages", "loc", "type", "year", "abstract", "comment", "keywords"),
                "mcarticles");
            $this->populate_table_xml("mcmembership.xml", "membership",
                array("id", "muser", "mpwd", "mrole"),
                "mcmembership");

            foreach($this->mCategoryTypes as $category_type)
            {
                $this->populate_table_xml("mc".$category_type."s.xml", $category_type,
                    array("id", "articleid", $category_type."id"),
                    "mc".$category_type."s");
                $this->populate_table_xml("mc".$category_type."dictionary.xml", $category_type."dictionary",
                    array("id", "detail", "parentid", "description"),
                    "mc".$category_type."dictionary");
            }
        }
        return true;
    }


    private function populate_table_txt($filename, $tablename)
    {
        if(file_exists($filename))
        {
            $handler=new MySQLHandler();
            $handler->connect();
            $filepath=mysql_real_escape_string(getcwd()."\\".$filename);
            $sQuery="LOAD DATA INFILE '".$filepath."' INTO TABLE ".$tablename;
            $handler->select_database();
            mysql_query($sQuery) or die(mysql_error());
            $handler->disconnect();

            return true;
        }
        return false;
    }

    private function create_table_category_dictionary($category_type)
    {
        $handler=new MySQLHandler();

        $fields=Array();
        $fields['id']='INT NOT NULL AUTO_INCREMENT';
        $fields['detail']='TEXT';
	$fields['parentid']='INT';
        $fields['description']='TEXT';

        $primary_key='id';

        $handler->create_table("mc".$category_type."dictionary", $fields, $primary_key);

        return true;
    }

    private function create_table_categories($category_type)
    {
        $handler=new MySQLHandler();

        $fields=Array();
        $fields['id']='INT NOT NULL AUTO_INCREMENT';
        $fields['articleid']='INT';
	$fields[$category_type.'id']='INT';

        $primary_key='id';
	$handler->create_table("mc".$category_type."s", $fields, $primary_key);

        return true;
    }

    private function create_table_articles()
    {
        $handler=new MySQLHandler();

        $fields=Array();
        $fields['id']='INT NOT NULL AUTO_INCREMENT';
	$fields['title']='VARCHAR(255)';
	$fields['pages']='VARCHAR(30)';
        $fields['loc']='VARCHAR(30)';
	$fields['type']='VARCHAR(20)';
	$fields['year']='VARCHAR(10)';
        $fields['abstract']='TEXT';
        $fields['comment']='TEXT';
        $fields['keywords']='VARCHAR(255)';

        $primary_key='id';
	$handler->create_table("mcarticles", $fields, $primary_key);
    }

    private function create_table_membership()
    {
        $handler=new MySQLHandler();

        $fields=Array();
        $fields['id']='INT NOT NULL AUTO_INCREMENT';
	$fields['muser']='VARCHAR(255)';
	$fields['mpwd']='VARCHAR(255)';
	$fields['mrole']='VARCHAR(20)';

        $primary_key='id';
	$handler->create_table("mcmembership", $fields, $primary_key);
    }

    /*
    private function init_tables()
    {
        $detail[1]="Graph Bisection";
        $detail[2]="SAT";
        $detail[3]="Iterated Prisoner Dilemma";
        $detail[4]="Set Partitioning Problem";
        $detail[5]="Max Cut Problem";
        $detail[6]="Team Orientation Problem";
        $detail[7]="Graph Coloring";
        $detail[8]="String Problem";
        $detail[9]="Constraint Satisfaction Problem";
        $detail[10]="Golomb Rulers Problem";
        $detail[11]="Still Life Problem";
        $detail[12]="Social Golfers";
        $detail[13]="Graph Matching";
        $detail[14]="Bin Packing ";
        $detail[15]="MinLA Problem";
        $detail[16]="Unit Commitment";
        $detail[17]="Finance";
        $detail[18]="Biology";
        $detail[19]="Distribution and Assignment Logistics";
        $detail[20]="Production Scheduling";
        $detail[21]="Micro-electronics";
        $detail[22]="Transportation Scheduling";
        $detail[23]="Statistical Modeling and Estimation";
        $detail[24]="Complex System";
        $detail[25]="Security and Cryptography";
        $detail[26]="E-learning";
        $detail[27]="Graphics and Image Processing";
        $detail[28]="Database Query";
        $detail[29]="Machine Learning";
        $detail[30]="Test Data Generation";
        $detail[31]="Communication";
        $detail[32]="Engineering Design";
        $detail[33]="Hardware-based Impelementation ";
        $detail[34]="Multi-Objective Optimization";
        $detail[35]="Optimization in Uncertain and Dynamic Environment";
        $detail[36]="Large-Scale Optimization Problems";
        $detail[100000]="Simplified Benchmarks";
        $detail[100001]="Real-World Problems";
        $detail[100002]="Nature of Problem";

        for($i=1; $i<=16; ++$i)
        {
            $handler->insert_into_table("mcapplicationdictionary",
                array('id', 'detail', 'parentid'),
                array($i, $detail[$i], 100000)
                );
        }

        for($i=17; $i<=33; ++$i)
        {
            $handler->insert_into_table("mcapplicationdictionary",
                array('id', 'detail', 'parentid'),
                array($i, $detail[$i], 100001)
                );
        }

        for($i=34; $i<=36; ++$i)
        {
            $handler->insert_into_table("mcapplicationdictionary",
                array('id', 'detail', 'parentid'),
                array($i, $detail[$i], 100002)
                );
        }

        for($i=100000; $i<=100002; ++$i)
        {
            $handler->insert_into_table("mcapplicationdictionary",
                array('id', 'detail', 'parentid'),
                array($i, $detail[$i], 0)
                );
        }
    }*/

    private function render_form()
    {
        $content='<form method="post" action="' . basename($_SERVER['PHP_SELF']) . '">
        <table border="0" align="left">
        <tr><td>Username: </td><td><input type="text" name="txtUsername" value="chen0469" /></td></tr>
        <tr><td>Password: </td><td><input type="password" name="txtPassword" value="" /></td></tr>
        <tr><td colspan="2"><input type="submit" name="btnSetupTxt" value="Setup By Txt" /></td></tr>
        <tr><td colspan="2"><input type="submit" name="btnSetupXml" value="Setup By Xml" /></td></tr>
        </table>
        </form>
        ';

        echo $content;
    }

    private function populate_table_xml($filename, $recordname, $fieldnames, $tablename)
    {
        if(! file_exists($filename))
        {
            return false;
        }
        
        $handler=new MySQLHandler();

        $fields=Array();

        $doc = new DOMDocument();
        $doc->load($filename);

        $records = $doc->getElementsByTagName($recordname);
        foreach( $records as $record )
        {
            $handler->connect();
            foreach($fieldnames as $fieldname)
            {
                if($record->hasAttribute($fieldname))
                {
                    $fieldvalue = $record->getAttribute($fieldname);
                    $fields[$fieldname]=$fieldvalue;
                }
                else
                {
                    $fields[$fieldname]='';
                }
            }
            $handler->disconnect();

            $fieldvalues=array();
            foreach($fields as $fieldname => $fieldvalue)
            {
                $fv=str_replace("[br]", "\n", $fieldvalue);
                //$fv=str_replace("\\n", "", $fv);

                $fieldvalues[]=$fv;
            }

            $handler->insert_into_table($tablename, $fieldnames, $fieldvalues);
        }

        return true;
    }
}
?>

<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Xianshun
 */
session_start();

require_once("MySqlHandler.php");
require_once("JQueryObj.php");

class MObj implements JQueryObj{
    //put your code here
    protected $mCSSFiles=Array();
    protected $mScripts=Array();
    protected $mId;
    protected $mTopOffset;
    protected $mBottomOffset;
	protected $mBackground;
	private $mBGColor;

    public function __construct($id)
    {
        $this->mId=$id;
        $this->mTopOffset=0;
        $this->mBottomOffset=0;
		$this->mBackground="";
		$this->mBGColor=null;
    }
	
	public function getBackground()
	{
		return $this->mBackground;
	}
	
	public function getBGColor()
	{
		return $this->mBGColor;
	}
	
	public function setBGColor($color)
	{
		$this->mBGColor=$color;
	}
	
	public function setBackground($bg)
	{
		$this->mBackground=$bg;
	}

    public function setTopOffset($offset)
    {
        $this->mTopOffset=$offset;
    }

    public static function commandBaseExists()
    {
        $handler=new MySQLHandler();
        return $handler->table_exists("mccommand");
        /*
        $db = new SQLiteDatabase('command.mc');
        if($db)
        {
            //check whether table exists
            $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='mccommand'");
            if($result->numRows() == 0)
            {
                return false;
            }
            return true;
        }
        else
        {
            die($err);
        }*/
    }
   
    public static function numUncommittedCommandsToday($mcuser)
    {
        $handler=new MySQLHandler();
        if($handler->table_exists("mccommand"))
        {
            $executed=$handler->connect();
            $handler->select_database();
            $sQuery="SELECT count(mcquery) FROM mccommand WHERE mcsender='".mysql_escape_string($mcuser)."' AND mccommited='0' AND date(mcdate)=date('NOW');";
            $result=mysql_query($sQuery) or die(mysql_error());
            $row=mysql_fetch_array($result) or die(mysql_error());
            $count=$row[0];
            if($executed)
            {
                $handler->disconnect();
            }
            return $count;
        }
        return 0;
        /*
        $db = new SQLiteDatabase('command.mc');
        if($db)
        {
            //check whether table exists
            $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='mccommand'");
            if($result->numRows() == 0)
            {
                return 0;
            }

            //$mcdate=date("Y-m-d H:i:s");
            $sQuery="SELECT count(mcquery) FROM mccommand WHERE mcsender='".sqlite_escape_string($mcuser)."' AND mccommited='0' AND date(mcdate)=date('NOW');";

            $result=$db->query($sQuery);
            $row=$result->fetch();
            return $row[0];
        }
        else
        {
            die($err);
        }*/
    }


    public static function addCommand($mcuser, $mcquery, $mcaction, $mcdetail)
    {
        $handler=new MySQLHandler();
        $executed=$handler->connect();
        $handler->select_database();
        if(!$handler->table_exists("mccommand"))
        {
            $sQuery='CREATE TABLE mccommand (id INT NOT NULL AUTO_INCREMENT, mcsender VARCHAR(255), mcaction TEXT, mcdetail TEXT, mcquery TEXT, mccommited VARCHAR(1), mcdate DATETIME);';
            mysql_query($sQuery) or die(mysql_error());
        }
        $sQuery="INSERT INTO mccommand (mcsender, mcquery, mcaction, mcdetail, mccommited, mcdate) VALUES ('".mysql_escape_string($mcuser)."', '".mysql_escape_string($mcquery)."', '".mysql_escape_string($mcaction)."', '".mysql_escape_string($mcdetail)."', '0', NOW())";
        mysql_query($sQuery) or die(mysql_error());
        if($executed)
        {
            $handler->disconnect();
        }
        /*
        //open or create sqlite database
        $db = new SQLiteDatabase('command.mc');
        if($db)
        {
            //check whether table exists
            $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='mccommand'");
            if($result->numRows() == 0)
            {
                $db->queryExec('CREATE TABLE mccommand (id INTEGER PRIMARY_KEY AUTOINCREMENT, mcsender VARCHAR(255), mcaction TEXT, mcdetail TEXT, mcquery TEXT, mccommited VARCHAR(1), mcdate DATETIME);');
            } 

            $sQuery="INSERT INTO mccommand (mcsender, mcquery, mcaction, mcdetail, mccommited, mcdate) VALUES ('".sqlite_escape_string($mcuser)."', '".sqlite_escape_string($mcquery)."', '".sqlite_escape_string($mcaction)."', '".sqlite_escape_string($mcdetail)."', '0', DATETIME('NOW'))";
            $db->queryExec($sQuery);
        }
        else
        {
            die($err);
        }*/
    }

    public static function hasLoginned()
    {
        if(isset($_SESSION['user']))
        {
            return true;
        }
        return false;
    }

    public static function hasRole($role)
    {
        if(isset($_SESSION['role']))
        {
            if(strcmp($_SESSION['role'], $role)==0)
            {
                return true;
            }
        }
        return false;
    }

    public static function getUser()
    {
        if(isset($_SESSION['user']))
        {
            return $_SESSION['user'];
        }
        return null;
    }

    public static function getRole()
    {
        if(isset($_SESSION['role']))
        {
            return $_SESSION['role'];
        }
        return null;
    }
    
    public function setBottomOffset($offset)
    {
        $this->mBottomOffset=$offset;
    }

    public function clearScript()
    {
        $this->mScripts=Array();
    }

    public function clearCSS()
    {
        $this->mCSSFiles=Array();
    }

    public function getTopOffset()
    {
        return $this->mTopOffset;
    }

    public function render_top_offset()
    {
        for($i=0; $i<$this->mTopOffset; ++$i)
        {
            echo '<br />';
        }
    }

    public function render_bottom_offset()
    {
        for($i=0; $i<$this->mBottomOffset; ++$i)
        {
            echo '<br />';
        }
    }

    public function getBottomOffset()
    {
        return $this->mBottomOffset;
    }

    public function getId()
    {
        return $this->mId;
    }
    
    public function render()
    {
        
    }

    public function render_hidden()
    {
        
    }

    public function linefeed()
    {
        return '';
    }

    public function render_header()
    {
        
    }

    public function getScripts()
    {
        return $this->mScripts;
    }

    public function getCSS()
    {
        return $this->mCSSFiles;
    }

    public function addScript($script)
    {
        if(!$this->scriptExists($script))
        {
            $this->mScripts[count($this->mScripts)]=$script;
        }
    }

    public function getScriptCount()
    {
        return sizeof($this->mScripts);
    }

    public function getCSSCount()
    {
        return count($this->mCSSFiles);
    }

    protected function scriptExists($filename)
    {
        if(!isset($filename))
        {
            return true;
        }
        foreach($this->mScripts as $key => $value)
        {
             if(strcmp($value, $filename)==0)
             {
                 return true;
             }
        }
        return false;
    }

    protected function CSSExists($filename)
    {
        if(!isset($filename))
        {
            return true;
        }
        foreach($this->mCSSFiles as $key => $value)
        {
             if(strcmp($value, $filename)==0)
             {
                 return true;
             }
        }
        return false;
    }

    public function addCSS($css)
    {
        $this->mCSSFiles[count($this->mCSSFiles)]=$css;
    }
}
?>

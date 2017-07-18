<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MySQLHandler
 *
 * @author Xianshun
 */
class MySQLHandler {
    //put your code here
    private $mHostName;
    private $mUsername;
    private $mPassword;
    private $mDatabase;
    private static $mIsConnected=false;
    
    public function __construct()
    {
        $this->mHostName="localhost";
            $this->mUsername="root";
            $this->mPassword="chen0469";
            $this->mDatabase="mysql";
    }

    public function connect()
    {
        if(self::$mIsConnected==false)
        {
            mysql_connect($this->mHostName, $this->mUsername, $this->mPassword) or die(mysql_error());
            self::$mIsConnected=true;
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function table_exists($table)
    {
        $executed=$this->connect();

        $tables=mysql_list_tables($this->mDatabase) or die(mysql_error());
        while(list($temp)=mysql_fetch_array($tables))
        {
            if(strtolower($temp) == strtolower($table))
            {
                return true;
            }
        }

        if($executed)
        {
            $this->disconnect();
        }

        return false;
    }

    public function select_database()
    {
        mysql_select_db($this->mDatabase) or die(mysql_error());
    }

    public function delete_table($table_name)
    {
        $executed=$this->connect();

        $this->select_database();

        $query="DROP TABLE " . $table_name;
        mysql_query($query) or die(mysql_error());

        if($executed)
        {
            $this->disconnect();
        }
    }

    public function create_table($table_name, $fields, $primary_key)
    {
        if($this->table_exists($table_name)==TRUE)
        {
            $this->delete_table($table_name);
        }

        $executed=$this->connect();
        $this->select_database();

        $query="CREATE TABLE " . $table_name . " (";
        foreach($fields as $key => $detail)
        {
                $query=$query . $key . " " . $detail . ", ";
                if($key==$primary_key)
                {
                        $query=$query . "PRIMARY KEY(" . $key . "), ";
                }
        }
        $query=substr_replace($query, ")", -2);
        //echo $query;

        mysql_query($query) or die(mysql_error());

        if($executed)
        {
            $this->disconnect();
        }
    }

    public function insert_into_table($table_name, $keys, $values)
    {
        $executed=$this->connect();

        $this->select_database();

        $query="INSERT INTO " . $table_name . " (";
        foreach($keys as $key)
        {
            $query=$query . $key . ", ";
        }
        $query=substr_replace($query, ")", -2);
        $query=$query . " VALUES (";
        foreach($values as $value)
        {
            $refined=mysql_real_escape_string(stripslashes($value));
            $query=$query . "'" . $refined . "', ";
        }
        $query=substr_replace($query, ")", -2);
        //echo $query;
        mysql_query($query) or die(mysql_error());

        if($executed)
        {
            $this->disconnect();
        }
    }

    private function select_from_table($table_name, $keys, $criteria)
    {
        $query="SELECT ";
        if(count($keys) == 0)
        {
            $query=$query . "*";
        }
        else
        {
            foreach($keys as $key)
            {
                $query=$query . $key . ", ";
            }
        }
        $query=substr_replace($query, "", -2);
        $query=$query . " FROM " . $table_name;
        if($criteria != "")
        {
            $query=$query . " " . $criteria;
        }
        //echo $query;
        $result=mysql_query($query) or die(mysql_error());

        return $result;
    }

    public function create_html_table($table_name, $keys, $criteria, $display_fields, $display_style)
    {
        $executed=$this->connect();

        $this->select_database();

        $data=$this->select_from_table($table_name, $keys, $criteria);
        $content="<table style=\"" . $display_style . "\" border=\"1\">";

        $content = $content . "<tr>";
        foreach($display_fields as $value)
        {
                $content = $content . "<td style=\"font-weight:bold\">" . $value . "</td>";
        }
        $content = $content . "</tr>";

        while($row = mysql_fetch_array($data))
        {

                $content = $content . "<tr>";
                foreach($keys as $key)
                {
                        $content = $content . "<td>" . mysql_real_escape_string($row[$key]) . "</td>";
                }
                $content = $content . "</tr>";
        }

        if($executed)
        {
            $this->disconnect();
        }

        $content=$content . "</table>";

        return $content;
    }

    public function disconnect()
    {
        if(self::$mIsConnected==true)
        {
            mysql_close();
            self::$mIsConnected=false;
            return true;
        }
        else
        {
            return false;
        }
    }
}
?>

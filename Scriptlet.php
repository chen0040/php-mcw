<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Scriptlet
 *
 * @author Xianshun
 */
 require_once("Mobj.php");
 
class Scriptlet extends MObj{
    //put your code here
    private $mJQueryScript;
    private $mJQueryUIScript;
    private $mObjs=Array();
    
    public function __construct()
    {
        parent::__construct("Scriptlet");
        
		//$this->mJQueryScript="jquery/jquery.min.js";
        //$this->mJQueryUIScript="jquery/jquery-ui.min.js";      
        $this->mJQueryScript="jquery/jquery-1.3.2.min.js";
        $this->mJQueryUIScript="jquery/jquery-ui-1.7.2.custom.min.js";        
    }

    public function add($obj)
    {
        $this->mObjs[$obj->getId()]=$obj;
    }

    public function remove($obj)
     {
         foreach($this->mObjs as $key => $value)
         {
             if($value == $obj)
             {
                 unset($this->mObjs[$key]);
                 break;
             }
         }
     }

    public function render()
    {
        echo '<script src="' . $this->mJQueryScript . '" type="text/javascript"></script>';
        echo '<script src="' . $this->mJQueryUIScript . '" type="text/javascript"></script>';

        $this->clearScript();
        foreach($this->mObjs as $key => $obj)
        {
            $filenames=$obj->getScripts();
            if(isset($filenames))
            {
                foreach($filenames as $key => $value)
                {
                     $this->addScript($value);
                }
            }
        }

        foreach($this->mScripts as $key => $value)
        {
            echo '<script src="' . $value . '" type="text/javascript"></script>';
        }

        echo '<script type="text/javascript">';
        foreach($this->mObjs as $key => $obj)
        {
            
            if(isset($obj))
            {
                $obj->render_header();
            }
        }
        echo '</script>';
    }
}
?>

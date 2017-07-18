<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Stylet
 *
 * @author Xianshun
 */
 require_once("MObj.php");

class Stylet extends MObj{
    //put your code here
    private $mJQueryUICSSFile;
    private $mAdditionalCSS=Array();
    private $mObjs=Array();

     public function __construct()
     {
         parent::__construct("Stylet");
         //add JQuery css
         $selected_skin='black-tie';
        if(file_exists('theme.xml'))
        {
            $doc = new DOMDocument();
            $doc->load("theme.xml");

            $roots=$doc->getElementsByTagName("theme");
            foreach($roots as $root)
            {
                $selected_skin=$root->getAttribute("skin");
                break;
            }
        }
         $this->mJQueryUICSSFile="themes/".$selected_skin."/jquery-ui-1.7.2.custom.css";
         $this->mAdditionalCSS[count($this->mAdditionalCSS)]="codezone/css/codezone.css";
         
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

     public function add($obj)
     {
         $this->mObjs[count($this->mObjs)]=$obj;
     }

     public function setThemeCSSFile($filename)
     {
         $this->mJQueryUICSSFile=$filename;
     }

     public function render()
     {
         echo '<link href="' . $this->mJQueryUICSSFile . '" rel="stylesheet" type="text/css" />';

         $this->clearCSS();
         foreach($this->mObjs as $key => $obj)
         {
             $filenames=$obj->getCSS();
             if(isset($filenames))
             {
                 foreach($filenames as $key => $value)
                 {
                     if(!$this->CSSExists($value))
                     {
                         $this->addCSS($value);
                     }
                 }
             }
         }

         foreach($this->mAdditionalCSS as $key => $value)
         {
             echo '<link href="' . $value . '" rel="stylesheet" type="text/css" />';
         }
         
         foreach($this->mCSSFiles as $key => $value)
         {
             if(strcmp($value, '') != 0)
             {
                echo '<link href="' . $value . '" rel="stylesheet" type="text/css" />';
             }
         }
     }
}
?>

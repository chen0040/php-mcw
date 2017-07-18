<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JQueryPanel
 *
 * @author AB
 */
require_once('MObj.php');
require_once('MySQLHandler.php');
require_once("phpmathpublisher/mathpublisher.php") ;

class JQueryChart extends MObj{
    //put your code here
    protected $mTitle;
    protected $mContent;
    protected $mImg;
    protected $mHeight;
    protected $mWidth;
    public function __construct($id)
    {
        parent::__construct($id);
        $this->mImg='';

//        $this->addCSS("jscrollpane/jScrollPane.css");
//        $this->addCSS("jscrollpane/demoStyles.css");
//        $this->addScript("jscrollpane/jquery.mousewheel.js");
//        $this->addScript("jscrollpane/jScrollPane-1.2.3.min.js");
		$this->addScript("jquery/jquery.corner.js");

        $this->mWidth=-1;
        $this->mHeight=-1;
    }

    public function setTitle($title)
    {
        $this->mTitle=$title;
    }
    
    public function setContent($content)
    {
        $pathtoimg="phpmathpublisher/img/"; //img folder for storing the generated img
        $size=14; //font size
        
        $this->mContent=mathfilter($content,$size,$pathtoimg);
    }

    public function setUrl($filename)
    {
        $this->mContent='';
        if(file_exists($filename))
        {
            $file_handle = fopen($filename, "r");
            while (!feof($file_handle)) {
               $line = fgets($file_handle);
               $this->mContent.= $line;
            }
            fclose($file_handle);
        }
    }

    public function setWidth($width)
    {
        $this->mWidth=$width;
    }

    public function setHeight($height)
    {
        $this->mHeight=$height;
    }
	
	public function render_header()
    {
        echo '$(function(){
            $("#'.$this->mId.'_title").corner("bevel top");
        });';
    }

    public function setIcon($img)
    {
        $this->mImg=$img;
    }

//    public function render_header()
//    {
//        $content='$(function(){$("#'.$this->mId.'_panel").jScrollPane();});';
//        echo $content;
//    }

    public function render_title()
    {
         $img='';
        if(strcmp($this->mImg, '')!=0)
        {
            $img='<img src="'.$this->mImg.'" border="0" align="absmiddle" /> ';
        }

        $format='padding-top:5px;padding-bottom:5px;padding-left:5px;padding-right:5px;background: url(codezone/css/images/top_bar.png);'; //#6af

        if($this->mWidth != -1)
        {
            $format.='width:'.($this->mWidth).'px;';
        }

        echo '<div style="'.$format.'" id="'.$this->mId.'_title">'.$img.$this->mTitle.'</div>'; //class="ui-state-default ui-corner-all"
    }

//    public function render_content()
//    {
//        $format='background:white;color:black';
//
//        if($this->mWidth != -1)
//        {
//            $format.='width:'.$this->mWidth.'px;';
//        }
//        
//        if($this->mHeight != -1)
//        {
//            $format.='height:'.$this->mHeight.'px;';
//        }
//
//
//        echo '<div id="'.$this->mId.'_panel" class="scroll-pane" style="'.$format.'">'.$this->mContent.'</div>';
//    }

    public function render()
    {
        
        
        echo '<table border="0" cellspacing="0" cellpadding="0" width="100%">';
        echo '<tr><td>';
        $this->render_title();
        echo '</td></tr>';
        $format='background:white;color:black;padding:5px;border-left:1px solid cyan;border-right:1px solid cyan;border-bottom:4px double cyan;';

        if($this->mWidth != -1)
        {
            $format.='width:'.$this->mWidth.'px;';
        }
        echo '<tr><td style="'.$format.'">';
        
		echo '<iframe src ="publication_chart.php" frameborder="0" width="100%" height="200">';
		echo '<p>Your browser does not support iframes.</p>';
		echo '</iframe>';

        echo '</td></tr>';
        echo '</table>';
        
    }
}
?>

<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GridLayout
 *
 * @author Xianshun
 */
 require_once("MObj.php");
class GridLayout extends MObj{
    //put your code here
    private $mRowCount;
    private $mColCount;
    private $mGrid=Array();
    private $mCentered;
    private $mColSpans=Array();
    private $mRowSpans=Array();
    private $mCellVAlignments=Array();
    private $mCellHAlignments=Array();
    private $mCellBGColor=Array();
    private $mBorder;
    private $mCellSpacing;
    private $mCellPadding;
    private $mBGColor;
    private $mBGImg;
    
    public function __construct($id, $rows, $cols)
    {
        parent::__construct($id);
        
        $this->mRowCount=$rows;
        $this->mColCount=$cols;
        $this->mBorder=0;
        $this->mCellSpacing=0;
        $this->mCellPadding=0;
        $this->mBGColor=null;


        for($i=0; $i<$rows; ++$i)
        {
            $this->mGrid[$i]=Array();
            $this->mColSpans[$i]=Array();
            $this->mRowSpans[$i]=Array();
            $this->mCellVAlignments[$i]=Array();
            $this->mCellHAlignments[$i]=Array();
            $this->mCellBGColor[$i]=Array();
            for($j=0; $j<$cols; ++$j)
            {
                $this->mGrid[$i][$j]=null;
                $this->mColSpans[$i][$j]=1;
                $this->mRowSpans[$i][$j]=1;
                $this->mCellVAlignments[$i][$j]="top";
                $this->mCellHAlignments[$i][$j]=null;
                $this->mCellBGColor[$i][$j]="white";
            }
        }

        $this->mCentered=true;
        $this->mBGImg=''; //'codezone/css/images/ui-bg_diagonals-thick_8_333333_40x40.png';
    }

    public function setBGImg($img)
    {
        $this->mBGImg=$img;
    }

    public function center($centered)
    {
        $this->mCentered=$centered;
    }

    public function setCellBGColor($row, $col, $color)
    {
        $this->mCellBGColor[$row][$col]=$color;
    }

    public function setCellVAlignment($row, $col, $alignment)
    {
        $this->mCellVAlignments[$row][$col]=$alignment;
    }

    public function setCellHAlignment($row, $col, $alignment)
    {
        $this->mCellHAlignments[$row][$col]=$alignment;
    }
	
    public function setCellSpacing($spacing)
    {
            $this->mCellSpacing=$spacing;
    }

    public function setCellPadding($spacing)
    {
            $this->mCellPadding=$spacing;
    }

    public function setBorder($border)
    {
            $this->mBorder=$border;
    }
	
    public function render()
    {
        

        $bgcolor='';
        if(isset($this->mBGColor))
        {
            $bgcolor='bgcolor="'.$this->mBGColor.'"';
        }



        $format='margin:0px;'; // border-color:silver; border-style:solid;border-collapse: collapse;'; //position:absolute; top:0px;
        if($this->mCentered)
        {
            //$format.='width:1000px; margin-right:auto; margin-left:auto;';
            $format.='width:100.0%; height:100.0% margin: 0; padding: 0;'; //margin-right:auto; margin-left:auto;
        }

        
        if(strcmp($this->mBGImg, '')!=0)
        {
            $format.='background-image:url('.$this->mBGImg.');';
        }
        
        echo '<table '.$bgcolor.' id="' . $this->mId . '" border="'. $this->mBorder . '" cellpadding="' . $this->mCellPadding . '" cellspacing="' . $this->mCellSpacing . '" style="'.$format.'">';

        for($i=0; $i<$this->mRowCount; ++$i)
        {
            echo '<tr>';
            for($j=0; $j<$this->mColCount; ++$j)
            {
                $obj=$this->mGrid[$i][$j];
                if(isset($obj))
                {
                    $colspan=$this->mColSpans[$i][$j];
                    $rowspan=$this->mRowSpans[$i][$j];

                    $format='';
                    if($colspan!=1)
                    {
                        $format=$format . ' colspan="' . $colspan . '"';
                    }

                    if($rowspan!=1)
                    {
                        $format=$format . ' rowspan="' . $rowspan . '"';
                    }

                    $valign=$this->mCellVAlignments[$i][$j];
                    $halign=$this->mCellHAlignments[$i][$j];
                    $color=$this->mCellBGColor[$i][$j];

                    if(isset($valign))
                    {
                        $format=$format . ' valign="' . $valign . '"';
                    }
                    if(isset($halign))
                    {
                        $format=$format . ' align="' . $halign . '"';
                    }
					
					$color2=$obj->getBGColor();
					if(isset($color2))
					{
						$color=$color2;
					}

                   
					
					$bg=$obj->getBackground();
					if(isset($bg) && $bg != "")
					{
						$format.=' style="background: url(' . $bg . ');"';
					}
					else if(isset($color))
                    {
                        $format.=' style="background-color:' . $color . '"';
                    }
                    
                    echo '<td' . $format . '>';
                    $obj->render_top_offset();
                    $obj->render();
                    $obj->render_bottom_offset();
                    echo '</td>';
                }
            }
            echo '</tr>';
        }
        echo '</table>';

        for($i=0; $i<$this->mRowCount; ++$i)
        {

            for($j=0; $j<$this->mColCount; ++$j)
            {
                $obj=$this->mGrid[$i][$j];
                if(isset($obj))
                {
                    $obj->render_hidden();
                }
            }
        }

    }

    public function add($obj, $row, $col, $colspan=1, $rowspan=1)
    {
        $this->mGrid[$row][$col]=$obj;
        $this->mColSpans[$row][$col]=$colspan;
        $this->mRowSpans[$row][$col]=$rowspan;
    }

    public function get($row, $col)
    {
        return $this->mGrid[$row][$col];
    }

    public function getRowCount()
    {
        return $this->mRowCount;
    }

    public function getColCount()
    {
        return $this->mColCount;
    }
}
?>

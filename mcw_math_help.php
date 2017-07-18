<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Application
 *
 * @author Xianshun
 */
require_once("MasterPage.php");
require_once('JQueryPanel_MathHelp.php');

class MathHelp extends MasterPage
{
    private $mMathHelp;
    public function __construct()
    {
        parent::__construct();

        $this->setTitle("Memetic Computing: Math Help");
        $this->mMathHelp=new JQueryPanel_MathHelp("MathHelp");
        
        $this->setLayout(new GridLayout("CurrentLayout", 5, 1));
        $this->add($this->mCaption, 0, 0);
        $this->add($this->mTopBanner, 1, 0);
        $this->add($this->mTopMenuBar, 2, 0);
        $this->add($this->mMathHelp, 3, 0);
        $this->add($this->mFooter, 4, 0);

        $this->mTopMenuBar->highlight('Math Help');
    }

    
}

$page=new MathHelp();
$page->render();
?>

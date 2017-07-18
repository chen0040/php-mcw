<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Publisher
 *
 * @author Xianshun
 */

 require_once('MasterPage.php');
 require_once('PublisherPanel.php');

class Publisher extends MasterPage{
    //put your code here
    private $mDBPublisher;
    public function __construct()
    {
        parent::__construct();

        $this->setTitle("Memetic Computing: Publisher");
        
        $this->setLayout(new GridLayout("PublisherLayout", 5, 1));
        $this->add($this->mCaption, 0, 0);
        $this->add($this->mTopBanner, 1, 0);
        $this->add($this->mTopMenuBar, 2, 0);

        $this->mTopMenuBar->highlight("Publisher");

        $this->mDBPublisher=new PublisherPanel("DBPublisher");
        $this->add($this->mDBPublisher, 3, 0);
        $this->mLayout->setCellVAlignment(3, 0, "top");
        $this->mLayout->setCellBGColor(3, 0, "white");

        $this->mTopMenuBar->highlight('Publish');


        $this->add($this->mFooter, 4, 0);
    }
}

$page=new Publisher();
$page->render();
?>

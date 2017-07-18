<?php
require_once("MasterPage.php");
require_once('ArticleAdvSearcher.php');

class AdvSearch extends MasterPage
{
    private $mAdvSearcher;

    public function __construct()
    {
        parent::__construct();

        $this->setTitle("Memetic Computing: Advanced Search");

        $this->mAdvSearcher=new ArticleAdvSearcher("ArticleAdvSearcher");
       
        $this->setLayout(new GridLayout("CurrentLayout", 5, 1));
        $this->add($this->mCaption, 0, 0);
        $this->add($this->mTopBanner, 1, 0);
        $this->add($this->mTopMenuBar, 2, 0);
        
        $this->add($this->mAdvSearcher, 3, 0);
        $this->add($this->mFooter, 4, 0);

        $this->mTopMenuBar->highlight('Advanced Search');
    }
}

$page=new AdvSearch();
$page->render();
?>

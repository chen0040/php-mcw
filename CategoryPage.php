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
require_once("CategorySearcher.php");
require_once("CategoryEntryPanel.php");

class CategoryPage extends MasterPage {
    //put your code here
    private $mForm;

    private $mCategoryType;
    private $mCategoryTypeName;
    
    public function __construct($category_type, $category_type_name, $menu_title)
    {
        parent::__construct();
        $this->mCategoryType=$category_type;
        $this->mCategoryTypeName=$category_type_name;

        $this->setLayout(new GridLayout("PageLayout", 5, 1));
        $this->add($this->mCaption, 0, 0);
        $this->add($this->mTopBanner, 1, 0);
        $this->add($this->mTopMenuBar, 2, 0);
       

        $this->setTitle("Memetic Computing: ".$this->mCategoryTypeName."s");

        $this->mForm=new CategorySearcher("ArticlesBy".ucfirst($this->mCategoryType), 'mcarticles', $this->mCategoryType, $this->mCategoryTypeName);
        $this->mForm->setEditable(true);

        $this->add($this->mForm, 3, 0);
        $this->mLayout->setCellVAlignment(3, 0, "top");

        $this->add($this->mFooter, 4, 0);

        $this->mTopMenuBar->highlight($menu_title);
    }
}
?>

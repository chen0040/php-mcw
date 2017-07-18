<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JQueryTree
 *
 * @author AB
 */
 require_once('MObj.php');

class JQueryTree extends MObj{
    //put your code here
    
    public function __construct($id)
    {
        parent::__construct($id);
        
        $this->addScript("treeview/jquery.treeview.pack.js");

        $this->addCSS("treeview/jquery.treeview.css");
        $this->addCSS("treeview/red-treeview.css");
    }

    public function render()
    {
        $content='
            <div id="'.$this->mId.'_sidetreecontrol"><a href="?#">Collapse All</a> | <a href="?#">Expand All</a></div>
            <ul id="' . $this->mId . '" style="width:220px">
		<li class="open"><span>Folder 1</span>
			<ul>
				<li><span>Item 1.1</span>
					<ul>
						<li><span>Item 1.1.1</span></li>
					</ul>
				</li>
				<li><span>Folder 2</span>
					<ul>
						<li><span>Subfolder 2.1</span>
							<ul id="folder21">
								<li><span>File 2.1.1</span></li>
								<li><span>File 2.1.2</span></li>
							</ul>
						</li>
						<li><span>Subfolder 2.2</span>
							<ul>
								<li><span>File 2.2.1</span></li>
								<li><span>File 2.2.2</span></li>
							</ul>
						</li>
					</ul>
				</li>
				<li class="closed"><span>Folder 3 (closed at start)</span>
					<ul>
						<li><span>File 3.1</span></li>
					</ul>
				</li>
				<li><span>File 4</span></li>
			</ul>
                    </li>
                </ul>
               
        ';
        echo $content;
    }

    public function render_header()
    {
        $content='
	$(document).ready(function(){
		$("#' . $this->mId . '").treeview({
                        collapsed: true,
                        animated: "medium",
                        control:"#'.$this->mId.'_sidetreecontrol",
                        persist: "location",
			toggle: function() {
				//console.log("%s was toggled.", $(this).find(">span").text());
			}
		});
                
	});
        ';
        echo $content;
    }
}
?>

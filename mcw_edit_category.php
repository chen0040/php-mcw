<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("MasterPage.php");

class CategoryEditor extends MasterPage
{
    private $mCategory;
    private $mCategoryId;
    
    public function __construct()
    {
        parent::__construct();

        $this->mCategory=$_GET['category'];
        $this->mCategoryId=$_GET['category_id'];

        $this->setTitle("Edit ".ucfirst($this->mCategory));

        $this->setBGColor('white');
    }

    public function render_header()
    {
        echo '<script type="text/javascript">';
        echo "$(function(){
            $.ajax({
            url: 'get_category.php?category_type=".$this->mCategory."',
            type: 'POST',
            data: 'id=".$this->mCategoryId."',
            dataType: 'json',
            success: function(data){
                $('input[name=\"detail\"]').val(data.detail);
                $('textarea#description').val(data.description);

                var parentid=data.parentid;
                $.post('get_categories.php?category_type=".$this->mCategory."', {id: ".$this->mCategoryId."},
                    function(data){
                        $('#parent_category').html(data);
                        $('#parent_category option[value='+parentid+']').attr('selected', true);
                    }, 'html');
                }
            });

             $('#btnUpdate').click(function(){
                    var pdetail=$('input[name=\"detail\"]').val();
                    var pdescription=$('textarea#description').val();
                    var pparentid=$('#parent_category').val();
                    //alert(hiddenId);
                    //alert(rowId);
                    //alert(pdescription);
                    //alert(pdetail);
                    //alert(pparentid);

                    //alert('id='+hiddenId+'&detail='+pdetail+'&description='+pdescription+'&parentid='+pparentid);


                    $.post(
                        'edit_category.php?category_type=".$this->mCategory."',
                        {id: ".$this->mCategoryId.", detail: pdetail, description: pdescription, parentid: pparentid},
                        function(rdata){
                            alert(rdata.msg);
                        }, 'json');
                     return false;
              });
        });
        ";
        echo '</script>';
    }

    public function render_body()
    {
        echo '<form id="simple_form" >';
        
        echo '<input type="hidden" name="category_id" id="category_id" value="'.$this->mCategoryId.'" />';
        echo '<input type="hidden" name="category" id="category" value="'.$this->mCategory.'" />';
        echo '<table>';
        echo '<tr><td>Detail: </td><td><input type="text" name="detail" id="detail" value="" size="80"/></td></tr>';
        echo '<tr><td>Category: </td><td><select id="parent_category"></select></td></tr>';
        echo '<tr><td colspan="2">Description: </td></tr>';
        echo '<tr><td colspan="2"><textarea rows="40" cols="90" name="description" id="description"></textarea></td></tr>';
        echo '<tr><td><button class="fg-button ui-state-default ui-corner-all" id="btnUpdate">Update</button>';
        echo '</td></tr>';
        echo '</table>';
        echo '</form>';
    }
}

$page=new CategoryEditor();
//if(!$page->hasLoginned())
//{
//    header("Location: index.php");
//}
$page->render();
?>
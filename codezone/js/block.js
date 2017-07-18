$(document).ready(function(){

    $('div .block').hover(function(){
        $(this).addClass('blockhover');
        }, function(){
        $(this).removeClass('blockhover');
        }
    );
    
    var oas_get_block_contents=function(block)
    {
            if(block.is('.blocktitle'))
            {
                block=block.parent();
            }
           
            return block.children().filter(function(){
                if($(this).is('.blockcontents'))
                {
                    return true;
                }
                else
                {
                    return false;
                }
            });
    }
    
    var oas_hide_block_contents=function(event)
    {
        if(!$(event.target).is('.blockcontents'))
        {
            oas_get_block_contents($(event.target)).addClass('hideblock');
        }
    };
    
    var oas_show_block_contents=function(event)
    {
        /*
        $('.blockcontents').filter(function(){
            if(!$(this).hasClass('hideblock'))
            {
                return true;
            }
            else
            {
                return false;
            }
        }).addClass('hideblock');
        */
        
        if(!$(event.target).is('.blockcontents'))
        {
           oas_get_block_contents($(event.target)).removeClass('hideblock');
        }
    }

    $('div .block').toggle(oas_hide_block_contents, oas_show_block_contents);
    $('div .block').trigger('click');    
});
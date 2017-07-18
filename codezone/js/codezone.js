$(document).ready(function(){
    $('#topmenu td').addClass('topmenuitem');
    $('#mainform').addClass('mainform');
    
    $('#topmenu td').hover(function(){
        $(this).addClass('topmenuitemhover');
        }, function(){
        $(this).removeClass('topmenuitemhover');
        }
    );
    
    $('#mnuSales').click(function(){
        window.location='Sales.aspx';
    });
    
    $('#mnuPurchases').click(function(){
        window.location='Purchases.aspx';
    });
    $('#mnuItemsAndInventory').click(function(){
        window.location='Inventory.aspx';
    });
    $('#mnuBanking').click(function(){
        window.location='Banking.aspx';
    });
    $('#mnuTimeBilling').click(function(){
        window.location='TimeBilling.aspx';
    });
    $('#mnuGeneralLedger').click(function(){
        window.location='GeneralLedger.aspx';
    });
    $('#mnuSetup').click(function(){
        window.location='Setup.aspx';
    });
    $('#mnuPayroll').click(function(){
        window.location='Payroll.aspx';
    });
    $('#mnuCardFile').click(function(){
        window.location='CardFile.aspx';
    });
    $('#mnuConfig').click(function(){
        window.location='Config.aspx';
    });
    $('#mnuHome').click(function(){
        window.location='Default.aspx';
    });
});
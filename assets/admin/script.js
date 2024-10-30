
jQuery( document ).ready(function() {
jQuery('.hubbed-tabs-nav-li').addClass("active");
//element.classList.add("active");
    jQuery('#hubbed_order_list_table').dataTable({

     "ordering": false

});
});
var Ele = document.getElementsByClassName("hubbed-tab-content");

for(var i = 0; i < Ele.length; i++){
    Ele[i].style.display = "none";
    if(i == 0){
        Ele[i].style.display = "block";
    }


}


// Click function
document.addEventListener('click', function (e) {
    if (e.target.classList.contains("hubbed-tabs-nav-li-a")) {

        var blocksection = e.target.getAttribute('redirectid');

        var element = document.getElementsByClassName("hubbed-tabs-nav-li");
        for(var i = 0; i < element.length; i++){
            element[i].classList.remove("active");
        }
        var Ele = document.getElementsByClassName("hubbed-tab-content");
        for(var i = 0; i < Ele.length; i++){
            Ele[i].style.display = "none";
        }

        e.target.parentNode.classList.add("active");
        // hubbedFadeIn(document.getElementById(blocksection),'1');
        document.getElementById(blocksection).style.display = "block";        
    }
}, false);



if (document.getElementById('hubbed_api_key') != null)
    {
        document.getElementById("hubbed_api_key").oninput = function(e)  {
            var val = document.getElementById("hubbed_api_key").value.trim();
            var letters = /^[a-zA-Z0-9-]+$/;
            var errMsgHolder = document.getElementById('hubbedAdminErrMsg');    
                    
            if(val != '')
            {
                if (val.match(letters))
                {
                    errMsgHolder.innerHTML= '';
                    document.getElementsByClassName("hubbed-submit")[0].style.display = "block";
                }
                else {
                    errMsgHolder.innerHTML= 'Key can only contain alphanumeric characters';
                    document.getElementsByClassName("hubbed-submit")[0].style.display = "none";
                }     
            }  
            else
            {
                errMsgHolder.innerHTML= '';
                document.getElementsByClassName("hubbed-submit")[0].style.display = "block";
            }  
    }
}

// Onclick plan details show.


jQuery('#hubbed-tabs-subscription').on("click", function() {
    var plzwait = '<div class="plz_wait_plan" style="text-align:center;">Please Wait.....</div>';
jQuery('.hubbed-plan-content').html(plzwait);
jQuery.ajax({
        type : "post",
        dataType : "json",
        url : hubbed_admin_ajax.ajaxurl,
        data : {action: "hubbed_plan_detail" },
        success: function(response) 
        {
            if (response.status_code == 200) {
                var interval = "";
                 var plan_interval = "";
                if (response.data.interval_unit == 'months') { var interval = "Monthly"; var plan_interval = "Month";}
        if (response.data.interval_unit == 'years') { var interval = "Yearly"; var plan_interval = "Year";}
            if (response.data.interval_unit == 'weeks') { var interval = "Weekly"; var plan_interval = "Week";}
    var html = "";
    html += '<div class="hubbed_plan_detail"> <div class="hubbed_plan_detail_heading">HUBBED Click & Collect subscription</div><table><thead></thead> <tbody>';
    html += '<tr> <td>Subscription name and code </td> <td> '+response.data.plan.name+' ('+response.data.plan.plan_code+') </td> </tr>';
    html += '<tr> <td>Subscription billing cycle  </td> <td> '+interval+' </td> </tr>';
    html += '<tr> <td>Subscription fee  </td> <td> $'+response.data.plan.price+'/'+plan_interval+' + $'+response.addon_price+'/transaction </td> </tr>';
    html += '<tr> <td>Next Billing Date </td> <td> '+response.next_billing_date+' </td> </tr>';

    html += '</tbody></table> </div>';
    jQuery('.hubbed-plan-content').html(html);
    }else{
            var plzwait = '<div class="plz_wait_plan" style="text-align:center;">There are no any data to fetch.</div>';
jQuery('.hubbed-plan-content').html(plzwait);
    }


         }   

    });

 });


// Generate Tracking No. and Consignment no.
/*
jQuery('#hubbed_order_fullfill_button').on("click", function() {
var hubbed_enable_order_id = jQuery('#hubbed_enable_order_id').val();
var hubbed_enable_order_tacking = jQuery('#hubbed_enable_order_tacking').val();
var hubbed_order_tacking_nonce = jQuery('#hubbed_order_tacking_nonce').val();

//if (!hubbed_enable_order_tacking) { alert("Please enter the unique Tracking No."); return false; }
jQuery('#hubbed_enable_order_tacking').attr('readonly', true);
jQuery('#hubbed_order_fullfill_button').attr('disabled', true);
jQuery('.plzwait-track').html('Please Wait.....');
jQuery.ajax({
        type : "post",
        dataType : "json",
        url : hubbed_admin_ajax.ajaxurl,
        data : {action: "hubbed_generate_consignment", hubbed_enable_order_id:hubbed_enable_order_id, hubbed_enable_order_tacking:hubbed_enable_order_tacking, hubbed_order_tacking_nonce:hubbed_order_tacking_nonce },
        success: function(response) 
        {            
        jQuery('#hubbed_enable_order_tacking').attr('readonly', false);
        jQuery('#hubbed_order_fullfill_button').attr('disabled', false); 
        jQuery('.plzwait-track').html('');  
            
            if (response.responseCode == 200) {
                jQuery('.hubbed-tracking-error').text("The Click & Collect order was sent to HUBBED successfully.").css('color','green');
                window.location.href=window.location.href;
            }else{
                jQuery('.hubbed-tracking-error').text("The Click & Collect order was not sent to HUBBED successfully.").css('color','red');
            }
            

         }   

    });

 });
*/

// Hubbed Price change
var hubbed_shipping_cost = jQuery('.hubbed-id-shipping-cost').val();
if (hubbed_shipping_cost == 0) {
    jQuery('.hubbed_feeyes').hide(500);
}else{
    jQuery('.hubbed_feeyes').show(500);
    jQuery('#hubbed_cutoff_price').attr('required',true)
    jQuery('#hubbed_lower_price').attr('required',true)
    jQuery('#hubbed_higher_price').attr('required',true)
}
jQuery('.hubbed-id-shipping-cost').on("change", function() { 
var hubbed_shipping_cost = jQuery('.hubbed-id-shipping-cost').val();
if (hubbed_shipping_cost == 0) {
    jQuery('.hubbed_feeyes').hide(500);
}else{
    jQuery('.hubbed_feeyes').show(500);
    jQuery('#hubbed_cutoff_price').attr('required',true)
    jQuery('#hubbed_lower_price').attr('required',true)
    jQuery('#hubbed_higher_price').attr('required',true)
}

})

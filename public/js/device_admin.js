/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    var site_url = jQuery.cookie('BaseUrl');
    
    $( "#taxi_nos" ).autocomplete({
      source: site_url+'/tunnel/admin/alltaxiesNo',minLength: 2,
      focus: function( event, ui ) {
          
        $( "#taxi_nos" ).val( ui.item.label );
      
        return false;
      },
      select: function( event, ui ) {
        $( "#taxi_nos" ).val( ui.item.label );
        $( "#taxi_nos_id" ).val( ui.item.value );
       
  
        return false;
      }
    


    
});

$('#ui-id-1').css('z-index','900000000000000000');
});



$(document).ready(function(){
     var site_url = jQuery.cookie('BaseUrl');
   $( "#taxi_nosUpdate" ).autocomplete({
      source: site_url+'/tunnel/admin/alltaxiesNo',minLength: 2,
      focus: function( event, ui ) {
          
        $( "#taxi_nosUpdate" ).val( ui.item.label );
      
        return false;
      },
      select: function( event, ui ) {
        $( "#taxi_nosUpdate" ).val( ui.item.label );
        $( "#taxi_nos_idUpdate" ).val( ui.item.value );
       
  
        return false;
      }
    


    
}); 
$('#ui-id-2').css('z-index','900000000000000000');
});

function addDevice(){
   
    $("#device_signup_form").bPopup();
    
}

function deviceRegAdmin(){
  var site_url = jQuery.cookie('BaseUrl');
    
            $.ajax({
                dataType:'json',
                url:site_url+'/tunnel/admin/device_add',
                type:'POST',
                data:$("#frm_details_device").serialize(),
                success:deviceSuccess,
                error:deviceError
                
                
            });  
}
function deviceSuccess(data){
    
   if(data.data == "001"){
      
       alert("Unit no. is already existed"); 
       
   }
    else if(data.data =="true"){
        alert("successfully submitted");
        location.reload();
    }
    else{
        
        alert("sorry");
    }
}
function deviceError(){
    
    console.log(error);
}

var valueid = [];
//function delete_device(){
 
$("#delete_device").click(function(){
     $(':checkbox:checked').each(function(i){
          valueid[i] = $(this).val();
          
         
        });
         
     $("#device_form212").bPopup(); 
});


$("#conformation").click(function(){
  // alert(val);
   // alert("yes");
     delete_driver(valueid);
});
$("#not_conformation").click(function(){
    
    location.reload();
});



function driver_pay_upload(){		
		var status = 0; 
		var error =0;
		$(".error_label").hide();
		if(status==0){
			var error = 0;
			var mob = /^[0-9]{10,15}$/;
			var num = /^[0-9]*$/;
			var nam = /^[a-zA-Z ]{2,}$/;
			var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})*$/;
			
			$('#deposit_date').each(function(){
				if(($(this).val() == '')){
					$(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required Field.</label></div>');
				error=error+1;
				return false;
				} 
			});
			$('#cph_main_drpPaymentMode').each(function(){
				if(($(this).val() == '0')){
					$(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required Field.</label></div>');
				error=error+1;
				return false;
				} 
			});
			
						
			$('#cph_main_upldChq').each(function(){
				if(($(this).val() == '')){
					$(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required Field.</label></div>');
					error=error+1;
					return false;
				}
			});	
			
			$('#cph_main_drpBankAccounts').each(function(){
				if(($(this).val() == '')){
					$(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required Field.</label></div>');
					error=error+1;
					return false;
				}
			});	
			
			
			
			
			$('#amount').each(function(){
				if(($(this).val() == '')){
					$(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required Field.</label></div>');
					error=error+1;
					return false;
				}
			});
			$('#deposit_bank').each(function(){
				if(($(this).val() == '')){
					$(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required Field.</label></div>');
					error=error+1;
					return false;
				}
			});	
			
			
			
			$('#deposited_branch').each(function(){
				if(($(this).val() == '')){
					$(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required Field.</label></div>');
					error=error+1;
					return false;
				}
			});
				
		}
		if(error==0){
		    var deposit_date = $("#deposit_date").val();
			var cph_main_drpPaymentMode= $("#cph_main_drpPaymentMode").val();
			var fileupload= $("#cph_main_upldChq").val();
			var trans= $("#cph_main_txttransactionno").val();
			var chequeno= $("#cph_main_txtChqNo").val();
			var partner_bank = $("#cph_main_drpBankAccounts").val();
			var amount = $("#amount").val();
			var deposit_bank =  $("#deposit_bank").val();
			var deposited_branch= $("#deposited_branch").val();
			var remark = $("#remark").val();
			var id_tbluser =  $("#id_tbluser").val();
			var id_tbluserinfo= $("#id_tbluserinfo").val();
			var id_tbldriver= $("#id_tbldriver").val();
			//alert(id_tbldriver);
			//var strst = site_url.substring(0, site_url.length - 7);
	       // var url = strst+'/tunnel/menu/DriverPaymentUpload';
			var url = 'http://localhost/hello42/tunnel/menu/DriverPaymentUpload';
			$.ajax({
				type: 'POST',
				url: url,
				dataType: "json",
				data : {
					deposit_date:deposit_date,
					cph_main_drpPaymentMode:cph_main_drpPaymentMode,
					fileupload:fileupload,
					trans:trans,
					chequeno:chequeno,
					partner_bank:partner_bank,
					amount:amount,
					deposit_bank:deposit_bank,
					deposited_branch:deposited_branch,
					id_tbluser:id_tbluser,
					id_tbluserinfo:id_tbluserinfo,
					id_tbldriver:id_tbldriver,
					remark:remark},		 
				success: function(data){	
					if(data.status == "true"){
					//$('.fa-spin').remove();
					alert('Payment uploaded');	
					//$('#101').attr("onclick","bookingLocalSubmit(this.id);");
					//location.reload();
					//console.log(data.distance);
				}else{
					alert(data.msg);
				}
				},
				error: function(error){
					console.log(error);			
				}
			});
		}
		/* if(error==0){
			$('#101').html($('#101').html()+'<i class="fa fa-spinner fa-spin" style="font-size:1.5em;float:right;"></i>');
			$('#101').attr("onclick","return false;");
			if($.cookie('token') == null){
				var localname = $("#localName").val();
				var localmobile = $("#localMobile").val();
				var localemail = $("#localEmail").val();
			}else{
				var token = $.cookie('token');
			}
			var time = $('input[name=booklocaltime]:checked').val();
			var cartypes = $('input[name=cartypeslocal]:checked').val();
			var bookingType= id;
			$('#form2').valid();
			var localCabIn = $("#localCabIn").val();
			var localCabFor= $("#localCabFor").val();
			var localNationality= $("#localNationality").val();
			var localAdults= $("#localAdults").val();
			var localChilds= $("#localChilds").val();
			var localLuggages= $("#localLuggages").val();
			var pickuparea = $("#localPickupArea").val();
			var pickuplocation =  $("#localPickupArea_value").val();
			var localAddress= $("#localAddress").val();
			
			var site_url = jQuery.cookie('BaseUrl');	
			var url = site_url+'/tunnel/bookingCabFare/localBooking';
			$.ajax({
				type: 'POST',
				url: url,
				dataType: "json",
				data : {
					token:token,
					DeviceType:'WEB',
					name:localname,
					phone:localmobile,
					email:localemail,
					picktime:time,
					carTypes:cartypes,
					bookingType:bookingType,
					//PromotionName:'Sessional',
					CouponName:local_coupan_code},		 
				success: function(data){	
					if(data.Status == 'true'){
					$('.fa-spin').remove();
					alert("\"Thanks for choosing Hello42! Your bkg ref "+data.ref+" for "+data.pickupTime+" Total apx cost Rs. "+data.price+"\"");	
					$('#101').attr("onclick","bookingLocalSubmit(this.id);");
					//location.reload();
					//console.log(data.distance);
				}else{
					alert(data.msg);
				}
				},
				error: function(error){
					console.log(error);			
				}
			});
		} */
	}



function delete_driver(valueid){
     var site_url = jQuery.cookie('BaseUrl');
    var device = valueid.toString();
  alert(device);
  
              
                
                $.ajax({
                    dataType:'json',
                    url:site_url+'/tunnel/Admin/deleteDevice',
                    type:'POST',
                    data:{deviceIds:device},
                    success:function(data){
                       if(data.status=="true"){
                           alert("yes");
                           location.reload();
                       }
                       else{
                           alert("no");
                           location.reload();
                       }
                    },
                    error:function(error){
                       console.log(error);
                    }
                });
   
    
}

$("#driver_payment").on('click','.approve_driver_payment1',function(){
  // alert("sdfsd");
  //var i=$("#device_id").val();
 // alert(i);
 
    
      var id = $(this).attr("data");
	  
	  //var site_url = jQuery.cookie('BaseUrl');
    
    
                 $.ajax({
                    dataType:'json',
                    //url:site_url+'/tunnel/Admin/showDriverPayment1',
					url:baseurl+'/tunnel/Admin/showDriverPayment1',
                    type:'POST',
                    data:{driver_pay_id:id},
                    success:function(data){
						if(data.status == "true"){
                        //alert(data.data.deposit_date);
						$("#DPaymentid").val(data.data.id);
						$("#payment_ref_no").val(data.data.payment_ref_no);
						$("#drivid").val(data.data.driver_id);
						$("#user_account").val(data.data.user_account);
                        $("#deposit_date").val(data.data.deposit_date);
                        $("#transaction_mode").val(data.data.transaction_mode);
                        $("#partner_bank").val(data.data.partner_bank);
                        $("#amount").val(data.data.amount);
                        $("#deposit_bank").val(data.data.deposit_bank);
                        $("#deposited_branch").val(data.data.deposited_branch);
						$("#remark").val(data.data.remark);
						$("#driver_approval_form").bPopup();
						}else{
					   alert('Not Approved by Account Section');
				       }
                      
                    },
                    error:function(error){
                       console.log(error);
                    }
                });
    
	  
	  //showDriverPaymentDetail1(id);
     //alert(id);
       // deviceProfile(id); 
      
  
      
});

function showDriverPaymentDetail1(id){
    
     //alert(id);
     var site_url = jQuery.cookie('BaseUrl');
    
    
                 $.ajax({
                    dataType:'json',
                    url:site_url+'/tunnel/Admin/showDriverPayment1',
                    type:'POST',
                    data:{driver_pay_id:id},
                    success:function(data){
						if(data.status == "true"){
                        //alert(data.data.deposit_date);
						$("#DPaymentid").val(data.data.id);
						$("#user_account").val(data.data.user_account);
                        $("#deposit_date").val(data.data.deposit_date);
                        $("#transaction_mode").val(data.data.transaction_mode);
                        $("#partner_bank").val(data.data.partner_bank);
                        $("#amount").val(data.data.amount);
                        $("#deposit_bank").val(data.data.deposit_bank);
                        $("#deposited_branch").val(data.data.deposited_branch);
						$("#remark").val(data.data.remark);
						}else{
					   alert('Not Approved by Account Section');
				       }
                      
                    },
                    error:function(error){
                       console.log(error);
                    }
                });
    
    
    
}

$("#driver_payment").on('click','.approve_driver_payment',function(){
  // alert("sdfsd");
  //var i=$("#device_id").val();
 // alert(i);
 
    
      var id = $(this).attr("data");
     //alert(id);
       // deviceProfile(id); 
      $("#driver_approval_form").bPopup();
  
      showDriverPaymentDetail(id);
});

function showDriverPaymentDetail(id){
    
     //alert(id);
     //var site_url = jQuery.cookie('BaseUrl');
		//alert(site_url);
    
                 $.ajax({
                    dataType:'json',
                    url:baseurl+'/tunnel/Admin/showDriverPayment',
                    type:'POST',
                    data:{driver_pay_id:id},
                    success:function(data){
                        //alert(data.data.deposit_date);
						$("#DPaymentid").val(data.data.id);
						$("#payment_ref_no").val(data.data.payment_ref_no);
                        $("#deposit_date").val(data.data.deposit_date);
                        $("#transaction_mode").val(data.data.transaction_mode);
                        $("#partner_bank").val(data.data.partner_bank);
                        $("#amount").val(data.data.amount);
                        $("#deposit_bank").val(data.data.deposit_bank);
                        $("#deposited_branch").val(data.data.deposited_branch);
						$("#remark").val(data.data.remark);
                      
                    },
                    error:function(error){
                       console.log(error);
                    }
                });
    
    
    
}

function driverpaymentaudit(){
	
    //var site_url = jQuery.cookie('BaseUrl');
    
                   $.ajax({
                    dataType:'json',
                    //url:site_url+'/tunnel/Admin/payment_audit',
					url:baseurl+'/tunnel/Admin/payment_audit',
                    type:'POST',
                    data:$("#frm_audit_driver").serialize(),
                    success:function(data){
                        if(data.response == "true"){
                       alert("succesfully updated");
                       location.reload();
                        }
                        else{
                           alert("Not updated"); 
                           location.reload();
                        }
                    },
                    error:function(error){
                       console.log(error);
                    }
                });
    
}
function driverpaymentapproved(){
    //var site_url = jQuery.cookie('BaseUrl');
    
                   $.ajax({
                    dataType:'json',
                    //url:site_url+'/tunnel/Admin/payment_approved',
					url:baseurl+'/tunnel/Admin/payment_approved',
                    type:'POST',
                    data:$("#frm_update_driver").serialize(),
                    success:function(data){
                        if(data.response == "true"){
                       alert("succesfully updated");
                       location.reload();
                        }
                        else{
                           alert("Not updated"); 
                           location.reload();
                        }
                    },
                    error:function(error){
                       console.log(error);
                    }
                });
    
}

$("#devicemanagement").on('click','.deviceedit',function(){
  // alert("sdfsd");
  //var i=$("#device_id").val();
 // alert(i);
 
    
      var id = $(this).attr("data");
     //alert(id);
       // deviceProfile(id); 
      $("#device_update_form").bPopup();
  
      editDevice(id);
});

function editDevice(id){
    
    $("#update_unit").show();
    $("#update_device").show();
    $("#edit_device").hide();
     //var site_url = jQuery.cookie('BaseUrl');
    
    
                 $.ajax({
                    dataType:'json',
                    //url:site_url+'/tunnel/Admin/editDevice',
					url:baseurl+'/tunnel/Admin/editDevice',
                    type:'POST',
                    data:{deviceIds:id},
                    success:function(data){
                        
                        $(".unitnos").val(data.data.unit);
                        $("#device_pdate").val(data.data.pdate);
                        $("#device_adate").val(data.data.adate);
                        $("#device_sim").val(data.data.sim);
                        $("#device_portno").val(data.data.port);
                        $("#taxi_nosUpdate").val(data.data.taxi);
                      
                    },
                    error:function(error){
                       console.log(error);
                    }
                });
    
    
    
}

function deviceupdateAdmin(){
    //var site_url = jQuery.cookie('BaseUrl');
    
                   $.ajax({
                    dataType:'json',
                    //url:site_url+'/tunnel/Admin/updateDevice',
					url:baseurl+'/tunnel/Admin/updateDevice',
                    type:'POST',
                    data:$("#frm_update_device").serialize(),
                    success:function(data){
                        if(data.response == "true"){
                       alert("succesfully updated");
                       location.reload();
                        }
                        else{
                           alert("Not updated"); 
                           location.reload();
                        }
                    },
                    error:function(error){
                       console.log(error);
                    }
                });
    
}
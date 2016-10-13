/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 $(document).ready(function(){
	 
	/* $("#credit_date" ).datepicker({
          
        changeMonth: true,
        changeYear: true,
        yearRange: '1920:2010',
        dateFormat : 'dd-mm-yy',
        defaultDate: new Date(1985, 00, 01)
    });*/
     
      $("#driverBirth" ).datepicker({
          
        changeMonth: true,
        changeYear: true,
        yearRange: '1920:2010',
        dateFormat : 'dd-mm-yy',
        defaultDate: new Date(1985, 00, 01)
    });
	
	$("#permit_date,#license_validity,#insurance_validity" ).datepicker({          
        changeMonth: true,
        changeYear: true,
		currentYear : new Date().getFullYear(),
        yearRange: 'currentYear:2035',
        dateFormat : 'yy-mm-dd',
        defaultDate: new Date(2015, 00, 01),
		minDate: 0
    });
  
 var site_url = jQuery.cookie('BaseUrl');
    //var strst = site_url.substring(0, site_url.length - 7);
    $( "#group_search_admin" ).autocomplete({
      source: global_url+'/tunnel/Admin/allgroups',minLength: 2,
      focus: function( event, ui ) {
        $( "#group_search_admin" ).val( ui.item.label );
        return false;
      },
      select: function( event, ui ) {
        $( "#group_search_admin" ).val( ui.item.label );
        $( "#group_search_admin_value" ).val( ui.item.value );
       
 
        return false;
      }
    });
    
 });

   
 
 var valueid = [];
$("#delete_drivers_data").click(function(){
  
       $("#sendSMSForm").bPopup({
            follow: [false, false], //x, y
            position: [150, 200] //x, y
        });
      
       
   
}); 

$("#conformation").click(function(){
  // alert(val);
   // alert("yes");
     delete_driver(valueid);
});
$("#not_conformation").click(function(){
    
    location.reload();
});

function delete_driver(valueid){
    var site_url = jQuery.cookie('BaseUrl');
    var drivers = valueid.toString();
  //alert(drivers);
  
              
                
                $.ajax({
                    dataType:'json',
                    url:global_url+'/tunnel/Admin/sendSmsDriver',
                    type:'POST',
                    data:{driverIds:drivers},
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


//$("#backbutton").click(function(){
 function backbutton(){  
    //$("#page-wrapper").show();
    // $("#driverprofile").hide();
	location.reload();
}    
//});

//$(function(){


function driverProfile(id){

 var uid=id;
 
		//var site_url = jQuery.cookie('BaseUrl'); 
		var strst = global_url.substring(0, global_url.length - 7);
		//alert(strst);
         $.ajax({
                    dataType:'json',
                    url:global_url+'/tunnel/Admin/profileDriver',
                    type:'POST',
                    data:{driverProfileId:uid},
                    success:successProfile,
                    error:errorProfile

                });

}

//});
function successProfile(data){
	
    $(".idsdriver").val(data.data.idsDriver);
   $("#profile_update").attr('id','profile_update_'+data.data.idsDriver);
   $("#vehicle_update").attr('id','vehicle_update_'+data.data.idsDriver);
    $("#unique_id").val(data.data.Unique_ref_Key);
    $("#driver_firstName").val(data.data.name);
    $("#driver_name").html(data.data.name);
   //alert(ss);
   $("#driver_lastName").val(data.data.lastname);
   $("#driver_father").val(data.data.fathername);
   $("#externalRef").val(data.data.refn);
   $("#city_driver").val(data.data.city);
   $("#createdDate").text(data.data.createDate);
   $("#email_driver").val(data.data.email);
   $("#driver_add").val(data.data.address);
    $("#driver_addofc").val(data.data.ofcadds);
   $("#driver_phone").val(data.data.phone);
   $("#driver_username").val(data.data.userName);
   $("#driverBirth").val(data.data.db);
   $("#driverDefaullag").val('Hindi');
   $("#driverGender").val(data.data.gender);
   //alert(data.data.ac_nonac);
   $("#ac_nonac").val(data.data.ac_nonac)
   $("#vehicleid").text(data.data.vehicleName);
   $("#driver_account").text(data.data.account);
   $("#driver_fleetNo").val(data.data.fleet);
   
   $("#driver_panNos").val(data.data.panNo);
   $("#driver_licence").val(data.data.licence);
   $("#driver_vehicleNo").val(data.data.vehicle);
   $("#driver_lstate").val(data.data.lstate);
   $("#driver_modely").val(data.data.modelV);
   $("#driver_zone").val(data.data.zone);
   $("#driver_route").val(data.data.route);
   $("#driver_pref").val(data.data.prflocation);
   $("#driver_weekoff").val(data.data.weekoff);
    $("#driver_weekoffday").val(data.data.weekoffday);
   $("#driveimei").val(data.data.iemi);
   $("#drivergps").val(data.data.gps);
   $("#cabName").val(data.data.cabName);
   $("#CabIgnitionType").val(data.data.CabIgnitionType);
   $("#operational_company_name").val(data.data.operational_company_name);
   $("#vehicle_owner_name").val(data.data.vehicle_owner_name);
   $("#vehicle_owner_cnt_no").val(data.data.vehicle_owner_cnt_no);
   $("#vehicle_owner_alt_cnt_no").val(data.data.vehicle_owner_alt_cnt_no);
   $("#license_validity").val(data.data.license_validity);
   $("#insurance_validity").val(data.data.insurance_validity);
   $("#com_pincode").val(data.data.company_pincode);
   $("#rc_no").val(data.data.rc_no);
   
   if(data.data.prof_img != ''){
       $("#profile_pic_update").attr("src",global_url+'/'+data.data.prof_img);
   }else{
       $("#profile_pic_update").attr("src",global_url+'/public/image/default_user.png');
       //alert(empty);
   }
   
   var pic = global_url+'/public/image/default_user.png';
   //alert(global_url);
   
   if(data.data.veh_img != ''){
       $("#vehicle_pic_update").attr("src",global_url+'/'+data.data.veh_img);
   }else{
       $("#vehicle_pic_update").attr("src",global_url+'/public/image/vehicle.png');
       //alert(empty);
   }
   
   //$("#vehicle_pic").attr("src",global_url+'/'+data.data.veh_img);
   $("#driver_name_head").html(data.data.name);
   $(".samount").val(data.data.amount);
   $("#emailVerify").val(data.data.verify);
   
   
   
   var emailv =$("#emailVerify").val();
   //alert(emailv);
   if(emailv == "1"){
   $('.hello42').prop('checked',true);
   $('.hello42').prop('checked',true);
   }
   
   $("#phoneverify").val(data.data.verify);
   
 var write = data.data.write;
 var write_lang = write.split(',');
 $("#write1").val(write_lang[0]);
 $("#write2").val(write_lang[1]);
 $("#write3").val(write_lang[2]);
 $("#write4").val(write_lang[3]);
 
 var speak = data.data.speak;
 var speak_lang = speak.split(',');
 $("#speak1").val(speak_lang[0]);
 $("#speak2").val(speak_lang[1]);
 $("#speak3").val(speak_lang[2]);
 $("#speak4").val(speak_lang[3]);
 
 
 var timing = data.data.timing;
 var timing_driver = timing.split(",");
 $("#time1").val(timing_driver[0]);
 $("#time2").val(timing_driver[1]);
 $("#time3").val(timing_driver[2]);
 
 ////// Code for Driver Status Starts Here /////
 
var driverstatusIdArr=data.data.status_id;
var driverstatusArr=data.data.status;
var lengthDriverStatusArr = driverstatusIdArr.length;
for(var i=0; i< lengthDriverStatusArr; i++){
//alert(i);
if(i==0){
var driverType="activedriver";
}else if(i==1){
var driverType="holddriver";
}else if(i==2){
var driverType="blackdriver";
}else if(i==3){
var driverType="inactivedriver";
}else if(i==4){
var driverType="unapproveddriver";
}
$(".driverStatus").append('<div class="input-group"><input type="radio" id="isActive" name="driver_isActive" value="'+driverstatusIdArr[i]+'"  required="" class="form-control inputsize '+driverType+'"><label class="radio-inline" style="margin-top:-4px;cursor:default;">'+driverstatusArr[i]+'</label></div>');
}

var driver_status = data.data.active;
if(driver_status == 1){
  $('.activedriver').prop('checked', true);
  $('#driver_length').val('Active');
}
else if(driver_status == 2){
 $('.holddriver').prop('checked', true);
 $('#driver_length').val('On Hold'); 
}
else if(driver_status == 3){
  $('.blackdriver').prop('checked', true);
  $('#driver_length').val('Black Listed'); 
}
else if(driver_status == 4){
  $('.inactivedriver').prop('checked', true);
  $('#driver_length').val('Inactive'); 
}
else{
  $('.unapproveddriver').prop('checked', true);
  $('#driver_length').val('Unapproved'); 
}


////// Code for Driver Status Ends Here ///// 

////// Code for Driver Duty Type Starts Here /////

var masterpackagerefArr=data.data.master_package_ref;
var PackageIdArr=data.data.Package_Id;
var lengthPackageArr = masterpackagerefArr.length;
for(var i=0; i< lengthPackageArr; i++){
	//alert(masterpackagerefArr[i]);

$(".duty_type").append('<div class="input-group"><label class="checkbox-inline" style="margin-top:-4px;cursor:default;">'+masterpackagerefArr[i]+'</label><input type="checkbox" id="driverLocal" name="drAir[]" value="'+PackageIdArr[i]+'" class="form-control inputsize driver'+PackageIdArr[i]+'"></div>');
}
$(".duty_type").append('<div class="input-group"><label class="checkbox-inline" style="margin-top:-4px;cursor:default;">All</label><input type="checkbox" id="driverAll" name="driverAll" value="101,102,103,104,105" class="form-control inputsize"></div>');

/* var duty_pref = data.data.dutyprf;
 var pref_duty = duty_pref.split(',');
 for(i=0;i<=pref_duty.length;i++){
	 //alert(pref_duty[i]);
   $(".driver"+pref_duty[i]).prop('checked',true);  
 }*/
 
 var duty_pref = data.data.dutyprf;
 var pref_duty = duty_pref.split(',');
 if(pref_duty=="101,102,103,104,105"){
	 $("#driverAll").prop('checked',true);  
 }
 for(j=0;j<=pref_duty.length;j++){
   $(".driver"+pref_duty[j]).prop('checked',true);  
 }
 
 
////// Code for Driver Duty Type Ends Here /////

 var cash = data.data.cash;
 var driver_cash = cash.split(",");
 if(driver_cash=="1,2"){
	 $("#driverBoth").prop('checked',true);  
 }
 for(i=0;i<=driver_cash.length;i++){
   $(".driverCash"+driver_cash[i]).prop('checked',true);  
 } 
     
     
     
     var data_specs = data.data.eyetest;
	 
	
	 
	 // switch(data_specs){
			
		// case "1":
		// $("#specs_active").prop('checked', true);
		// break;
		// case "2":
		// $("#specs_inactive").prop('checked', true);
		// break;
	 // }
	 if(data_specs == 1){
		 $('.specs_active').prop('checked', true); 
	 }else{
		$('.specs_inactive').prop('checked', true); 
	 }
	 
	 var data_vehicle_owned = data.data.vehicle_owned;
	 
	 if(data_vehicle_owned == "vehicle_owned"){
		 $('.vehicle_owned').prop('checked', true); 
		 $("#vehicle_owned_attach").css("display","none");
	 }else{
		$('.vehicle_attached').prop('checked', true);
		$("#vehicle_owned_attach").css("display","block");
	 }
     
 
    if(data.data.status_driver == "1"){
       var txt='Online';
       
      $("#driverStatus").val(txt);
       
   }
   else{
       var txt2 ="Offline";
       $("#driverStatus").val(txt2);
   }
   $("#driver_signup_comts").text(data.data.signup_comts);
   $("#driver_internal_comts").text(data.data.internal_comts);
   if(data.data.vendorName != null){
    $("#vName").text(data.data.vendorName); 
    
   }
   else if(data.data.vendorName == null){
     var name = "No Boss";
     $("#vName").text(name);    
   }
   
   
   if(data.data.photo == "1"){
       
      $(".photo").prop('checked',true); 
   }
   
   
        
}
function errorProfile(error){
     
    console.log(error);
    
}

function update_driver(){
    var site_url = jQuery.cookie('BaseUrl');
    var prof_img = $("#driver_form_details").val();
	var chkdata = $("input[name='drAir\\[\\]']:checked").map(function(){return $(this).val();}).get();
	var chkdata1 = $("input[name='dPaymentType\\[\\]']:checked").map(function(){return $(this).val();}).get();
        $.ajax({
                type:'POST',
                dataType:'json',
                url:global_url+'/tunnel/Admin/profileUpdateDriver',
                data:$("#driver_form_details").serialize()+"&chkdata="+chkdata+"&chkdata1="+chkdata1,
                success:updateSucces,
                error:udateError                
        });
    
  
    function updateSucces(data){
        if(data.status =="true"){
            alert("Update completed");
            location.reload();
        }
        
        else{
            alert("Update not completed");
        }
    }
    function udateError(error){
        
       console.log(error); 
    }
}
function usernameCheck(){
  var site_url = jQuery.cookie('BaseUrl');
   var uname = $("#driver_username").val();
   //var dids = $("#driverIds");
   
   
                    $.ajax({
                type:'POST',
                dataType:'json',
                url:global_url+'/tunnel/Admin/driverunameCheck',
                data:{userName:uname},
                success:checkSucces,
                error:checkError                
        });
   
   function checkSucces(data){
       //alert("success");
       
       if(data.response == 002){
         alert("new username"); 
         return false;
       }
       else if(data.response == 001){
           alert("username alreday existed");
           return false;
       }
   }
   function checkError(error){
     console.log(error);  
   }          
}


$("#emailVerify").click(function(){
   var one =1; 
   var zero = 0;
   var atLeastOneIsChecked = $('#emailVerify :checkbox:checked').length < 0;
   if(atLeastOneIsChecked == false){
  $("#emailVerify").val(one);
  }
  else{
      $("#emailVerify").val(zero); 
  }
});



function signupDriver(){
	    
    $("#driver_signup_form").bPopup({
            follow: [false, false], //x, y
            position: [200, 100] //x, y
			
        });
		
	 
}

function driverRegAdmin(){
    
var site_url = jQuery.cookie('BaseUrl'); 
                    $.ajax({
                        url:global_url+'/tunnel/admin/driversignup',
                        type: "post",
		         dataType: "json",
                        data:$("#frm_details").serialize(),
                        success: function(data) {
                            //alert(data);
                            //console.log(data);
							if(data.status =="unSuccess")
							{
							alert("Your Email/Mobile no. is already registered");
							}
                           else{ 
					window.open(global_url+'/admin/driver',"_self");
					}
                        },
        error: function(error){
		
				console.log(error);	
				
		}
           }); 
}



function bankaccount(uid){
	//var uid=id;
  var site_url = jQuery.cookie('BaseUrl');      
        
         $.ajax({
                    dataType:'json',
                    url:global_url+'/tunnel/Admin/driverbank',
                    type:'POST',
                    data:{driverProfileId:uid},
                    success:successBankProfile,
                    error:errorbankProfile

                });

}
function successBankProfile(data){
	
	$("#bankName").val(data.data.bankname);
	$("#bankAccHolder").val(data.data.accholder);
	$("#bankAdds").val(data.data.bankadds);
	$("#bankAccNo").val(data.data.bankAccNo);
	$("#bankNeft").val(data.data.bankneft);
	$("#bankIbn").val(data.data.bankibn);

}
function errorbankProfile(data){
console.log(error);
}


var pickerOpts = {dateFormat: 'dd-mm-yy',minDate:(-10), maxDate:(365)};
 
 $(document).ready(function(){
 $(function() {
	 //debugger;
	 var currentDate = new Date();  
	$( "#deposit_date" ).datepicker(pickerOpts);
	$( "#credit_date" ).datepicker(pickerOpts);
	
	 $("#deposit_date").datepicker("setDate", currentDate);
   
   // $( ".date" ).datepicker();
    
  }); 
 var site_url = jQuery.cookie('BaseUrl');
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
			
			var paymentType=$("#cph_main_drpPaymentMode").val();
			
			$('#deposit_date').each(function(){
				if(($(this).val() == '')){
					$(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required Field.</label></div>');
				error=error+1;
				return false;
				} 
			});
			/*$('#credit_date').each(function(){
				if(($(this).val() == '')){
					$(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required Field.</label></div>');
				error=error+1;
				return false;
				} 
			});*/
			
			$('#cph_main_drpPaymentMode').each(function(){
				if(($(this).val() == '0')){
					$(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required Field.</label></div>');
				error=error+1;
				return false;
				} 
			});
			
			if(paymentType=='Cheque' || paymentType=='Demand Draft' || paymentType=='Cash Deposit'){		
			$('#cph_main_upldChq').each(function(){
				if(($(this).val() == '')){
					$(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required Field.</label></div>');
					error=error+1;
					return false;
				}
			});
			}
			var trans= $("#cph_main_txttransactionno").val();
			if(trans=="Online Transfer"){
			$('#cph_main_txttransactionno').each(function(){
				if(($(this).val() == '')){
					$(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required Field.</label></div>');
					error=error+1;
					return false;
				}
			});	
			}
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
			//var fileupload= $("#cph_main_upldChq").val();
			var fileupload= $("#file_upldChq").val();
			var trans= $("#cph_main_txttransactionno").val();
			var chequeno= $("#cph_main_txtChqNo").val();
			var partner_bank = $("#cph_main_drpBankAccounts").val();
			var amount = $("#amount").val();
			var deposit_bank =  $("#deposit_bank").val();
			var deposited_branch= $("#deposited_branch").val();
			var remark = $("#remark").val();
			var id_tbldriver =  $("#driverIds").val();
			//alert(id_tbldriver);
			//var id_tbluserinfo= $("#id_tbluserinfo").val();
			//var id_tbldriver= $("#id_tbldriver").val();
			//alert(id_tbldriver);
			//var strst = site_url.substring(0, site_url.length - 7);
	       // var url = strst+'/tunnel/menu/DriverPaymentUpload';
			//var url = 'http://localhost/hello42/tunnel/menu/DriverPaymentUpload';
			$.ajax({
				type: 'POST',
				url:global_url+'/tunnel/Admin/DriverPaymentUpload',
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
					id_tbldriver:id_tbldriver,
					remark:remark},		 
				success: function(data){	
					if(data.status == "true"){
					alert('Payment uploaded');	
					location.reload();
					//alert(global_url);
					//window.open(global_url+'/admin/driver',"_self");
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
	}




function bankUpdate(uid){
var site_url = jQuery.cookie('BaseUrl');  
  
        
         $.ajax({
                    dataType:'json',
                    url:global_url+'/tunnel/Admin/driverbankupdate',
                    type:'POST',
                    data:$("#bank_frm_details").serialize()+'&'+$.param({ 'driverProfileId': uid }),
                    success:successBankupdate,
                    error:errorbankupdate

                });

}
function successBankupdate(data){

	if(data.data == 1){
		alert("Update SuccessFully");
		location.reload();
		
	}else{
	alert("Not Updated ");
	}

}
function errorbankupdate(){
console.log(error);
}


var dids;
function driverCer(id){

 dids=id;
		var site_url = jQuery.cookie('BaseUrl');  
 
        $.ajax({
                    dataType:'json',
                   url:global_url+'/tunnel/Admin/drivercertificates',
                   type:'POST',
                    data:{driverProfileId: id },
                   success:successCertificates,
                   error:errorCerificates

                });



}

/////////// Code to Show Driver Cerificates on the cerificates tab starts here////




function successCertificates(data){
  // alert("qwe"); 
   
   if(data.data.resident != ''){
       $("#residential_proof").attr("src",global_url+'/public/'+data.data.resident);
	   $(".residential_proof").val(data.data.resident);
   }
   
   if(data.data.office != ''){
       $("#office_proof").attr("src",global_url+'/public/'+data.data.office);
	   $(".office_proof").val(data.data.office);
   }
   
   if(data.data.pan != ''){
       $("#pan_card").attr("src",global_url+'/public/'+data.data.pan);
	   $(".pan_card").val(data.data.pan);
   }
   
   if(data.data.vehicle != ''){
       $("#vehicle_image").attr("src",global_url+'/public/'+data.data.vehicle);
	   $(".vehicle_image").val(data.data.vehicle);
   }
   
   if(data.data.license != ''){
       $("#licence_img").attr("src",global_url+'/public/'+data.data.license);
	   $(".licence_img").val(data.data.license);
   }
   
   if(data.data.police != ''){
       $("#police_verification").attr("src",global_url+'/public/'+data.data.police);
	   $(".police_verification").val(data.data.police);
   }
   
   if(data.data.audit != ''){
       $("#audit_verification").attr("src",global_url+'/public/'+data.data.audit);
	   $(".audit_verification").val(data.data.audit);
   }
   
   if(data.data.rc_proof != ''){
       $("#rcproof_verification").attr("src",global_url+'/public/'+data.data.rc_proof);
	   $(".rcproof_verification").val(data.data.rc_proof);
   }
   
        
}
function errorCerificates(error){
     
    console.log(error);
    
}





////////// Code to show Driver Certificates on the cerificates tab Ends Here/////

function successfileDownload(data){

 var res=data.data.resident;
 if(res ==""){
     alert("You have not uploaded any certificates");
 }else{

    var site_url = jQuery.cookie('BaseUrl');  
	//var site_url = site_url.substring(0, site_url.length - 7);
    var image_url = global_url+'/'+res;
    window.open(image_url,'_blank');
  }
}
function errorfileDownload(){

	console.log(error);

}

$("#driverRproof").click(function(e){
	e.preventDefault();  //stop the browser from following
	//successfileDownload();
        
        var site_url = jQuery.cookie('BaseUrl');  
		
        
         $.ajax({
                    dataType:'json',
                    url:global_url+'/tunnel/Admin/driverdownloadfiles',
                    type:'POST',
                    data:{driverProfileId: dids },
                    success:successfileDownload,
                    error:errorfileDownload

                });
});


$("#driverOproof").click(function(e){
	e.preventDefault();  //stop the browser from following
	//successfileDownload();
        
        var site_url = jQuery.cookie('BaseUrl');  
  
        
         $.ajax({
                    dataType:'json',
                    url:global_url+'/tunnel/Admin/driverdownloadfiles',
                    type:'POST',
                    data:{driverProfileId: dids },
                    success:successfileDownloadOfc,
                    error:errorfileDownload

                });
        
        
        
	

});
function successfileDownloadOfc(data){

 var res=data.data.office;
 if(res ==""){
     alert("You have not uploaded any certificates");
 }else{

 //   var site_url = jQuery.cookie('BaseUrl');  
    var image_url = global_url+'/'+res;
    window.open(image_url,'_blank');
 }
}

$("#driverPanproof").click(function(e){
	e.preventDefault();  //stop the browser from following
	//successfileDownload();
        
     //   var site_url = jQuery.cookie('BaseUrl');  
  
        
         $.ajax({
                    dataType:'json',
                    url:global_url+'/tunnel/Admin/driverdownloadfiles',
                    type:'POST',
                    data:{driverProfileId: dids },
                    success:successfileDownloadPan,
                    error:errorfileDownload

                });
        
        
        
	

});
function successfileDownloadPan(data){

 var res=data.data.pan;
    if(res ==""){
     alert("You have not uploaded any certificates");
 }else{
   // var site_url = jQuery.cookie('BaseUrl');  
    var image_url = global_url+'/'+res;
    window.open(image_url,'_blank');
}
}


$("#driverVproof").click(function(e){
	e.preventDefault();  //stop the browser from following
	//successfileDownload();
        
       // var site_url = jQuery.cookie('BaseUrl');  
  
        
         $.ajax({
                    dataType:'json',
                    url:global_url+'/tunnel/Admin/driverdownloadfiles',
                    type:'POST',
                    data:{driverProfileId: dids },
                    success:successfileDownloadvic,
                    error:errorfileDownload

                });
        
        
        
	

});
function successfileDownloadvic(data){

 var res=data.data.vehicle;
   if(res ==""){
     alert("You have not uploaded any certificates");
 }else{

    var site_url = jQuery.cookie('BaseUrl');  
    var image_url = global_url+'/'+res;
    window.open(image_url,'_blank');
}
}


$("#driverbadgeproof").click(function(e){
	e.preventDefault();  //stop the browser from following
	//successfileDownload();
        
      //  var site_url = jQuery.cookie('BaseUrl');  
  
        
         $.ajax({
                    dataType:'json',
                    url:global_url+'/tunnel/Admin/driverdownloadfiles',
                    type:'POST',
                    data:{driverProfileId: dids },
                    success:successfileDownloadLic,
                    error:errorfileDownload

                });
        
        
        
	

});
function successfileDownloadLic(data){

 var res=data.data.license;
 if(res ==""){
     alert("You have not uploaded any certificates");
 }else{

   // var site_url = jQuery.cookie('BaseUrl');  
    var image_url = global_url+'/'+res;
    window.open(image_url,'_blank');
} 
}


$("#driverPoliceproof").click(function(e){
	e.preventDefault();  //stop the browser from following
	//successfileDownload();
        
        //var site_url = jQuery.cookie('BaseUrl');  
  
        
         $.ajax({
                    dataType:'json',
                    url:global_url+'/tunnel/Admin/driverdownloadfiles',
                    type:'POST',
                    data:{driverProfileId: dids },
                    success:successfileDownloadpol,
                    error:errorfileDownload

                });
        
        
        
	

});
function successfileDownloadpol(data){

 var res=data.data.police;
 if(res ==""){
     alert("You have not uploaded any certificates");
 }else{

    //var site_url = jQuery.cookie('BaseUrl');  
    var image_url = global_url+'/'+res;
    window.open(image_url,'_blank');
}
}

$("#driverAuiditproof").click(function(e){
	e.preventDefault();  //stop the browser from following
	//successfileDownload();
        
      //  var site_url = jQuery.cookie('BaseUrl');  
  
        
         $.ajax({
                    dataType:'json',
                    url:global_url+'/tunnel/Admin/driverdownloadfiles',
                    type:'POST',
                    data:{driverProfileId: dids },
                    success:successfileDownloadAut,
                    error:errorfileDownload

                });
});

function successfileDownloadAut(data){

 var res=data.data.audit;
 if(res ==""){
     alert("You have not uploaded any certificates");
 }else{

    var site_url = jQuery.cookie('BaseUrl');  
    var image_url = global_url+'/'+res;
    window.open(image_url,'_blank');
}
}

$("#driverRcproof").click(function(e){
	e.preventDefault();  //stop the browser from following
	//successfileDownload();
        
      //  var site_url = jQuery.cookie('BaseUrl');  
  
        
         $.ajax({
                    dataType:'json',
                    url:global_url+'/tunnel/Admin/driverdownloadfiles',
                    type:'POST',
                    data:{driverProfileId: dids },
                    success:successfileDownloadRcProof,
                    error:errorfileDownload

                });
});

function successfileDownloadRcProof(data){

 var res=data.data.rc_proof;
 if(res ==""){
     alert("You have not uploaded any certificates");
 }else{

    var site_url = jQuery.cookie('BaseUrl');  
    var image_url = global_url+'/'+res;
    window.open(image_url,'_blank');
}
}




$("#driverAllproof").click(function(e){
	e.preventDefault();  //stop the browser from following
	//successfileDownload();
        
      //  var site_url = jQuery.cookie('BaseUrl');  
  
        
         $.ajax({
                    dataType:'json',
                    url:global_url+'/tunnel/Admin/driverdownloadfiles',
                    type:'POST',
                    data:{driverProfileId: dids },
                    success:successfileDownloadAut,
                    error:errorfileDownload

                });
        
        
        
	

});
/*function successfileDownloadAut(data){

 var res=data.data.audit;

    var site_url = jQuery.cookie('BaseUrl');  
    var image_url = site_url+'/public/'+res;
    window.open(image_url,'_blank');
}*/

function updateCertificates(id){
    
   //  var site_url = jQuery.cookie('BaseUrl');  
  
        
         $.ajax({
                    dataType:'json',
                    url:global_url+'/tunnel/Admin/updateCer',
                    type:'POST',
                    data:$("#bank_frm_details_file").serialize()+'&'+$.param({ 'dids': id}),
                    success:successCerUpload,
                    error:errorCerUpload

                });
    
}
function successCerUpload(data){
    
    if(data.data == "success"){
        //alert(location);
        alert("File Uploaded");
       location.reload();
    }
    else{
        
        alert("Not test Updated");
    }
    
}
function errorCerUpload(error){
    console.log(error);
	location.reload();
    
}


//$("#vsearch").click(function(){
//    
//    
//    var lPlate = $("#lPlate").val();
//    var make = $("#vmake").val();
//    var model = $("#vmodel").val();
//    var extref = $("#vEref").val();
//    
//             var site_url = jQuery.cookie('BaseUrl');  
//  
//        
//         $.ajax({
//                    dataType:'json',
//                    url:site_url+'/tunnel/Admin/vsearchOfdriver',
//                    type:'POST',
//                    data:{vlPlate:lPlate,vmake:make,vmodel:model,vextref:extref},
//                    success:successvehicleSeach,
//                    error:errorvehicleSeach
//
//                });
//  
//});
//
//function successvehicleSeach(data){
//    
//      table=$('#driverSearch').dataTable( {
//        "ajax": '<?php echo $this->basePath();?>'+"/tunnel/admin/driver_list",
// "deferRender": true
//    } );
//    
//}
//function errorvehicleSeach(error){
//    
//    
//    console.log(error);
//}


function driver_img(id){
   
   // var photo_verify = $("#photo_verify").val();
   //$("#photo_verify").val();
    //if("#photo_verify")
            // var site_url = jQuery.cookie('BaseUrl');  
  
        
         $.ajax({
                    dataType:'json',
                    url:global_url+'/tunnel/Admin/driverPhotoVerification',
                    type:'POST',
                    data:$("#driver_img_photo").serialize()+'&'+$.param({ 'dids': id}),
                    success:successverify,
                    error:errorverify

                });
  
  }

function successverify(data){
    
     if(data.data == true){
         alert("Updated");
         location.reload();
     }
     else{
         
         alert("Not Updated");
     }
    
}
	function errorverify(error){
		
		
		console.log(error);
	}
   
   function bookingfeature()
   {
		//var site_url = jQuery.cookie('BaseUrl'); 
		
		$.ajax({
                    //dataType:'json',
                    url:global_url+'/tunnel/Admin/Getfeature',
                    type:'POST',
					cache: false,
					dataType:"json"
                    //data:{driver_features:data,dids:id},
                    //success:successgetfeature,
                   // error:errorfeatures

                }).done(function( html ) {
	var k=1;
	var driverFeature = '';
    for(var i=0;i<html.data.length;i++)
	{
		//alert(html.data[i].featurename);
		
		driverFeature +='<option value="'+html.data[i].id+'" >'+html.data[i].featurename+'</option>';
		
		/* driverFeature = (html.data[i].status == 0) ?'Inactive':'Active';
		$('#exampleVehicle tbody').append('<tr><td>'+k++ +'</td><td>'+html.data[i].DriverId+'</td><td>'+html.data[i].DriverName+'</td><td>'+html.data[i].CabRegistrationNumber+'</td><td>'+html.data[i].CabType+'</td><td>'+html.data[i].CabName+'</td><td>'+html.data[i].CabManufacturer+'</td><td>'+html.data[i].CabModel+'</td><td>'+statusVehicle+'</td><td>'+html.data[i].RatePerKm+'</td><td>'+html.data[i].RatePerHr+'</td><td>'+html.data[i].MinimumCharge+'</td><td><button id="vehicle_'+html.data[i].id+'" class="btn btn-primary manageVehicleFair">Manage Fare</button></td></tr>'); */  
	}
	
	$("#s1").html(driverFeature);
  }).fail(function (error){
	alert('error');
	console.log(error);
		
 });
		
   }
   
   function successgetfeature(data)
   {
	  var hi = data.data.id;
        //alert(hi); 
   }
   
   
   function featuresUpdate(id){
       var features = $("#s1").val();
      
       var data = features.join();
     
       //alert(data);
       
      // var site_url = jQuery.cookie('BaseUrl');  
  
        
         $.ajax({
                    dataType:'json',
                    url:global_url+'/tunnel/Admin/feature_update',
                    type:'POST',
                    data:{driver_features:data,dids:id},
                    success:successfeatures,
                    error:errorfeatures

                });
       
   }
   function successfeatures(data){
       //alert("dsdddd");
       if(data.data == true){
           
           alert("update");
           location.reload();
       }else{
           
           alert("Not Updated");
       }
       
   }
   function errorfeatures(error){
       console.log(error);
   }
    
    function feature_driver(id){
        // var site_url = jQuery.cookie('BaseUrl');
		//alert(global_url);
         $.ajax({
                    dataType:'json',
                    url:global_url+'/tunnel/Admin/profileDriver',
                    type:'POST',
                    data:{driverProfileId:id},
                    success:successfeaturesselect,
                    error:errorfeaturesselect

                });
        
    }
    
    function successfeaturesselect(data){
        
        
        var hi = data.data.feature;
        //alert(hi);
        var newdata = hi.split(',');
        var length1 = newdata.length ;
        //alert(length1);
        //var foo = [];
        
        
        if(length1 == 0){
            
            setTimeout(
  function() 
  {
    $("#s1").dropdownchecklist({icon: {}, width: 550 });
  }, 200);
        }else{
        
        
        
        for(var i=0; i< length1 ; i++){
       
    $('#s1').dropdownchecklist('destroy');
		 $('#s1 option').each(function() {
			//$(this).attr('selected',false);
			$("select option[value="+i+"]").attr("selected","selected");
		 });
		$("#s1").dropdownchecklist({icon: {}, width: 550 });
		  }
  
        }    
  
    }
    function errorfeaturesselect(error){
        
        console.log(error);
    }
    
    
    function adddrivergroup(id){
       var gId =  $("#group_search_admin_value").val();
        var site_url = jQuery.cookie('BaseUrl');
         $.ajax({
                    dataType:'json',
                    url:global_url+'/tunnel/Admin/driverEditGroup',
                    type:'POST',
                    data:{drivergroupid:gId,dids:id},
                    success:successdriverEditGroup,
                    error:errordriverEditGroup

                });
        
        
    }
    
    function successdriverEditGroup(data){
        if(data.data == true){
            
            alert("Group Changed");
            location.reload();
        }else{
            alert("Group Not Changed");
        }
        
        
        
    }
function errordriverEditGroup(error){
    console.log(error);
}



function driverGroupname(id){
    var site_url = jQuery.cookie('BaseUrl');
         $.ajax({
                    dataType:'json',
                    url:global_url+'/tunnel/Admin/driverGroupName',
                    type:'POST',
                    data:{dids:id},
                    success:successdriverGroupName,
                    error:errordriverGroupName

                });
    
}
function successdriverGroupName(data){
    
    if(data.data == null){
        var no = 'No Driver Group Join';
        $("#driver_group_default").html(no);
         $("#remove_default_drivers").hide();
    }
    else{
        $("#driver_group_default").html(data.data);
       
    }
}
function errordriverGroupName(error){
    console.log(error);
    
}








function successdriverRemoveGroup(data){
    if(data.data == true){
        
        alert("Removed Successfully");
        location.reload();
    }
    else{
        
        alert("not removed");
    }
    
}
function errordriverRemoveGroup(error){
    console.log(error);
}
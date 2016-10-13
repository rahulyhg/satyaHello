/**
 * @author project manager
 */ 
/*=================front page validation   ===================*/


function localValidate(){
     var nam = /^[a-zA-Z]{2,}$/;
    $('.nam_val').each(function(){
                if(mob.test($(this).val())==false)
              {
                 $(this).parent().append('<div class="error_label"><label class="col-mg-5 col-lg-5"></label><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Input a Valid Number</label></div></div>');
                  error=error+1;
                  
              }
          });  
}

/*=================front page validation   ===================*/



$(document).click('.dateofdriver',function(){
    
//alert("dgasdjhdjaldks");    
    $( ".dateofdriver" ).datepicker({
          
        changeMonth: true,
        changeYear: true,
        yearRange: '1920:2010',
        dateFormat : 'yy-mm-dd',
        defaultDate: new Date(1985, 00, 01)
    });


});
$(document).ready(function(){
     $(function() {
         

        
      $("#dreDateOfBirth" ).datepicker({
          
        changeMonth: true,
        changeYear: true,
        yearRange: '1920:2010',
        dateFormat : 'yy-mm-dd',
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
     $("#driverBirth" ).datepicker({

         changeMonth: true,
         changeYear: true,
         yearRange: '1920:2010',
         dateFormat : 'yy-mm-dd',
         defaultDate: new Date(1985, 00, 01)
     });
     
$("#auditclick").on('click',function(e){
    
    e.preventDefault();

    //alert("download");
    var site_url = jQuery.cookie('BaseUrl');  
    window.location.href = site_url+'/public/audit/Audit Sheet.xlsx';
    
})  ; 


//     var date = new Date();
//$("#dreDateOfBirth").datepicker({
//    changeMonth: true,
//    changeYear: true,
//    yearRange: '1900:-20',
//    defaultDate : '-18y'    
//});
     });
     
    
    
});
 $(".dateofdriver").click(function(){
        alert("sdasdsa"); 
     });
     
     function date(){
         
         alert("sddddddd");
     }
//var base_urlSer = "<?php echo $this->basePath(); ?>";

//function driver_submit(){
////var base_url = "<?php echo $this->basePath(); ?>";	
////var base_url = window.location.host+'/hello42';	
// 
//var newarray=$('input[name]').serialize();
//
//var site_url = jQuery.cookie('BaseUrl'); 
//var driverName =$("#driver_name").val();
//var driver_email =$("#driver_email").val();
//var city =	$("#city").val();
//var dirveNo = $("#driver_no").val();
//var dirveNo = $("#driver_no").val();
//var driverAno = $("#driver_Altno").val();
//var driveAd = $("#driver_adds").val();
//var driverOads = $("#driver_oadds").val();
//var driverPan =	$("#driver_pan").val();
//var driverFleet = $("#driver_fleet").val();
//var driverAmount = $("#driver_amount1").val();
//var driverTypeVech = $("#driverVecType").val();
//var drivermake = $("#driverMakeVec").val();
//var driverModel = $("#driverModel").val();
//var driverBadge = $("#driverBadge").val();
//var driverSpec = $("#driverSpec").val();
//var driverNoSoec = $("#driverNoSoec").val();
//var driverZone = $("#driverZone").val();
//var driverAmount = $("#driverAmount").val();
//var driverAir =	$("#driverAir").val();
//var driverOut =	$("#driverOut").val();
//var driverLocal = $("#driverLocal").val();
//var driverPoint = $("#driverPoint").val();
//var driverAll =  $("#driverAll").val();
//var driverCash =  $("#driverCash").val();
//var driverCredit =  $("#driverCredit").val();
//var driverBoth =  $("#driverBoth").val();
//
//var userEmailId = jQuery.cookie('Email'); 
//var userPass = jQuery.cookie('FirstPass'); 
//var userSecPass = jQuery.cookie('SecondPass'); 
//var userRole = jQuery.cookie('UsersRole'); 
//
//var url = 'http://localhost/hello42/tunnel/menu/driversignup';
//	
//	$.ajax({
//        type: 'POST',
//		//contentType: 'application/json',
//        url: url,
//        dataType: "json",
//		data : {newarray:newarray,dName : driverName,driver_email:driver_email,city:city,dNo : dirveNo,dAno : driverAno,dAd : driveAd,dofc : driverOads,dpan : driverPan,dfleet : driverFleet,driverBadge: driverBadge,
//				damount : driverAmount,driverTypeVech:driverTypeVech,drivermake:drivermake,dAir : driverAir,dout : driverOut,dLocal : driverLocal,dPoint : driverPoint,dall : driverAll,dcash : driverCash,
//				dCredit : driverCredit,dboth : driverBoth,userEmailIdLog:userEmailId,userpassword:userPass,userRoles:userRole,key:API_KEY},		 
//        success: function(data){
//		
//					alert(data.new);	
//		                   
//					//verificationMesg(mobno);
//					//var base_url = "<?php echo $this->basePath(); ?>";
//					//window.open(site_url+'/verify',"_self");
//					},
//        error: function(error){
//		
//				console.log(error);	
//				
//		}
//	});
//	
//	
//}	

function setInfo(i, e) {
	$('#x').val(e.x1);
	$('#y').val(e.y1);
	$('#w').val(e.width);
	$('#h').val(e.height);
}

function not_mandat(){
var i=0;
var j=0;
	$('.not_mandat').each(function(){
	if($(this).val()!="")
	{
	i=i+1;
	//alert($(this).val());
	}
	j=j+1;
	
	});
	//alert(i);
	//alert(j);
    if((i==j) || (i==0))
	{
    return true;
	}else{
            
	
	
	return false;
	}
	
}
       
       
       
       
	//$(document).ready(function(){
       
         
	// prepare instant preview
	

	// implement imgAreaSelect plug in (http://odyniec.net/projects/imgareaselect/)
	
             
      
            
            
//         $("#frm_details").on("submit", function(event) {


 var driverName;
 var driverEmail;
 
 

	function driverReg(){
		var d = new Date();
		var n = d.getFullYear();    //get current year		
		$('.error_label').hide();		
		//if(!$("#frm_details").valid()){
			//return false;
			//alert('unsuccess');
		//}else{
			// $('#uploadImage').uploadify('upload');
			//$("#frm_details").valid();
			$('#driver_no2').val($('#driver_no').val());
			$('#driver_email2').val($('#driver_email').val());
			// var length = jQuery.cookie('FormLength'); 
			//alert(form_no);
			if(form_no > 1){
				$("#driverSignform").bPopup();
				driverName = $("#driver_name").val();
				$("#vender_name").val(driverName);
				driverEmail = $("#driver_email2").val();
				$("#vender_email").val(driverEmail);
				var vGroup = $("#vender_group").val();
				// alert(length);
				//length="";
			}else{
				$("#registerbtnloader").show();
				var base_url = "<?php echo $this->basePath(); ?>";
				var site_url = jQuery.cookie('BaseUrl'); 				
				var strst = site_url.substring(0, site_url.length - 7);
				var chkdata = $("input[name='drAir\\[\\]']:checked").map(function(){return $(this).val();}).get();
				var url = strst+'/tunnel/menu/driversignup';
				$.ajax({
					url: url,
					type: "post",
					dataType: "json",
					data:$("#frm_details").serialize()+'&'+$.param({ 'key': API_KEY })+'&'+"&chkdata="+chkdata,
					success: function(data){
						//console.log(data);
						if(data.Status =="Unsuccess"){
							if(data.error == "0" || data.error == "2"){
								$("#registerbtnloader").hide();
								$('.modal-body').html('<p>Vehicle is already registered</p>');
							}else if(data.error == "1"){
								$("#registerbtnloader").hide();
								$('.modal-body').html('<p>Your Email/Mobile no. is already registered</p>');
							}
							$("#myModal").modal('show');
							$("#registerbtnloader").hide();
						}else{ 
							$("#registerbtnloader").hide();
			//alert("You have Successfully Registered. Please Check your email to activate your account.");
alert("Dear Vendor, you have Successfully Submitted Your Details. Please Check your email to verify your details to process further or contact admin@hello42cab.com or call 011-42424242.");			
							  window.open(strst+'/verify',"_self");
						}
					},
						error: function(error){
						console.log(error);	
					}
				}); 
			}
		//}
		// alert($(this).serialize());
		//});
	}
 
 
 
 
 
 
  // $("#frm_details").on("submit", function(event) {
/*function save_drivers(){   
      $("#vendorregisterbtn").show();
        // var length = jQuery.cookie('FormLength'); 
//var length=1;
        var vGroup = $("#vender_group").val();       
                 var base_url = "<?php echo $this->basePath(); ?>";
				 var site_url = jQuery.cookie('BaseUrl'); 				
				  var strst = site_url.substring(0, site_url.length - 7);
				var url = strst+'/tunnel/menu/driversignup';
                    $.ajax({
                        url: url,
                        type: "post",
		               dataType: "json",
                        data:$("#frm_details").serialize()+'&'+$.param({ 'key': API_KEY,'vName' : driverName,'vEmail':driverEmail,'vGroup':vGroup,'flength':form_no}),
                        success: function(data) {
                            //alert(data);
                            //console.log(data);
                            if(data.Status =="Unsuccess")
                            {
                                if(data.error == "0" || data.error == "2")
                                {
                                    $("#registerbtnloader").hide();
                                    $('.modal-body').html('<p>Vehicle is already registered</p>');
                                    $("#vendorregisterbtn").hide();
                                }
                                else if(data.error == "1")
                                {
                                    $("#registerbtnloader").hide();
                                    $('.modal-body').html('<p>Your Email/Mobile no. is already registered</p>');
                                    $("#vendorregisterbtn").hide();
                                }
                                $("#myModal").modal('show');
                                $("#registerbtnloader").hide();
                                $(".b-close").click();
                            }
                            else if(data.vdata == 'false')
                            {
                                $("#registerbtnloader").hide();
                                $('.modal-body').html('<p>Vendor name is alreay existed</p>');
                                $("#myModal").modal('show');
                                $("#vendorregisterbtn").hide();
                                $(".b-close").click();
                            }
                            else
                            { 
								alert("Successfully Registered in Line:356");
                                $("#vendorregisterbtn").hide();
                                jQuery.removeCookie('FormLength');
                                window.open(strst+'/verify',"_self");
                            }
                        },
                     error: function(error){
		
				console.log(error);	
				
		}
                   });  
 
 
 
  // });
 
 }*/
 
 
 
 
	//alert("yahoo");
	 //not_mandat();
        //var p = $("#uploadPreview");

	// prepare instant preview
       // alert(jQuery.cookie('moboremail'))
	
		var roleOfUser = jQuery.cookie('UsersRole');
                var moboremail= jQuery.cookie('moboremail');
		if(roleOfUser == "1" || roleOfUser == "2")
		{
                    
                    if(roleOfUser==1)
                    {
                        
                        $('#user_head').show();
                    }
                    if(roleOfUser==2)
                    {
                        $('#corp_head').show();
                    }
                  if(moboremail==1)
                   {
                       $('#mob_no').val(jQuery.cookie('Email'));
                       $("#mob_no").attr("disabled","true"); 
					   $('#co_mob_no').val(jQuery.cookie('Email'));
                       $("#co_mob_no").attr("disabled","true"); 
                   }
                   if(moboremail==2)
                   {
                       $('#email_id').val(jQuery.cookie('Email'));
                       $("#email_id").attr("disabled","true");
					   $('#co_email_id').val(jQuery.cookie('Email'));
                       $("#co_email_id").attr("disabled","true");
                   }
		$("#vendor_head").hide();
		$("#userRoleDriver").hide();
		$("#userRoleNormal").show();
		}
		else
		{
                     
                    if(moboremail==1)
                   {
                       $('#driver_no').val(jQuery.cookie('Email'));
					   $('#driver_no2').val(jQuery.cookie('Email'));
                       $("#driver_no").attr("disabled","true"); 
					   $('#driver_passwd').val(jQuery.cookie('FirstPass'));
                   }
                   if(moboremail==2)
                   {
                       $('#driver_email').val(jQuery.cookie('Email'));
					   $('#driver_email2').val(jQuery.cookie('Email'));
                       $("#driver_email").attr("disabled","true"); 
					   $('#driver_passwd').val(jQuery.cookie('FirstPass'));
                   }
		$("#userRoleDriver").show();
		$("#userRoleNormal").hide();
		//alert("driver");
		}		
//******************************************User Registration Validation ****************************/	
         $("#reg_form2").validate({
             errorClass: 'error col-lg-offsets-5',
			rules: {
                           
                            userName: {
					required: true                                   
				      },
                            email_id: {
					required: true,
					email: true                                  
				      },
                            alt_email_id: {
					email: true,
                                        required:false 
				      },
                            user_a: {
					required: true
				      },                            
                            autocomplete: {
					required: true
				      },				
                            user_country: {
                                        required: true
                                      },
                            mob_no: {
                                        required: true,
                                        number: true,
                                        minlength: 10,
                                        maxlength: 10
                                      },
                            alternate_mob_no: {
					number:true,
					minlength:10,
                                        maxlength:10,
                                        required:false 
				      },
                            land_std: {
					number:true,
					minlength:3,
                                        maxlength:7,
                                        required:false 
				},          
                            land_no: {
					number:true,
					minlength:4,
                                        maxlength:9,
                                        required:false 
				},
                            pincode: {
					required: true,
                                        minlength:4,
                                        maxlength:8
				    }, 
                             user_company: {
					required:false                                   
				      },
                            user_email: {					
					email: true,
                                        required:false
				      },
                            user_alt_email: {
					email: true,
                                        required:false
				      },                            
                            user_mob: {                                        
                                        number: true,
                                        minlength: 10,
                                        maxlength: 10,                                        
                                        required:false
                                      },
                            cmp_lan_std: {
					number:true,
					minlength:3,
                                        maxlength:7,
                                        required:false 
				      },
                            user_land: {
                                        number:true,
					minlength:4,
                                        maxlength:9,
                                        required:false 
				},
                            user_pin: {					
                                        minlength:4,
                                        maxlength:6,
                                        required:false                                    
				    },
						speak11Us: {
					required: true                                   
				      },
					  userTerms: {
					required: true                                   
				      }
			},
			messages: {                            
                            userName: {
					required: "Please Enter your Name"                                  
				      },
                            email_id: {
					required: "Please Enter your Email",
					email: "Please Enter a valid Email"                                  
				      },
                            alt_email_id: {
					email: "Please Enter a valid Email" 
				      },
                            user_a: {
					required: "Please Enter Your address"
				      },                            
                            autocomplete: {
					required: "Please Enter your city"
				      },				
                            user_country: {
                                        required: "Please select your country"
                                      },
                            mob_no: {
                                        required: "Enter your Mobile no.",
                                        number: "Enter valid mobile no.",
                                        minlength: "Minimum length is 10",
                                        maxlength: "Maximum length is 10"
                                      },
                            alternate_mob_no: {
					number: "Enter valid Mobile no.",
                                        minlength: "Minimum length is 10",
                                        maxlength: "Maximum length is 10"
				      },
                            land_std: {
					number: "Invalid STD code",
                                        minlength: "Invalid STD code",
                                        maxlength: "Invalid STD code"
				      },          
                            land_no: {
					number: "Enter a valid phone no.",
                                         minlength: "Minimum length is 4",
                                        maxlength: "Maximum length is 9"
				},
                            pincode: {
					required: "Enter your area pincode",
                                         minlength: "Minimum length is 4",
                                        maxlength: "Maximum length is 8"
				    },
                            user_email: {					
					email: "Please Enter a valid Email" 
				      },
                            user_alt_email: {
					email: "Please Enter a valid Email" 
				      },                            
                            user_mob: {                                        
                                        number: "Enter valid Mobile No.",
                                        minlength: "Minimum length is 10",
                                        maxlength: "Maximum length is 10"
                                      },
                            cmp_lan_std: {
					number: "Invalid STD code",
                                        minlength: "Invalid STD code",
                                        maxlength: "Invalid STD code"
				      },
                            user_land: {
                                        number: "Enter valid Values",
                                        minlength: "Minimum length is 4",
                                        maxlength: "Maximum length is 9"
				},
                            user_pin: {					
                                        minlength: "Minimum length is 4",
                                        maxlength: "Maximum length is 6"
                                                                            
				    },
					speak11Us: {
					required: "Select Your language"                                  
				      },
					  userTerms: {
					required: "Accept Terms & conditions"                                  
				      }
			}
		});       
//*******************************End of User registration Validation*********************                
//*******************************Corporate User Validation** By Maninder*****************
  $("#reg_Corporate").validate({	
             errorClass: 'error col-lg-offsets-5',
			rules: {
                           
                            co_userName: {
					required: true                                   
				      },
                            co_email_id: {
					required: true,
					email: true                                  
				      },
                            co_alt_email_id: {
					email: true,
                                        required:false 
				      },
                            co_address: {
					required: true
				      },                            
                            co_city: {
					required: true
				      },				
                            co_user_country: {
                                        required: true
                                      },
                            co_mob_no: {
                                        required: true,
                                        number: true,
                                        minlength: 10,
                                        maxlength: 10
                                      },
                            co_alternate_mob_no: {
					number:true,
					minlength:10,
                                        maxlength:10,
                                        required:false
				      },
                            co_land_stdress: {
					number:true,
					minlength:4,
                                        maxlength:9,
                                        required:false
				},          
                            co_land_no: {
					number:true,
					minlength:4,
                                        maxlength:9,
                                        required:false 
				},
                            co_user_pin: {
					required: true,
                                        minlength:4,
                                        maxlength:8
				    }, 
                            co_user_company: {
					required: true                                
				      },
                            co_user_email: {					
					email: true,
                                        required: true
				      },
                            co_user_alt_email: {
					email: true,
                                        required:false
				      },
                            co_company_adds: {
					required: true
				      },                            
                            co_company_city: {
					required: true
				      },				
                            co_cmpany_country: {
                                        required: true
                                      },
                            co_user_mob: {                                        
                                        number: true,
                                        minlength: 10,
                                        maxlength: 10,                                        
                                        required: true
                                      },
                            co_land_std: {
					number:true,
					minlength:3,
                                        maxlength:7,
                                        required: true 
				      },
                            co_user_land: {
                                        number:true,
					minlength:4,
                                        maxlength:9,
                                        required: true
				},
                            co_company_pin: {					
                                        minlength:4,
                                        maxlength:8,
                                        required: true                                    
				    },
						speak11Co: {
					required: true                                   
				      },
					  co_userTerms: {
					required: true                                   
				      }          
			},
			messages: {                            
                            co_userName: {
					required: "Please Enter your name"                                  
				      },
                            co_email_id: {
					required: "Please Enter your Email",
					email: "Please Enter a valid Email"                                  
				      },
                            co_alt_email_id: {
					email: "Please Enter a valid Email" 
				      },
                            co_address: {
					required: "Please Enter Your address"
				      },                            
                            co_city: {
					required: "Please Enter your city"
				      },				
                            co_user_country: {
                                        required: "Please select your country"
                                      },
                            co_mob_no: {
                                        required: "Enter your Mobile no.",
                                        number: "Enter valid mobile no.",
                                        minlength: "Minimum length is 10",
                                        maxlength: "Maximum length is 10"
                                      },
                            co_alternate_mob_no: {
					                    number: "Enter valid Mobile no.",
                                        minlength: "Minimum length is 10",
                                        maxlength: "Maximum length is 10"
				      },
                            co_land_stdress: {
					number: "Invalid STD code",
                                        minlength: "Invalid STD code",
                                        maxlength: "Invalid STD code"
				      },          
                            co_land_no: {                                
					number: "Enter a valid phone no.",
                                         minlength: "Minimum length is 4",
                                        maxlength: "Maximum length is 9"
				},
                            co_user_pin: {
					required: "Enter your area pincode",
                                         minlength: "Minimum length is 4",
                                        maxlength: "Maximum length is 8"
				    },
                            co_user_company: {
					required: "Please Enter your name"                                  
				      },        
                            co_user_email: {					
					required: "Please Enter your Email",
					email: "Please Enter a valid Email" 
				      },
                            co_user_alt_email: {
					email: "Please Enter a valid Email" 
				      },
                            co_company_adds: {
					required: "Please Enter company name"
				      },                            
                            co_company_city: {
					required: "Please provide city name"
				      },				
                            co_cmpany_country: {
                                        required: "Please select country"
                                      },          
                            co_user_mob: {                                        
                                        required: "This field is Required",
                                        number: "Enter valid Mobile No.",
                                        minlength: "Minimum length is 10",
                                        maxlength: "Maximum length is 10"
                                      },
                            co_land_std: {
                                        required: "Enter your area STD code",
					                    number: "Invalid STD code",
                                        minlength: "Invalid STD code",
                                        maxlength: "Invalid STD code"
				      },
                            co_user_land: {
                                        required: "Enter your phone no",
                                        number: "Enter valid Values",
                                        minlength: "Minimum length is 4",
                                        maxlength: "Maximum length is 9"
				},
                            co_company_pin: {
                                        required: "Enter your area pincode",
                                         minlength: "Minimum length is 4",
                                        maxlength: "Maximum length is 8"
                                                                            
				    },
					speak11Co: {
					required: "Select Your language"                                  
				      },
					  co_userTerms: {
					required: "Accept Terms & conditions"                                  
				      }
			}
		});     
//*****************************end of Corporate user Validation*************************         
	$("#edit_profile").validate({
             errorClass: 'error col-lg-offsets-5',
              
			rules: {
				name: "required",
				mob_no: {
				required:true,
				number:true,
				minlength:10,
				maxlength:10
				},
				
			},
			messages: {
				firstname: "Please enter your firstname",
				lastname: "Please enter your lastname",
				username: {
					required: "Please enter a username",
					minlength: "Your username must consist of at least 2 characters"
				},
				password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 5 characters long"
				},
				confirm_password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 5 characters long",
					equalTo: "Please enter the same password as above"
				},
				email: "Please enter a valid email address",
				agree: "Please accept our policy"
			}
		});
	//});
        
        
 //************************************User Submit****************************       
function user_submit(){	
	var moboremail= jQuery.cookie('moboremail');
	$('#reg_form2').valid();
	if((not_mandat()!=false) && ($('#reg_form2').valid()!=false)){
		$(".fa-spinner").show();
		var name = $("#userName").val();
                var email = $("#email_id").val();
                var altemail = $("#alt_email_id").val();
                var address = $("#user_a").val();
                var city = $("#autocomplete").val();
                var country = $("#user_country").val();
				var state	= $("#user_state").val();
                var mob_isd = $("#mob_isd").val();
		        var mobno = $("#mob_no").val();
                var alternate_mob_no = $("#alternate_mob_no").val();
                var land_std = $("#land_std").val();
		        var lanNo = $("#land_no").val();
                var pincode = $("#pincode").val();
                    
		        var comName = $("#user_company").val();
                var userEmail = $("#user_email").val();
                var altUserEmail = $("#user_alt_email").val();
                var userAdds = $("#user_adds").val();
                var userCity = $("#autocomplete1").val();
                var cmpany_country = $("#cmpany_country").val();
                var cmp_mob_isd = $("#cmp_mob_isd").val();
		        var userMob = $("#user_mob").val();
                var cmp_lan_std = $("#cmp_lan_std").val();
				var userLan = $("#user_land").val();		
				var userPin = $("#user_pin").val();
				
				/* var user_pincode=$("#user_pincode").val();
				 
				  var user_company_country=$("#user_company_country").val();
				  var user_company_state=$("#user_company_state").val();
				  var user_company_city=$("#user_company_city").val(); 
				  var user_company_pincode=$("#user_company_pincode").val(); */
				
				
				
				var userEmailId = jQuery.cookie('Email'); 
				var userPass = jQuery.cookie('FirstPass'); 
				var userSecPass = jQuery.cookie('SecondPass'); 
				var userRole = jQuery.cookie('UsersRole'); 
				var base_url = "<?php echo $this->basePath(); ?>";
				var site_url = jQuery.cookie('BaseUrl'); 				
				var strst = site_url.substring(0, site_url.length - 7);
				var url = strst+'/tunnel/menu/signup';					
		$.ajax({
			type: 'POST',
			url: url,
			dataType: "json",
			data : {userName : name,mobileNo : mobno,landNo : lanNo,city : city,adds : address,emailId : email,altEmails : altemail,
                            companyName : comName,userMobile : userMob,UserLandline : userLan,userEmails : userEmail,altUserEmails : altUserEmail,
                            userAddress : userAdds,usersCity : userCity,userPins : userPin,userEmailIdLog : userEmailId,userpassword : userPass,
                            userRoles : userRole,image:$('#image_path0').val(),key:API_KEY,country :country,state:state,mob_isd :mob_isd,altmob_no :alternate_mob_no,
                            land_std :land_std,pincode :pincode,cmpany_country :cmpany_country,cmp_mob_isd :cmp_mob_isd,cmp_lan_std :cmp_lan_std,
							userLang:$("#speak11Us").val(),ReferralKey:''},		 
							/*user_pincode:user_pincode,user_company_country:user_company_country,user_company_state:user_company_state,user_company_city:user_company_city,
							user_company_pincode:user_company_pincode*/
			success: function(data){
				$(".fa-spinner").hide();
				if(data.Code=='002'){
					if(moboremail==1){
						$('.modal-body').html('<p>Email is already registered</p>');
					}
					if(moboremail==2){
						$('.modal-body').html('<p>Mobile is already registered</p>');
					}
					$("#myModal").modal('show');
				}else{
					alert("You have Successfully Registered. Please Check your email to activate your account.");															
					window.open(strst+'/verify',"_self");					
				}
			},
			error: function(error){
				console.log(error);	
			}
		});
	}else{
		return false;
	}
	
}

//*************************Corporate submit****Maninder***********************
function corporate_submit(){

	var moboremail= jQuery.cookie('moboremail');
	$('#reg_Corporate').valid();
	if($('#reg_Corporate').valid()!=false){		
		$(".fa-spinner").show();
		        var name = $("#co_userName").val();
                var email = $("#co_email_id").val();
                var altemail = $("#co_alt_email_id").val();
                var address = $("#co_address").val();
                var city = $("#co_city").val();
                var country = $("#co_user_country").val();
                var mob_isd = $("#co_mob_isd").val();
		        var mobno = $("#co_mob_no").val();
                var alternate_mob_no = $("#co_alternate_mob_no").val();
                var land_std = $("#co_land_stdress").val();
		        var lanNo = $("#co_land_no").val();
                var pincode = $("#co_user_pin").val();
                    
		        var comName = $("#co_user_company").val();
                var userEmail = $("#co_user_email").val();
                var altUserEmail = $("#co_user_alt_email").val();
                var userAdds = $("#co_company_adds").val();
                var userCity = $("#co_company_city").val();
                var cmpany_country = $("#co_cmpany_country").val();
                var cmp_mob_isd = $("#co_cmp_mob_isd").val();
		        var userMob = $("#co_user_mob").val();
                var cmp_lan_std = $("#co_land_std").val();
				var userLan = $("#co_user_land").val();		
				var userPin = $("#co_company_pin").val();
				var userEmailId = jQuery.cookie('Email'); 
				var userPass = jQuery.cookie('FirstPass'); 
				var userSecPass = jQuery.cookie('SecondPass'); 
				var userRole = jQuery.cookie('UsersRole');				 
				var base_url = "<?php echo $this->basePath(); ?>";
				var site_url = jQuery.cookie('BaseUrl');				
				var strsr = site_url.substring(0, site_url.length - 7);
				var url = strsr+'/tunnel/menu/signup';
		$.ajax({
			type: 'POST',
			url: url,
			dataType: "json",
			data : {userName : name,mobileNo : mobno,landNo : lanNo,city : city,adds : address,emailId : email,altEmails : altemail,
                            companyName : comName,userMobile : userMob,UserLandline : userLan,userEmails : userEmail,altUserEmails : altUserEmail,
                            userAddress : userAdds,usersCity : userCity,userPins : userPin,userEmailIdLog : userEmailId,userpassword : userPass,
                            userRoles : userRole,image:$('#image_path0').val(),key:API_KEY,country :country,mob_isd :mob_isd,altmob_no :alternate_mob_no,
                            land_std :land_std,pincode :pincode,cmpany_country :cmpany_country,cmp_mob_isd :cmp_mob_isd,cmp_lan_std :cmp_lan_std,
							userLang:$("#speak11Co").val(),ReferralKey:''},		 
			success: function(data){
				$(".fa-spinner").hide();
				if(data.Code=='002'){
					if(moboremail==1){
						$('.modal-body').html('<p>Email is already registered</p>');
					}
					if(moboremail==2){
						$('.modal-body').html('<p>Mobile is already registered</p>');
					}
					$("#myModal").modal('show');
				}else{
					alert("You have Successfully Registered. Please Check your email to activate your account.");															
					window.open(strsr+'/verify',"_self");				
				}
			},
			error: function(error){
				console.log(error);	
			}
		});
	}else{
		return false;
	}
	
} 
//***************************************end corporate submit function****************
 
    function howw(id){
        form_no=form_no-1;  
        $('#newform'+id).remove();
        $('.formno').each(function(index){
           $(this).html(index+2); 
         
        });
    
    }
    
    function invalid(){
        if($('#varifyCode').val()=="")
        {
           $('.invalid').show();
           return false;
        }else{
            return true;
        }
            
    }
$(document).ready(function(){
     $('#varifyCode').keypress(function(e){
      if(e.keyCode==13)
      $('#verifyBtn').click();
    });

$("#verification").show();
$("#login_done").show();
$("#verifyBtn").show();

});
    $( "#verifyBtn" ).click(function() {			
        //event.preventDefault();
	//if(invalid()==true)
       // {   
	var varify = $("#varifyCode").val();
	var site_url = jQuery.cookie('BaseUrl'); 				
	var strst = site_url.substring(0, site_url.length - 7);
	var url = strst+'/tunnel/menu/activation';
	$.ajax({
        type: 'POST',
		//contentType: 'application/json',
        url: url,
        dataType: "json",
		data : {userVerfy : varify,key:API_KEY},		 
        success: function(data){
					if(data.Status == "Success"){
                                           
					//alert("success");
					$('#verify_head').hide();
					$('#done_head').show();
					$('.done_crum').addClass('active');
					$("#verification").hide();
					$("#login_done").show();
					$("#doneButton").show();
					$("#conform").show();
					$("#verifyBtn").hide();
                                        $("#doneverify").focus();
                                        $("#doneButton").focus();
						}else{
							  $('.modal-body').html('<p style="color:red">Verification Code is invalid</p>');
						       $("#myModal").modal('show');
                                                     }
					
					},
        error: function(error){
		
				console.log(error);	
		}
	});
       // }
	

});


/*$("#driverAll").on('click',function(){
	var status = $(this).is(":checked") ? true : false;
    $(".dtp").prop('checked',status);   
}); 
$("#driverAir").on('click',function(){
  
    $("#driverAll").prop('checked',false);
}); 
$("#driverOut").on('click',function(){
  
    $("#driverAll").prop('checked',false);
});
$("#driverLocal").on('click',function(){
  
    $("#driverAll").prop('checked',false);
});
$("#driverPoint").on('click',function(){
  
    $("#driverAll").prop('checked',false);
	 $("#driverAll").prop('checked',false);
});*/


$("#driverBoth").on('click',function(){
    
	var status = $(this).is(":checked") ? true : false;
    $(".ccb").prop('checked',status);
	
    
});

$("#driverCash").on('click',function(){
  
    $("#driverBoth").prop('checked',false);
});
$("#driverCredit").on('click',function(){
  
    $("#driverBoth").prop('checked',false);
});
    
    
    $('#driver_reg').keypress(function (e) {
 var key = e.which;
 if(key == 13)  // the enter key code
  {
  save_drivers();
  }
});
$("#success-msg").hide();
$("#error-msg").hide();

function driver_payment_upload(){		
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
					$(this).parent().append('<div class="error_label" style="color:#f00 !important;">Required Field.</div>');
				error=error+1;
				return false;
				} 
			});
			
			
			$('#cph_main_drpPaymentMode').each(function(){
				if(($(this).val() == '0')){
					$(this).parent().append('<div class="error_label" style="color:#f00 !important;">Required Field.</div>');
				error=error+1;
				return false;
				} 
			});
			$('#amount').each(function(){
				if(($(this).val() == '')){
					$(this).parent().append('<div class="error_label" style="color:#f00 !important;">Required Field.</div>');
					error=error+1;
					return false;
				}
			});
			$('#deposit_bank').each(function(){
				if(($(this).val() == '')){
					$(this).parent().append('<div class="error_label" style="color:#f00 !important;">Required Field.</div>');
					error=error+1;
					return false;
				}
			});	
			$('#deposited_branch').each(function(){
				if(($(this).val() == '')){
					$(this).parent().append('<div class="error_label" style="color:#f00 !important;">Required Field.</div>');
					error=error+1;
					return false;
				}
			});
				
		}
		if(error==0){
		    var deposit_date = $("#deposit_date").val();
			//alert(deposit_date);
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


function driver_edit(){
	var site_url = jQuery.cookie('BaseUrl');
	var strst = site_url.substring(0, site_url.length - 7);
	var chkdata = $("input[name='drAir\\[\\]']:checked").map(function(){return $(this).val();}).get();
	var chkpaytype = $("input[name='drpaymentType\\[\\]']:checked").map(function(){return $(this).val();}).get();
	var url = strst+'/tunnel/menu/driverEdit_Profile';
	$.ajax({
        type: $("#driver_editProfile").attr('method'),
        url: url,
        dataType: "json",
		data : $("#driver_editProfile").serialize()+"&chkdata="+chkdata+"&chkpaytype="+chkpaytype,
        success: function(data){
					if(data.status == "Success"){						
						$('html, body').animate({ scrollTop: 0 }, 'fast', function () {							
						});
						$("#success-msg").show();
							setTimeout(function() {
								$("#success-msg").hide();
							}, 150000);								
						}
						else if(data.status == "Failed" && data.errorCode == "101"){
						    $('html, body').animate({ scrollTop: 0 }, 'fast', function () {							
						     });
						     $("#error-msg").show();
							 setTimeout(function() {
								$("#error-msg").hide();
							 }, 15000);
							 $("#driver_firstName").focus();
						  }else{
							  location.reload();
						  }
					},
        error: function(error){
				console.log(error);
				}
 });
}


 //******************************************Driver Registration Validation ****************************/
		jQuery.validator.addMethod("lettersonly", function(value, element) {
		  return this.optional(element) || /^[a-z]+$/i.test(value);
		}, "Letters only");
		
		
		
		  jQuery.validator.addMethod("specialChar", function(value, element) {
			 return this.optional(element) || /([0-9a-zA-Z\s])$/.test(value);
		  }, "Please Fill Correct Value in Field.");

		
		  
         $("#frm_details").validate({
			 
             errorClass: 'error col-lg-offsets-5',
			 
			 onfocusout: false,
			 invalidHandler: function(form, validator) {
                    var errors = validator.numberOfInvalids();
					//alert(errors);
                    if (errors) {
                        //alert(validator.errorList[0].message);
                        validator.errorList[0].element.focus();
                    }
                },
			 
			rules: {
				
                           
                            dName: {
					required: true,
					specialChar: true,
					number:false
				      },
					        dfName: {
					required: true,
					specialChar: true,
					number:false
				      },
                            dr_email: {
					required: true,
					specialChar: true,
					email: true                                  
				      },
                            dreDateOfBirth: {
								required:true, 
					             date: true                                        
				      },
                            dregender: {
								required: true
								  },                            
                            dAd: {
					required: true
				      },				
                            driver_state: {
                                        required: true
                                      },
                            city: {
                                        required: true
                                      },
                            driver_country: {
										required:true 
				},          
							dpincode: {
								required: true,
								minlength:4,
								maxlength:6
				    }, 
							driver_no: {                                        
                                        number: true,
                                        minlength: 10,
                                        maxlength: 10,                                        
                                        required:true
                                      },
							dAno: {
								number:true,
								minlength:10,
								maxlength:10,
								required:false 
				      },
                            dpan: {
					          required:true 
				},
                            dfleet: {
					required:true                                   
				      },
                            driver_cartypes: {					
					           required:true  
				      },
                            vechile_name: {
					            required:true  
				      },
                            igniton_type: {
					 required:true   
				      },
                            permit_date: {
                                         required:true,
										 date:true   
				},
                            driverModel: {					
                                        required:true                                   
				    },
						driverBadgeLic: {
							specialChar: true,
					required: true
				      },
					  driverLicState: {
					required: true
				      },
					  driverVechNoChar: {
					required: true,
					lettersonly: true,
					minlength:2,
					maxlength:4
				      },
					  driverVechNo: {
					required: true,
					minlength:4,
					maxlength:10
				      },
					  driverSpec: {
					required: true
				      },
					  /* driverAmount: {
					required: true,
					number:true
				      }, */
					  driverBank: {
						  lettersonly: true,
					required: true
				      },
					  driverholdern: {
						  lettersonly: true,
					required: true,
					number:false
				      },
					  driverBaddess: {
					required: true
				      },
					  driverAcNo: {
					required: true,
					number:true
				      },
					  driverrtgs: {
					required: true
				      },
					   driveribn: {
						   specialChar: true,
					required: true
				      },
					   driverRknow: {
					required: true
				      },
					   driverPrefL: {
					required: true
				      },
					   driverWoff: {
					required: true
				      },
					   driveimei: {
					required: false,
					number:true,
					minlength:12,
					maxlength:20
				      },
					   drivergps: {
					required: true
				      },
					   'dAir[]': {
					required: true
				      },
					   'dCash[]': {
					required: true  
				      },
					   driver_opration_country: {
					required: true
				      },
					   driver_opration_state: {
					required: true
				      },
					   driver_opration_city: {
					required: true
				      },
					   driver_pref_city: {
					required: true
				      },
					   driver_opration_Company: {
					required: true
				      },
					  DriverTerms:{
						  required: true
					  }
			},
			messages: {                            
                            dName: {
					required: "Please Enter Your Name",
					number:"Numbers are not allowed"
				      },
					  dfName: {
					required: "Please Enter your Father Name",
					number:"Numbers are not allowed"
				      },
                         dr_email: {
					required: "Please Enter your Email",
					email: "Please Enter a valid Email"                                  
				      },
                            dreDateOfBirth: {
					required: "Enter Your DOB",
						date:"Enter a valid date"
				      },
                            dregender: {
					required: "Please Select Your Gender"
				      },                            
                            dAd: {
					required: "Please Enter Your Address"
				      },				
                            driver_state: {
                                        required: "Please Select Your State"
                                      },
                            city: {
                                        required: "Please Select Your City Name"
                                      },
                            driver_country: {
										required: "Please Select Your Country Name"
				      },    
							dpincode: {
								required: "Enter your area pincode",
								minlength: "Minimum length is 4",
								maxlength: "Maximum length is 6"
				    },
							driver_no: {                                        
                                        number: "Enter valid Mobile No.",
                                        minlength: "Minimum length is 10",
                                        maxlength: "Maximum length is 10",
										Required: "Please Enter Mobile No."
                                      },
							dAno: {
								number: "Enter valid Mobile no.",
								minlength: "Minimum length is 10",
								maxlength: "Maximum length is 10"
				      },
                            dpan: {
					           required: "Enter Pancard Details"
							},
                            dfleet:{
								required:"Please Select Fleet Type"
							},
                            driver_cartypes: {					
					       required:"Please Select Car Type"
				      },
                            vechile_name: {
									required:"Please Select Vehicle Name"
				      },
					        igniton_type: {
									required:"Please Select Ignition Type"
				      },
                            permit_date: {
                                        required: "Enter Your Vehicle permit Expiry Date",
                                        date: "Enter a valid date"
				},
                            driverModel: {					
                                        required: "Select Vehicle Model Year"
				    },
							driverBadgeLic: {
							required: "Please Lincense Number"
							  },
							  driverLicState: {
					required: "Please Enter your Lincense State"
				      },
						driverVechNoChar: {
						required:"Required",
						lettersonly:"Letters only",
						minlength: "Minimum 2 Letters",
						maxlength: "Maximum 4 Letters"
				      },
						driverVechNo: {
						required: "Enter Vehicle Number",
						minlength: "Minimum 4 Characters",
						maxlength: "Maximum 10 Characters"
				      },
					  driverSpec: {
					required: "Choose your Eye Sight"
				      },
					  /* driverAmount: {
					required: "Please Enter Your Security Amount",
					number:"Only Numbers are allowed"
				      }, */
					  driverBank: {
						  lettersonly:"Letters only",
					required: "Please Enter your Bank Name"
				      },
					  driverholdern: {
						  lettersonly:"Letters only",
					required: "Please Enter Holder Name",
					number:"Numbers are not allowed"
				      },
					  driverBaddess: {
					required: "Please Enter Bank Address"
				      },
					  driverAcNo: {
					required: "Please Enter Account Number",
					number:"Only Numbers are allowed"
				      },
					  driverrtgs: {
					required: "Please Enter Transaction RTGS/NEFT Code"
				      },
					  driveribn: {
					required: "Please Enter IBN Number"
				      },
					  driverRknow: {
					required: "Please Declare about your route"
				      },
					  driverPrefL: {
					required: "Please Select Your preference zone"
				      },
					  driverWoff: {
					required: "This Field is Required"
				      },
					  driveimei: {
					number:"Only Numbers are allowed",
					minlength: "IMEI should be Minimum 12 digits",
					maxlength:"IMEI can be Maximum 20 digits"
				      },
					  drivergps: {
					required: "Please Enter GPS Number"
				      },
					  'dAir[]': {
					required: "Please Choose an Option"
				      },
					  'dCash[]': {
					required: "Please Choose an Option"
				      },
					  driver_opration_country: {
					required: "Please Select Operation Country"
				      },
					  driver_opration_state: {
					required: "Please Select Operation State"
				      },
					  driver_opration_city: {
					required: "Please Select Operation City"
				      },
					  driver_pref_city: {
					required: "Please Select City to Drive Cab"
				      },
					  driver_opration_Company: {
					required: "Please Select Operation Company"
				      },
					  DriverTerms: {
					required: "Accept our terms & conditions"
				      }
			}
		});       
//*******************************End of Driver registration Validation*********************  


//****************************************
//******************************************Driver Edit Profile Validation ****************************/
		/*jQuery.validator.addMethod("lettersonly", function(value, element) {
		  return this.optional(element) || /^[a-z]+$/i.test(value);
		}, "Letters only");  
         $("#driver_editProfile").validate({			 
             errorClass: 'error col-lg-offsets-5',
			rules: {                           
					driver_firstName: {
						required: true,
						lettersonly:true
					},
					driver_father: {
						required: true,
						lettersonly:true
					 },
					email_driver: {
						required: true,
						email: true                                  
					},
					driverBirth: {
						required:true, 
						date: true                                        
					},
					dregender: {
						required: true
					},                            
					driver_adds: {
						required: true
					},				
					driver_amount: {
						required: true
					},
					city_driver: {
						required: true
					},                            
					driver_phone: {                                        
						number: true,
						minlength: 10,
						maxlength: 10,                                        
						required:true
					},
					driver_panNos: {
						required:true 
					},        
					driver_lstate: {
						required:true
					},
					driver_modely: {					
						required:true                                   
					},
					driver_licence: {
						required: true
					},
					driver_vehicleNo: {
						required: true,
						minlength:4,
						maxlength:10
					},
					driverAmount: {
						required: true,
						number:true
					},					 
					driver_route: {
						required: true
					},
					driver_pref: {
						required: true
					},
					driver_weekoff: {
						required: true
					},
					driveimei: {
						required: true,
						number:true,
						minlength:12,
						maxlength:20
					},
					drivergps: {
						required: true 
					}
			},
			messages: {                            
					driver_firstName: {
						required: "Please Enter your name",
						lettersonly:"Numbers are not allowed"
					},
					driver_father: {
						required: "Please Enter your Father name",
						lettersonly:"Numbers are not allowed"
					/* },
					email_driver: {
						required: "Please Enter your Email",
						email: "Please Enter a valid Email"                                  
					},
					driverBirth: {
						required: "Enter Your DOB",
						date:"Enter a valid date"
					},
					dregender: {
						required: "Please Select Your Gender"
					},                            
					driver_adds: {
						required: "Please Enter Your Address"
					},				
					driver_amount: {
						required: "Please Enter your State"
					},
					city_driver: {
						required: "Enter your City name"
					},                           
					driver_phone: {                                        
						number: "Enter valid Mobile No.",
						minlength: "Minimum length is 10",
						maxlength: "Maximum length is 10",
						required: "Please Enter Mobile No."
					},
					driver_panNos: {
						required: "Enter Pancard Details"
					},
					driver_lstate: {
						required: "Enter Your Vehicle permit Expiry Date"
					},
					driver_modely: {					
						required: "Select Vehicle Model Year"
					},
					driver_licence: {
						required: "Please Lincense Number"
					},							  
					driver_vehicleNo: {
						required: "Enter Vehicle Number",
						minlength: "Minimum 4 Characters",
						maxlength: "Maximum 10 Characters"
					},
					driverAmount: {
						required: "Please Enter Your Security Amount",
						number:"Only Numbers are allowed"
					},					  
					driver_route: {
						required: "Please Declare about your route"
					},
					driver_pref: {
						required: "Please Select Your preference zone"
					},
					driver_weekoff: {
						required: "This Field is Required"
					},
					driveimei: {
						required: "Please Enter Mobile IMEI Number",
						number:"Only Numbers are allowed",
						minlength: "IMEI should be Minimum 12 digits",
						maxlength:"IMEI can be Maximum 20 digits"
					},
					drivergps: {
						required: "Please Enter GPS Number" 
					}
			}
		});     */  
//*******************************End of Driver Edit Profile Validation********************* 
 
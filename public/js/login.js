
var moboremail;
    function check()
    {
    var mob = /^[1-9]{1}[0-9]{9}$/;
    if(mob.test($('#login_email').val())==true)
    {
        return true;
    }else{
        return false;
    }
        
        
    }
    function check2()
    {
    var mob = /^[1-9]{1}[0-9]{9}$/;
    if(mob.test($('#emailOrNo').val())==true)
    {
        moboremail=1;
        return true;
    }else{
        moboremail=2;
        return false;
    }
        
        
    }
$(document).ready(function() {

    
    
    $('.login').keypress(function(e){
      if(e.keyCode==13)
      $('#signin').click();
    });
    
     $('.signup').keypress(function(e){
      if(e.keyCode==13)
      $('#nextStepBtn').click();
    });
    
    
    
   //jQuery.cookie('BaseUrl', baseurl, { expires: 3 });
   
   
    
    
    
    
    $("#login_form2").validate({
			rules: {
				login_email:{ 
                                    required:true,
                                    
                                    email :{depends:function()
                                    {
                                      var sel = check();

                                      if(sel ==true)
                                        {
                                            return false;
                                        }
                                      else
                                        {
                                            return true;
                                        }
                                    }
                                
                                }
                            },
				password: {
				required:true,
                                minlength:6,
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
                $("#login_form4").validate({
			rules: {
				login_email:{ 
                                    required:true,
                                    
                                    email :{depends:function()
                                        {
                                          var sel = check();

                                          if(sel ==true)
                                            {
                                                return false;
                                            }
                                          else
                                            {
                                                return true;
                                            }
                                        }
                                
                                    }
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
                $("#login_form3").validate({
			rules: {
				email:{ 
                                    required:true,
                                    
                                    email :{depends:function()
                                    {
                                      var sel = check2();

                                      if(sel ==true)
                                        {
                                            return false;
											
                                        }
                                      else
                                        {
                                            return true;
                                        }
                                    }

                                            }
                                        },
				firstPass : {
					minlength : 6,
                                        required:true
				},
				secondPass : {
					minlength : 6,
					equalTo : "#firstPass"
				}
				
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
				email: "Please enter a valid email / mobile no.",
				agree: "Please accept our policy"
			}
		});
    });
	
function segment(user_id)
{
	//alert(user_id);
	var emailOfuser = $("#emailOrNo").val(); 
	var firstPass = $("#firstPass").val(); 
	var secondPass = $("#secondPass").val(); 
	
	 jQuery.cookie('Email', emailOfuser, { expires: 1,path: '/' });
     jQuery.cookie('moboremail', moboremail, { expires: 1, path: '/'});
	 jQuery.cookie('FirstPass', firstPass, { expires: 1 , path: '/'});
	 jQuery.cookie('SecondPass', secondPass, { expires: 1, path: '/' });
	
	var lo = jQuery.cookie('Email'); 
	var lo1 = jQuery.cookie('FirstPass'); 
	var lo2 = jQuery.cookie('SecondPass'); 
	//alert(lo);
	//alert(lo1);
	//alert(lo2);
	
	var userid = user_id;
	 jQuery.cookie('UsersRole', userid, { expires: 1, path: '/'});
	 var user = jQuery.cookie('UsersRole'); 
	//alert(user);
var base_url = "<?php echo $this->basePath(); ?>";	
window.open(baseurl+'/registration',"_self");
}
function validate2()
 {
    var i=0;
    var email=$("#emailOrNo").val();
    alert(email);
    var mob = /^[1-9]{1}[0-9]{9}$/;
    //var eml = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var eml = /^([A-Za-z0-9_\-\.])+@([A-Za-z0-9_\-])+\.([A-Za-z]{2,4})$/; 
    //var eml = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{3,3}$/;

    var pass=$("#firstPass").val();
    var cpass=$("#secondPass").val();
    $('.valid_error').hide();
    if(eml.test(email)==false && mob.test(email)==false)
    {
        //alert(email);
       $('.email_error').show();

       $('.email_error').html("Please Enter Valid Email address");
       //return false;
       i=i+1;
    }
    if(pass=='' || cpass=='')
    {
        if(pass=='')
        {
            $('.pass_error').show();
            $('.pass_error').html("This Feild is required");
            //alert("Required field could not be empty");
	}
	{
	 $('.cpass_error').show();
            $('.cpass_error').html("This Feild is required");
            //alert("Required field could not be empty");
	}
	i=i+1;
	//return false;
    }
    else
    {
	if(pass.length<6)
	{
            $('.pass_error').show();
            $('.pass_error').html("Password must have six characters or more");
            //return false;
            i=i+1;
	}else
        {
            if(pass==cpass)
            {
                //alert("good");
                return true;
            }
            else
            {
                $('.cpass_error').show();
                $('.cpass_error').html("Password not match");
                //alert('Password not match');
                //return false;
                i=i+1;
            }
	}
    }
    if(i!=0)
    {
        return false;
    }
    
}

	$( "#signup" ).click(function() {
	  $('#crum').show();
	
	$("#login_start").hide();
	$("#login_id").hide();
	$("#done_id").hide();
	$("#loginBtn").hide();
	
	$("#email_id").show();
	$("#nextStepBtn").show();
	
	$("#login_done").show();
	$("#nextStepId").show();
});


function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}


$("#nextStepBtn").click(function(){
 //var base_url = "<?php echo $this->basePath(); ?>";
    //alert("yahoo");
   // alert($("#login_form3").valid());
   var relVar	=	$(this).attr('rel');
   
   
   
   //alert(relVar);
   
   if(relVar!=""){
   var first= relVar;
   }else{
   var first = getUrlVars()["role"];
   }
   
   var site_url = jQuery.cookie('BaseUrl');  
   var strst = site_url.substring(0, site_url.length - 7);
    $(".already").hide();    
	if( $("#login_form3").valid()==true){
				var name = $("#emailOrNo").val();
				var pswd = $("#firstPass").val();
				
				var url = strst+'/tunnel/menu/signupValidation';
				$.ajax({
				        type: 'POST',
						//contentType: 'application/json',
				        url: url,
				        dataType: "json",
						data : {userName : name,userPassword : pswd,key:API_KEY},		 
				        success: function(data){
				        		//alert(data);
									if(data.Status == "001"){
									//alert("update");	
										if(first=='3' || first=='3#'){
											//alert(first);
											segment(nextStepBtn);
										}else{
										$('.segment_crum').addClass('active');
										$("#email_id").hide();
										$("#nextStepBtn").hide();
										$("#doneBtn").hide();
										$("#segment").show();
										}
									}
									else{
                                     $(".already").show();    
									//alert("aleready exits");
									}
								
									},
				        error: function(error){
						
								console.log(error);	
						}
			});
		
		
		
		
		
		}
		else{}
	
	
});
	
	

//$("#signin").click(function(){
 
$(document).on('click', '#log_out', function(){
	// alert(1); 
	// return false;
    //var base_url = baseurl;
	
	var site_url = jQuery.cookie('BaseUrl');  
   var strst = site_url.substring(0, site_url.length - 7);
       
//  alert('yahoo');
//function signin(){
    
      //var cookietoken = getCookie("token");
       // var token = $.cookie('token');
        var id = $.cookie('login_id');	
        var token	=	$(this).attr('rel');
		//var token[0]=token.split("&");
		//alert(token);
		//return false;
	//var base_url = baseurl;	
	var url = strst+'/tunnel/menu/logout?token='+token+'&login_id='+id;
	
	$.ajax({
        type: 'POST',
		//contentType: 'application/json',
        url: url,
        dataType: "json",
				 
        success: function(data){
            //alert(data.status);
                             if(data.status=='true'){
								 //document.cookie="token=;expires=Thu, 01 Jan 1970 00:00:00 UTC";
								 var cookies = $.cookie();
								for(var cookie in cookies) {
								   $.removeCookie(cookie);
								}
								 window.open(strst,"_self");                            
					         //window.open(strst+'/login',"_self");
            }else{
				window.open(strst,"_self");
			}
				},
		
		error: function(error){
	console.log(error);	
		}
		});
		

});

  
$(document).on('click', '#signin', function(){  
    $('.invalid').hide();
    if($("#login_form2").valid()!=false)
    {
	var email = $("#login_email").val();
	var pass = $("#login_password").val();
	var base_url = baseurl;	
	var url = base_url+'/tunnel/menu/login';
	
	$.ajax({
        type: 'POST',
        url: url,
        dataType: "json",
		data : {userName:email,userPass:pass,key:API_KEY,DeviceType:"Web"},		 
                success: function(data){
			          if(data.status=="true")
                        {
							//alert(data.UserData[0].token);
							//return false;
							//$("#loginId").val(data.UserData[0].ID);
                            //document.cookie="token="+data.UserData[0].token;
							document.cookie="login_id="+data.UserData[0].ID;
							if(data.UserData[0].type_data==1){
							  window.location.href=global_url+"/login/user"; 
							}else if(data.UserData[0].type_data==3){
							  window.location.href=global_url+"/login/driver";
							}else{
								window.open(global_url,"_self"); 
							}
                            
                            console.log(data);
                        
                        }else{
                            if(data.status=='Unsuccess' && data.ErrorCode == '102')
                            {
                                $('.invalid').html('Already logged In');  
                            }else if(data.status=='Unsuccess' && data.ErrorCode == '101'){
                                $('.invalid').html('Invalid Email and Password');
                            }else if(data.status=='Unsuccess' && data.ErrorCode == '103'){
								$('.invalid').html('User with this Email or Mobile No. not Exist');
							}else if(data.status=='Unsuccess' && data.ErrorCode == '104'){
								$('.invalid').html('Invalid Password');
							}else if(data.status=='Unsuccess' && data.ErrorCode == '105'){
								$('.invalid').html('Account not Verified');
								var mobile=	data.mobile_no;
								jQuery.cookie('MobileNo', mobile, { expires: 1,path: '/' });
								alert("Code send Successfully");
								window.open(global_url+'/verify',"_self");
							}
                            $('.invalid').show();
                        }
                    },
		
		error: function(error){
                console.log(error);	
		}
            });
    }
});





$(document).on('click', '#resendBtn', function(){   
	var user_mobile = $("#user_mobile").val();
	var base_url = baseurl;	
	var url = base_url+'/tunnel/menu/resendOTP';
	$.ajax({
        type: 'POST',
        url: url,
        dataType: "json",
		data : {mobile_no:user_mobile},		 
                success: function(data){
					if(data.status=="true"){
					alert("Code Resend Successfully");
					}else{
					alert("Mobile No does not exist");
					}
                    },
		
		error: function(error){
                console.log(error);	
		}
            });
    
});


//}
 $('#forget').click(function(){
    
if($('#login_form4').valid()==true)
{
    
      $(".fa-spinner").show();

         // alert("yahoo");
        	
	var url = baseurl+'/tunnel/menu/forgetpassword';
        var email=$('#email').val();
       // alert(email);
    $.ajax({
        type: 'POST',
		//contentType: 'application/json',
        url: url,
        dataType: "json",
	data : {email:email,key:API_KEY},		 
        success: function(data){
            //alert(data.status);
            $(".fa-spinner").hide();
                //alert(data.status);
           // alert(data.status);
                        if(data.status=='true')
                        {
			   $('.modal-body').html('<p>An email has been sent to your account</p>');
			}else
                        {
                            $('.modal-body').html('<p style="color:red">Email address doesn\'t Exist</p>');
                        }
                        $("#myModal").modal('show');
                        $(".closeButton").on("click", function(e) {
                                window.location.href = baseurl;
                        });
                },
		error: function(error)
                {
                    console.log(error);	
		}
        });
    }
});
   $("#reset_form").validate({
			rules: {
				
         
                        firstpass : {
					minlength : 6,
                                        required:true
				},
                        secondpass : {
                                minlength : 6,
                                equalTo : "#firstpass"
                        },
                        username : "alpha"
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
                 
$('.form-control').keypress(function(e){
      if(e.keyCode==13)
      $('#resetpass').click();
    });
$('#resetpass').click(function(){
    
    if($('#reset_form').valid()==true)
    {

          $(".fa-spinner").show();

             // alert("yahoo");

            var url = baseurl+'/tunnel/menu/resetpassword';
            var firstpass=$('#firstpass').val();
            var secondpass=$('#secondpass').val();
            var token=$('#token').val();
            //alert(firstpass+secondpass+token);
           // alert(email);
        $.ajax({
            type: 'POST',
                    //contentType: 'application/json',
            url: url,
            dataType: "json",
                    data : {newpass:firstpass,cpass:secondpass,code:token,key:API_KEY},		 
            success: function(data){
              //  alert(data.status);
                $(".fa-spinner").hide();
                    //alert(data.status);
               // alert(data.status);
                            if(data.ErrorCode=='0')
                            {
                                $('.modal-body').html('<p>Password has been changed Successfully</p>');
                            }
                            else if(data.ErrorCode=='1'){
                                $('.modal-body').html('<p style="color:red">Reset Password Link is expired.</p>');
                            }
                            else
                            {
                                $('.modal-body').html('<p style="color:red">Password should be of 6 characters.</p>');
                            }
                            $("#myModal").modal('show');
                            $(".closeButton").on("click", function(e) {
                                window.location.href = baseurl;
                            });
                    },
                    error: function(error){
                    console.log(error);	
                    }
            });
    }
});
 /* function uploImage(){
	 var site_url = jQuery.cookie('BaseUrl');				
   var urling = site_url+'/tunnel/menu/sendImageByDefault';      	  
	  $("#uploadForm").on('submit',(function(e){
		e.preventDefault();
			$.ajax({
			url: urling,
			type: "POST",
			data:  new FormData(this),
			contentType: false,
			cache: false,
			processData:false,
			success: function(data){
				alert(data);
			//$("#targetLayer").html(data);
			},
			error: function(error){
				console.log(error);
			} 	        
			});
		}));  
 }  */ 
 
 
 
   function signpopup(){
       
       
       alert("asdasdas");
       
   }
  
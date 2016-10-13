/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function sendPushNotification(){
	var mesg = $("#mesg").val();
	//var data = mesg.serialize();
	alert(mesg);
	var site_url = jQuery.cookie('BaseUrl');			
	var url = site_url+'/tunnel/menu/send_push_notification';
	// var url2 = 'localhost/hello42/';
	$.ajax({
		url: url,
		type: 'POST',
		data: {data:mesg},
		beforeSend: function() {
		},
		success: function(data, textStatus, xhr) {
			var u =$('.txt_message').val("");
			var r = String(u);
		},
		error: function(xhr, textStatus, errorThrown) {
		
		}
	});
	return false;
}

function bookingPointSubmit(id){
	//alert(id);exit;
	var status = 0; 
	var error = 0;
	$(".error_label").hide();
	if(status==0){
		var error = 0;
		var mob = /^[0-9]{10,15}$/;
		var num = /^[0-9]*$/;
		var nam = /^[a-zA-Z ]{2,}$/;
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})*$/;
		if($.cookie("token")!=""){
			$('.point_phno').each(function(){
				if(($(this).val() == '') ){
					$(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Required field.</label></div></div>');
					error=error+1;
					return false;
				} else if(mob.test($(this).val())==false){
					$(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Input a Valid Number</label></div></div>');
					error=error+1;
					return false;
				}
			});
			$('.point_name').each(function(){
				if(($(this).val() == '') ){
					$(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Required Field.</label></div></div>');
					error=error+1;
					return false;
				} else if(nam.test($(this).val())==false){
					$(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Alphabets only </label></div></div>');
					error=error+1;
					return false;
				}
			});
			$('.point_Email').each(function(){
				if(($(this).val() == '') ){
					$(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Required Field.</label></div></div>');
					error=error+1;
					return false;
				} else if(regex.test($(this).val())==false){
					$(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Input a Valid Email Address </label></div></div>');
					error=error+1;
					return false;
				}
			});
		}
		$('.point_req_field').each(function(){
			if(($(this).val() == '') ){
				$(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required Field.</label></div>');
				error=error+1;
				return false;
			}
		});  
		$('#pointCabIn').each(function(){
			if(($(this).val() == '') ){
				$(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required Field.</label></div>');
				error=error+1;
				return false;
			}
			
			
		});
		$('.pointDrop').each(function(){
			if(($(this).val() == '') ){
				$(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required Field.</label></div>');
				error=error+1;
				return false;
			}
		});
		$('.pointPickAddress').each(function(){
			if(($(this).val() == '') ){
				$(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required Field.</label></div>');
				error=error+1;
				return false;
			}
		});
		$('input[name=cartype1]:checked').each(function(){
				if(($(this).val() == 'plater')){
					$('#point_date').each(function(){
				if(($(this).val() == '')){
					$(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">For book later booking pick date should be mandatory field.</label></div>');
					error=error+1;
					return false;
				}
			});
				}
		});
			
	}
	if(error==0){
		$('#102').html($('#102').html()+'<i class="fa fa-spinner fa-spin" style="font-size:1.5em;float:right;"></i>');
		$('#102').attr("onclick","return false;");
		if($.cookie('token') == null){    
			var userName=$("#pointName").val();
			var mobileNumbers=$("#pointMobile").val();
			var emailId=$("#pointEmail").val();  
		}else{
			var token = $.cookie('token');
		}
		//debugger;
		var bookingtype=id;
		 //alert(bookingtype);
		var pointCabIn=$("#pointCabIn").val();		
		var pointNationality=$("#pointNationality").val();		
		var pointAdults=$("#pointAdults").val();
		var pointChilds=$("#pointChilds").val();		
		var pointLuggages=$("#pointLuggages").val();				
		var pointPickupArea=$("#pointPickupArea").val();		
		var pointDropArea=$("#pointDropArea").val();  		  
		var pointAddress=$("#pointAddress").val();		
		var point_date=$("#point_date").val();		 
		var pointH=$("#PointTimeHour").val();		
		var pointS=$("#PointpickMinutes").val();		
		var p_lat_long=$("#pointPickupArea_value").val();		
		var d_lat_long=$("#pointDropArea_value").val();		
		var cartypes=$('input[name=cartypes]:checked').val();		
		var PointNoCars=$("#PointNoCars").val();		
		var point_coupan_id=$("#point_coupan_id").val();
		var point_DiscountType=$("#point_DiscountType").val();
		var point_coupanDisount=$("#point_coupan_disount").val();
		var coupan_code=$("#point_coupan_code").val();
		var point_Promo_Name=$("#point_Promo_Name").val();		
		var point_MinimumBookingAmount=$("#point_MinimumBookingAmount").val();		
		var site_url = jQuery.cookie('BaseUrl');
				
		
		//var url = site_url+'/tunnel/bookingCabFare/pointBooking';
		var url = site_url+'/tunnel/bookingCabFare/pointBooking';
		//alert(url);
		$.ajax({
			type: 'POST',
			url: url,
			dataType: "json",
			data : {        
				DeviceType:'WEB',                
				token:token,
				bookingType:bookingtype,
				pointcabindata:pointCabIn,
				pointnationalitydata:pointNationality,
				pointadultsdata:pointAdults,
				pointchildsdata:pointChilds,
				pointluggagesdata:pointLuggages,
				pointpickupareadata:pointPickupArea,
				pointdropareadata:pointDropArea,
				pointaddressdata:pointAddress,
				pointdate:point_date,
				pointH:pointH,
				pointS:pointS,
				carType:cartypes,
				userName:userName,
				mobileNumbers:mobileNumbers,
				emailId:emailId,
				origin:p_lat_long,
				destiny:d_lat_long,
				key:API_KEY,
				PointNoCars:PointNoCars,
				point_coupan_id:point_coupan_id,
				point_DiscountType:point_DiscountType,
				PromotionName:point_Promo_Name,
				point_coupanDisount:point_coupanDisount,
				point_MinimumBookingAmount:point_MinimumBookingAmount,
			    CouponName:coupan_code},
			success: function(data){ 
				if(data.Status == 'Success'){
					$('.fa-spin').remove();
					alert("\"Thanks for choosing Hello42! Your bkg ref "+data.ref+" for "+data.Pickupdate+" "+data.pickupTime+". Total apx cost Rs. "+data.price+"\"");	
					$('#102').attr("onclick","bookingPointSubmit(this.id);");
					location.reload();
					//console.log(data.distance);
				}else{
					alert(data.msg);
					location.reload();
				}
			},
			error: function(error){
				console.log(error);	
			}
		});
	}        
}

function geolocatedrop(){
	var city = $("#pointDropArea").val();
	var citylength = city.length;
	if(citylength > 1){                                                     
		var site_url = jQuery.cookie('BaseUrl');
		var url = site_url+'/tunnel/menu/allcities';
		$.ajax({
			type: 'POST',
			url: url,
			dataType: "json",
			data:{citydeatils:city,key:API_KEY},
			success: function(data){
				var citylength = data.Response.length;
				var list='';
				for(var i=0; i<citylength; i++){
					var city =data.Response[i];
					var latlong = data.LatLog[i];
					list+='<option name='+latlong+'>'+city+'</option>'; 
				}
				$("#citydetailsdrop").html(list);
			},
			error: function(error){
			console.log(error);	
			}
		});
	}else{
	}
}


$(document).ready(function(){
  
 var site_url = jQuery.cookie('BaseUrl');
    
    $( "#pointPickupArea" ).autocomplete({
      source: site_url+'/tunnel/menu/allcities?key='+API_KEY,minLength:0,delay:0,
      focus: function( event, ui ) {		 
        $( "#pointPickupArea" ).val( ui.item.label );
        return false;
      },
      select: function( event, ui ) {
        $( "#pointPickupArea" ).val( ui.item.label );
        $( "#pointPickupArea_value" ).val( ui.item.value );
       
 
        return false;
      }
    });
    $( "#pointDropArea" ).autocomplete({
      source: site_url+'/tunnel/menu/allcities?key='+API_KEY,minLength: 2,
      focus: function( event, ui ) {
        $( "#pointDropArea" ).val( ui.item.label );
        return false;
      },
      select: function( event, ui ) {
        $( "#pointDropArea" ).val( ui.item.label );
        $( "#pointDropArea_value" ).val( ui.item.value );
       
 
        return false;
      }
    });
    $( "#airPickupLocation" ).autocomplete({
      source:site_url+'/tunnel/menu/allcities2?key='+API_KEY,minLength: 2,
      focus: function( event, ui ) {
        $( "#airPickupLocation" ).val( ui.item.label );
        return false;
      },
      select: function( event, ui ) {
        $( "#airPickupLocation" ).val( ui.item.label );
        $( "#airPickupLocation_val" ).val( ui.item.value ).trigger('change');
       
 
        return false;
      }
    });
	$( "#airDropLocation" ).autocomplete({
      source:site_url+'/tunnel/menu/allcities2?key='+API_KEY,minLength: 2,
      focus: function( event, ui ) {
        $( "#airDropLocation" ).val( ui.item.label );
        return false;
      },
      select: function( event, ui ) {
        $( "#airDropLocation" ).val( ui.item.label );
        $( "#airDropLocation_val" ).val( ui.item.value ).trigger('change');
       
 
        return false;
      }
    });
    
	
    $( "#localPickupArea" ).autocomplete({
      source: site_url+'/tunnel/menu/allcities2?key='+API_KEY,minLength: 2,
      focus: function( event, ui ) {
        $( "#localPickupArea" ).val( ui.item.label );
        return false;
      },
      select: function( event, ui ) {
        $( "#localPickupArea" ).val( ui.item.label );
       
        $( "#localPickupArea_value" ).val( ui.item.value ).trigger('change');
     
        
       
 
        return false;
      }
    });
    
   //////////driver name of vehicle////
    
    $( "#vechile_name" ).autocomplete({
      source: site_url+'/tunnel/menu/vehicles?key='+API_KEY,minLength: 3,
    
      focus: function( event, ui ) {
        $( "#vechile_name" ).val( ui.item.label );
        return false;
      },
      select: function( event, ui ) {
        $( "#vechile_name" ).val( ui.item.label );
       
        $( "#vechile_name_value" ).val( ui.item.value ).trigger('change');
     
        
       
 
        return false;
      }
    });
    
     $( ".secodformV" ).autocomplete({
      source: site_url+'/tunnel/menu/vehicles?key='+API_KEY,minLength: 3,
    
      focus: function( event, ui ) {
        $( ".secodformV" ).val( ui.item.label );
        return false;
      },
      select: function( event, ui ) {
        $( ".secodformV" ).val( ui.item.label );
       
        $( ".secodformVH" ).val( ui.item.value ).trigger('change');
     
        
       
 
        return false;
      }
    });
    
    ////city for driver signup //
   
    
    
    

    
});
	
	
	
function bookingLocalSubmit(id){	
		//debugger;
		var status = 0; 
		var error =0;
		$(".error_label").hide();
		if(status==0){
		//debugger;
			//alert(1);
			var error = 0;
			var mob = /^[0-9]{10,15}$/;
			var num = /^[0-9]*$/;
			var nam = /^[a-zA-Z ]{2,}$/;
			var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})*$/;
			if($.cookie("token")!=""){
				$('.loc_name').each(function(){
					if(($(this).val() == '')){
						$(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Required field.</label></div></div>');
						error=error+1;
						return false;
						
					}else if(nam.test($(this).val())==false){
						$(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Input a valid name</label></div></div>');
						error=error+1;
						return false;
					}
				});
				$('.loc_phno').each(function(){
					if(($(this).val() == '')){
						$(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Required field.</label></div></div>');
						error=error+1;
						return false;
					}else if(mob.test($(this).val())==false){
						$(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Input a Valid Number</label></div></div>');
						error=error+1;
						return false;
					}
				});
				$('.loc_Email').each(function(){
					if(($(this).val() == '')){
						$(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Required field.</label></div></div>');
						error=error+1;
						return false;
					}else if(regex.test($(this).val())==false){
						$(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Input a Valid Email id</label></div></div>');
						error=error+1;
						return false;
					} 
				});
			}
			$('#localCabIn').each(function(){
				if(($(this).val() == '')){
					$(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required Field.</label></div>');
				error=error+1;
				return false;
				} 
			});
			/*$('.loc_req_field').each(function(){
				if(($(this).val() == '')){
					$(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required Field.</label></div>');
					error=error+1;
					return false;
				}
			});*/
			$('.Address_loc_req_field').each(function(){
				if(($(this).val() == '')){
					$(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required Field.</label></div>');
					error=error+1;
					return false;
				}
			});
			$('input[name=booklocaltime]:checked').each(function(){
				if(($(this).val() == 'Later')){
					$('.local_date_picker').each(function(){
				if(($(this).val() == '')){
					$("#pickd").after().append('<div class="error_label"><label style="color:#f00 !important;;">For book later booking pick date should be mandatory field.</label></div>');
					error=error+1;
					return false;
				}
			});
				}
			});
			
				
		}
		if(error==0){
			//debugger;
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
			//var booklocaltime = $("#booklocaltime").val();
			//var later = $("#later").val();
			var datepicker = $("#local_datepicker").val();
			var localTimeH=$("#TimeHour").val();
			var localTimeS=$("#pickMinutes").val();
			var localNoCars=$("#localNoCars").val();
			var local_coupan_id=$("#local_coupan_id").val();
			var local_coupan_code=$("#coupan_code1").val();
			var local_DiscountType=$("#local_DiscountType").val();
			var local_coupanDisount=$("#local_coupan_disount").val();
			var local_MinimumBookingAmount=$("#local_MinimumBookingAmount").val();
			var local_Promo_Name=$("#local_Promo_Name").val();
			
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
					localCabInData : localCabIn,
					localCabForData:localCabFor,
					localNationalitydata:localNationality,
					localAdultsDat : localAdults,
					localChildsData:localChilds,
					localLuggagesData:localLuggages,
					pickup :pickuparea, 
					localAddressData:localAddress,
					picklatlong:pickuplocation,
					datepickerData:datepicker,
					localTimeH:localTimeH,
					localTimeS:localTimeS,
					local_coupan_id:local_coupan_id,
					localNoCars:localNoCars,
					PromotionName:local_Promo_Name,
					local_DiscountType:local_DiscountType,
					local_coupanDisount:local_coupanDisount,
					local_MinimumBookingAmount:local_MinimumBookingAmount,
					key:API_KEY,
					//PromotionName:'Sessional',
					CouponName:local_coupan_code},		 
				success: function(data){	
					if(data.Status == 'true'){
					$('.fa-spin').remove();
					alert("\"Thanks for choosing Hello42! Your bkg ref "+data.ref+" for "+data.pickupTime+" Total apx cost Rs. "+data.price+"\"");	
					$('#101').attr("onclick","bookingLocalSubmit(this.id);");
					location.reload();
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
 
 
 function booking_time(id){
     
     if(id=='later'){
         $("#pickdate").show();
     }
     else if(id='now'){
         $("#pickdate").hide();
     }
     
 }
 function pbooking_time(id){
     
     if(id=='pnow'){
         $("#pdate").hide();
     }
     else if(id='plater'){
         $("#pdate").show();
     }
    
 }
 
 function airport_booking_time(id){
     
     if(id=='anow'){
         $("#airpickup").hide();
     }
     else if(id='alater'){
         $("#airpickup").show();
     }
     
 }
 
 function carbook(id){
     
    // alert(id);
     
 }
 
 function goingToAirportSubmit(id){
     
     var status = 0; 
     var error =0;
     $(".error_label").hide();
    if(status==0){
           
           //alert(1);
            var mob = /^[0-9]{10,15}$/;
            var num = /^[0-9]*$/;
            var nam = /^[a-zA-Z ]{2,}$/;
			var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})*$/;
			if($.cookie("token")!=""){
				$('.Air_name').each(function(){
					if(($(this).val() == '')){
						$(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Required field.</label></div></div>');
						error=error+1;
						return false;
						
					}else if(nam.test($(this).val())==false){
						$(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Input a valid name</label></div></div>');
						error=error+1;
						return false;
					}
				});
				$('.Air_phno').each(function(){
					if(($(this).val() == '')){
						$(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Required field.</label></div></div>');
						error=error+1;
						return false;
					}else if(mob.test($(this).val())==false){
						$(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Input a Valid Number</label></div></div>');
						error=error+1;
						return false;
					}
				});
				$('.Air_Email').each(function(){
					if(($(this).val() == '')){
						$(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Required field.</label></div></div>');
						error=error+1;
						return false;
					}else if(regex.test($(this).val())==false){
						$(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Input a Valid Email id</label></div></div>');
						error=error+1;
						return false;
					} 
				});
			}
            $('.AirPick').each(function(){
               if(($(this).val() == '') )
              {
                  $(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Required field.</label></div></div>');
                  error=error+1;
                  return false;
                  
              } 
          });  
           $('.AirLandMark').each(function(){
               if(($(this).val() == '') )
              {
                  $(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required field.</label></div></div>');
                  error=error+1;
                  return false;
                  
              } 
          });
          $('.airPickupAddress').each(function(){
               if(($(this).val() == '') )
              {
                  $(this).parent().append('<div class="error_label"><label style="color:#f00 !important;;">Required Field.</label></div>');
                  error=error+1;
                  return false;
              } 
          });
           $('.airDropAddress').each(function(){
               if(($(this).val() == '') )
              {
                  $(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"style="display:block !important;"/><label style="color:#f00 !important; clear:both !important;">Required Field.</label></div></div>');
                  error=error+1;
                  return false;
              } 
          });
            }
     
    
        //var userId = $("#loginId").val();
        
        
       if(error==0){
		 //alert(2);
       $('#103').html($('#103').html()+'<i class="fa fa-spinner fa-spin" style="font-size:1.5em;float:right;"></i>');
       $('#103').attr("onclick","return false;");
        //var token = token;
         
			
		if($.cookie('token') == null){    
		var userName=$("#AirName").val();
		var mobileNumbers=$("#AirMobile").val();
		var emailId=$("#AirEmail").val();  
		}else{
		var token = $.cookie('token');
		}
        
	var bookingType = id;
	var airNationalitydata=$("#airNationality").val();
	var airAdultsdata=$("#airAdults").val();
	var airChildsdata=$("#airChilds").val();
	var airLuggagesdata=$("#airLuggages").val();
	var airFlightNodata=$("#airFlightNo").val();
	var airAirportdata=$("#airAirportTerminals").val();
	var airPickupLocationdata=$("#airPickupLocation").val();
	var airDropLocationdata=$("#airDropLocation").val();
	var airLandmarkdata=$("#airLandmark").val();
	var airPickupAddressdata=$("#airPickupAddress").val();
	var airDropAddressdata=$("#airDropAddress").val();
	var airPickupDatedata=$("#airPickupDate").val();
	var airPickupTimeHdata=$("#airPickupTimeH").val();
	var airPickupTimeS=$("#airPickupTimeS").val();
	var airport_coupan_code=$("#airport_coupan_code").val();
	var airport_coupan_id=$("#airport_coupan_id").val();
	var airport_DiscountType=$("#airport_DiscountType").val();
	var airport_coupan_disount=$("#airport_coupan_disount").val();
	var airport_Promo_Name=$("#airport_Promo_Name").val();
	var airport_MinimumBookingAmount=$("#airport_MinimumBookingAmount").val();
	var aircartypes = $('input[name=Airportcartype]:checked').val();
	
        var site_url = jQuery.cookie('BaseUrl');
	
	
	var url = site_url+'/tunnel/bookingCabFare/airportBookingto';
	
	$.ajax({
        type: 'POST',
        url: url,
        dataType: "json",
		data : {        
				token:token,
				DeviceType:'WEB',
				userName:userName,
				mobileNumbers:mobileNumbers,
				emailId:emailId,
				bookingType:bookingType,
				airnationality : airNationalitydata,
				airadults:airAdultsdata,
				airchilds:airChildsdata,
				airluggages : airLuggagesdata,
				airflightno:airFlightNodata,
				airairport:airAirportdata,
				airpickuplocation:airPickupLocationdata,
				airDropLocation :airDropLocationdata,
				airDropAddress: airDropAddressdata,
				airlandmark:airLandmarkdata,
				airpickupaddress:airPickupAddressdata,
				airpickupdate:airPickupDatedata,
				airpickuptimeH:airPickupTimeHdata,
				airpickuptimeS:airPickupTimeS,
				airport_coupan_id:airport_coupan_id,
				airport_DiscountType:airport_DiscountType,
				airport_coupan_code:airport_coupan_code,
				airport_coupan_disount:airport_coupan_disount,
				airport_MinimumBookingAmount:airport_MinimumBookingAmount,
				airport_Promo_Name:airport_Promo_Name,
				aircartypes:aircartypes,
                                key:API_KEY
				},		 
        success: function(data){
			
			
			if(data.Status == 'Success'){
					$('.fa-spin').remove();
					alert("\"Thanks for choosing Hello42! Your bkg ref "+data.ref+"for "+data.Pickupdate+" "+data.pickupTime+"  Total approx cost Rs."+data.price+"\"");
					$('#103').attr("onclick","goingToAirportSubmit(this.id);");
					location.reload();
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
   


function bookingroundtrip(id){

     var status = 0; 
     var error =0;
     $(".error_label").hide();
    if(status==0){
           
           
            var mob = /^[0-9]{10,15}$/;
            var num = /^[0-9]*$/;
            var nam = /^[a-zA-Z ]{2,}$/;
            
            $('.StationPhno').each(function(){
               if(($(this).val() == '') )
              {
                  $(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Required field.</label></div></div>');
                  error=error+1;
                  return false;
                  
              } else if(mob.test($(this).val())==false)
              {
                 $(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Input a Valid Number</label></div></div>');
                  error=error+1;
                 return false;
              }
          });
          $('.StationName').each(function(){
               if(($(this).val() == '') )
              {
                  $(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Required Field.</label></div></div>');
                  error=error+1;
                  return false;
              } else if(nam.test($(this).val())==false)
              {
                 $(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Alphabets Only </label></div></div>');
                  error=error+1;
                  return false;
              }
          });
      
          $('.StationPick').each(function(){
              if(($(this).val() == '') )
              {
                 
                  $(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Required Field.</label></div></div>');
                  error=error+1;
                  return false;
              }
              
               
           });
           $('#outRoundtripfrom').each(function(){
              if(($(this).val() == '') )
              {
                 
                  $(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Required Field.</label></div></div>');
                  error=error+1;
                  return false;
              }
              
               
           });
            }
            if(error==0){
            var userId = $("#loginId").val();
            if(userId== ""){
            $('#login_form2').bPopup();
             }else{
   
       $('#104').html($('#104').html()+'<i class="fa fa-spinner fa-spin" style="font-size:1.5em;float:right;"></i>');
       $('#104').attr("onclick","return false;"); 
       
        if($.cookie('token') == null){    
        var userName=$("#pointName").val();
	var mobileNumbers=$("#pointMobile").val();
	var emailId=$("#pointEmail").val();  
        }else{
         var token = $.cookie('token');
        }
    var bookingType=id;
    var outRoundtripfromData=$("#outRoundtripfrom").val();
    var outRoundtriptoData=$("#outRoundtripto").val();
    var outstation_DdateData=$("#outstation_Ddate").val();
    var outstation_RdateData=$("#outstation_Rdate").val();
    var outstationNationalityData=$("#outstationNationality").val();
    var outstationAdultsData=$("#outstationAdults").val();
    var outstationChildsData=$("#outstationChilds").val();
    var outstationLuggagesData=$("#outstationLuggages").val();
    var outPickupAddressData=$("#outPickupAddress").val();
    var outPickupTimeHData=$("#outPickupTimeH").val();
    var outPickupTimeMData=$("#outPickupTimeM").val();
	var cartype=$('input[name=cartype]:checked').val();
    var site_url = jQuery.cookie('BaseUrl');			

    var url = site_url+'/tunnel/menu/outstationTrip';

	$.ajax({
        type: 'POST',
        url: url,
        dataType: "json",
		data : {        
				token:token,
				DeviceType:'WEB',
				carType:cartype,
				bookingType:bookingType,
				outfrom : outRoundtripfromData,
				outto:outRoundtriptoData,
				outDepdate:outstation_DdateData,
				outRdate : outstation_RdateData,
				outNationality:outstationNationalityData,
				outAdults:outstationAdultsData,
				outChilds:outstationChildsData,
				outLuggages:outstationLuggagesData,
				outPickupAddress:outPickupAddressData,
				outPickupH:outPickupTimeHData,
				outPickupM:outPickupTimeMData,
				userName:userName,
				mobileNumbers:mobileNumbers,
				emailId:emailId,
				userId:userId,
                                key:API_KEY
				},		 
        success: function(data){
		
					$('.fa-spin').remove();
					alert("\"Thanks for choosing Hello42. Your bkg ref. "+data.ref+"\nPickUp Date: "+data.Pickupdate+" Return date: "+data.Returndate+"We will contact you shortly\"");
					$('#104').attr("onclick"," return bookingroundtrip(this.id);")
					},
        error: function(error){
		
				console.log(error);	
				
		}
	});

             }
            }
    }
	
function bookinground1trip(id){
	alert('Mohit');
	 var error =0;
	if(error==0){
		var userId = $("#loginId").val();
            if(userId== ""){
            $('#login_form2').bPopup();
             }else{
   
       $('#104').html($('#104').html()+'<i class="fa fa-spinner fa-spin" style="font-size:1.5em;float:right;"></i>');
       $('#104').attr("onclick","return false;"); 
       
        if($.cookie('token') == null){    
        //var userName=$("#pointName").val();
	//var mobileNumbers=$("#pointMobile").val();
	//var emailId=$("#pointEmail").val();  
        }else{
         var token = $.cookie('token');
        }
    var bookingType=id;
    var outRoundtripfromData=$("#outRoundtripfrom").val();
	//alert(outRoundtripfromData);
	 var outRoundtriptoData=$("#outRoundtripto").val();
    var outstation_DdateData=$("#outstation_Ddate").val();
    var outstation_RdateData=$("#outstation_Rdate").val();
    var outstationNationalityData=$("#outstationNationality").val();
    var outstationAdultsData=$("#outstationAdults").val();
    var outstationChildsData=$("#outstationChilds").val();
    var outstationLuggagesData=$("#outstationLuggages").val();
    var outPickupAddressData=$("#outPickupAddress").val();
    var outPickupTimeHData=$("#outPickupTimeH").val();
    var outPickupTimeMData=$("#outPickupTimeM").val();
	var cartype=$('input[name=cartype]:checked').val();
    var site_url = jQuery.cookie('BaseUrl');			

    var url = site_url+'/tunnel/menu/outstationTrip';

	$.ajax({
        type: 'POST',
        url: url,
        dataType: "json",
		data : {        
				token:token,
				DeviceType:'WEB',
				carType:cartype,
				bookingType:bookingType,
				outfrom : outRoundtripfromData,
				outto:outRoundtriptoData,
				outDepdate:outstation_DdateData,
				outRdate : outstation_RdateData,
				outNationality:outstationNationalityData,
				outAdults:outstationAdultsData,
				outChilds:outstationChildsData,
				outLuggages:outstationLuggagesData,
				outPickupAddress:outPickupAddressData,
				outPickupH:outPickupTimeHData,
				outPickupM:outPickupTimeMData,
			
				userId:userId,
                                key:API_KEY
				},		 
        success: function(data){
		
					$('.fa-spin').remove();
					alert("\"Thanks for choosing Hello42. Your bkg ref. "+data.ref+"\nPickUp Date: "+data.Pickupdate+" Return date: "+data.Returndate+"We will contact you shortly\"");
					$('#104').attr("onclick"," return bookingroundtrip(this.id);")
					},
        error: function(error){
		
				console.log(error);	
				
		}
	});
			 }

	
	}
	
}

//////////////Function for Intercity Booking //////////////////////


function bookingintercitytrip(id){
	alert('Test');
     var status = 0; 
     var error =0;
     $(".error_label").hide();
    if(status==0){
           
           
            var mob = /^[0-9]{10,15}$/;
            var num = /^[0-9]*$/;
            var nam = /^[a-zA-Z ]{2,}$/;
            
            $('.Intercity_phno').each(function(){
               if(($(this).val() == '') )
              {
                  $(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Required field.</label></div></div>');
                  error=error+1;
                  return false;
                  
              } else if(mob.test($(this).val())==false)
              {
                 $(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Input a Valid Number</label></div></div>');
                  error=error+1;
                 return false;
              }
          });
          $('.Intercity_name').each(function(){
               if(($(this).val() == '') )
              {
                  $(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Required Field.</label></div></div>');
                  error=error+1;
                  return false;
              } else if(nam.test($(this).val())==false)
              {
                 $(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Alphabets Only </label></div></div>');
                  error=error+1;
                  return false;
              }
          });
      
          $('.IntercityPick').each(function(){
              if(($(this).val() == '') )
              {
                  $(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Required Field.</label></div></div>');
                  error=error+1;
                  return false;
              }
              
               
           });
           $('#intercitytripfrom').each(function(){
              if(($(this).val() == '') )
              {  
                  $(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Required Field.</label></div></div>');
                  error=error+1;
                  return false;
              }    
           });
		   $('#intercitytripto').each(function(){
              if(($(this).val() == '') )
              {  
                  $(this).parent().append('<div class="error_label"><div class="col-mg-7 col-lg-7"/><label style="color:#f00 !important;;">Required Field.</label></div></div>');
                  error=error+1;
                  return false;
              }    
           });
            }
            if(error==0){
            var userId = $("#loginId").val();
            //if(userId== ""){
            //$('#login_form2').bPopup();
             //}else{
   
       $('#104').html($('#104').html()+'<i class="fa fa-spinner fa-spin" style="font-size:1.5em;float:right;"></i>');
       $('#104').attr("onclick","return false;"); 
       
        if($.cookie('token') == null){    
        var userName=$("#intercityName").val();
		var mobileNumbers=$("#intercityMobile").val();
		var emailId=$("#intercityEmail").val();  
        }else{
         var token = $.cookie('token');
        }
    var bookingType=id;
    var outRoundtripfromData=$("#outRoundtripfrom").val();
    var outRoundtriptoData=$("#outRoundtripto").val();
    var outstation_DdateData=$("#outstation_Ddate").val();
    var outstation_RdateData=$("#outstation_Rdate").val();
    var outstationNationalityData=$("#outstationNationality").val();
    var outstationAdultsData=$("#outstationAdults").val();
    var outstationChildsData=$("#outstationChilds").val();
    var outstationLuggagesData=$("#outstationLuggages").val();
    var outPickupAddressData=$("#outPickupAddress").val();
    var outPickupTimeHData=$("#outPickupTimeH").val();
    var outPickupTimeMData=$("#outPickupTimeM").val();
	var cartype=$('input[name=cartype]:checked').val();
    var site_url = jQuery.cookie('BaseUrl');			

    //var url = site_url+'/tunnel/menu/outstationTrip';

	$.ajax({
        type: 'POST',
        url: url,
        dataType: "json",
		data : {        
				token:token,
				DeviceType:'WEB',
				carType:cartype,
				bookingType:bookingType,
				outfrom : outRoundtripfromData,
				outto:outRoundtriptoData,
				outDepdate:outstation_DdateData,
				outRdate : outstation_RdateData,
				outNationality:outstationNationalityData,
				outAdults:outstationAdultsData,
				outChilds:outstationChildsData,
				outLuggages:outstationLuggagesData,
				outPickupAddress:outPickupAddressData,
				outPickupH:outPickupTimeHData,
				outPickupM:outPickupTimeMData,
				userName:userName,
				mobileNumbers:mobileNumbers,
				emailId:emailId,
				userId:userId,
                                key:API_KEY
				},		 
        success: function(data){
		
					$('.fa-spin').remove();
					alert("\"Thanks for choosing Hello42. Your bkg ref. "+data.ref+"\nPickUp Date: "+data.Pickupdate+" Return date: "+data.Returndate+"We will contact you shortly\"");
					$('#104').attr("onclick"," return bookingintercitytrip(this.id);")
					},
        error: function(error){
		
				console.log(error);	
				
		}
	});

             //}
            }
    }
	
///////////////// Function for Intercity Booking Starts Here//////

function bookingintercityNewtrip(id){
	 //var intercity_car_radio=$('#intercity_car_radio').val();
	 var intercity_car_radio = $("input:radio[name ='intercity_car']:checked").val();
            if ($("#intercity_car_radio:checked").length == 0){
				alert("Please select at least one car");
                return false;
            }else{
				//alert(intercity_car_radio);
     var error =0;
     $(".error_label").hide();

            if(error==0){
            var userId = $("#loginId").val();
            //if(userId== ""){
            //$('#login_form2').bPopup();
             //}else{
   
       $('#105').html($('#105').html()+'<i class="fa fa-spinner fa-spin" style="font-size:1.5em;float:right;"></i>');
       $('#105').attr("onclick","return false;"); 
       
        if($.cookie('token') == null){    
        var userName=$("#intercityName").val();
		var mobileNumbers=$("#intercityMobile").val();
		var emailId=$("#intercityEmail").val();  
        }else{
         var token = $.cookie('token');
        }
	
    var bookingType=id;
    var intercitytripfromData=$("#intercitytripfrom").val();
    var intercitytriptoData=$("#intercitytripto").val();
    var intercity_DdateData=$("#intercity_Depdate").val();
    //var outstation_RdateData=$("#outstation_Rdate").val();
    var intercityNationalityData=$("#intercityNationality").val();
    var intercityAdultsData=$("#intercityAdults").val();
    var intercityChildsData=$("#intercityChilds").val();
    var intercityLuggagesData=$("#intercityLuggages").val();
    var intercityPickupAddressData=$("#intercityPickupAddress").val();
	var intercityDropAddressData=$("#intercityDropAddress").val();
    var intercityPickupTimeHData=$("#intercityPickupTimeH").val();
    var intercityPickupTimeMData=$("#intercityPickupTimeM").val();
	
	var intercitySplit = intercity_car_radio.split(',');
	var carprice=intercitySplit[0];
	var carfixkm=intercitySplit[1];
	var cartype=intercitySplit[2];
	var routeid=intercitySplit[3];
    var site_url = jQuery.cookie('BaseUrl');			

    var url = site_url+'/tunnel/bookingCabFare/intercityBooking';

	$.ajax({
        type: 'POST',
        url: url,
        dataType: "json",
		data : {        
				token:token,
				DeviceType:'WEB',
				carPrice:carprice,
				carFixKm:carfixkm,
				carType:cartype,
				routeId:routeid,
				bookingType:bookingType,
				intercityfrom : intercitytripfromData,
				intercityto:intercitytriptoData,
				intercityDepdate:intercity_DdateData,
				//outRdate : outstation_RdateData,
				intercityNationality:intercityNationalityData,
				intercityAdults:intercityAdultsData,
				intercityChilds:intercityChildsData,
				intercityLuggages:intercityLuggagesData,
				intercityPickupAddress:intercityPickupAddressData,
				intercityDropAddress:intercityDropAddressData,
				intercityPickupH:intercityPickupTimeHData,
				intercityPickupM:intercityPickupTimeMData,
				userName:userName,
				mobileNumbers:mobileNumbers,
				emailId:emailId,
				userId:userId,
                                key:API_KEY
				},		 
				success: function(data){ 
				$('.fa-spin').remove();
				if(data.Status == 'Success'){
					$('.fa-spin').remove();
					alert("\"Thanks for choosing Hello42! Your bkg ref "+data.ref+" for "+data.Pickupdate+" "+data.pickupTime+". Total cost Rs. "+data.price+" Total kms "+data.total_km+"\"");	
					$('#105').attr("onclick"," return bookingintercityNewtrip(this.id);");
					//location.reload();
					console.log(data.distance);
				}else{
					alert(data.msg);
				}
			},
        /*success: function(data){
		
					$('.fa-spin').remove();
					alert("\"Thanks for choosing Hello42. Your bkg ref. "+data.ref+"\nPickUp Date: "+data.Pickupdate+" We will contact you shortly\"");
					//$('#105').attr("onclick"," return bookingintercityNewtrip(this.id);")
					},*/
        error: function(error){
		
				console.log(error);	
				
		}
	});

             //}
            }
	}
    }
	
///////////////// Function for Intercity Booking Ends Here//////

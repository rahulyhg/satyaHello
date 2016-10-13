/**
 * @author project manager
 */

/* var pickerOpts = {
 
    minDate: "new Date()"
	//{minDate:(0), maxDate:(365)}
}; */ 
var pickerOpts = {dateFormat: 'dd-mm-yy',minDate:(0), maxDate:(365)};
var countm=1;
//var pickerOptsout = {dateFormat: 'dd-mm-yy',minDate:(0), maxDate:(365)};
 
 $(document).ready(function(){
 $(function() {

    $( "#local_datepicker" ).datepicker(pickerOpts);
    //$( "#local_datepicker" ).datepicker("option","dateFormat","yy-mm-dd");
    $( "#point_date" ).datepicker(pickerOpts);
    //$( "#point_date" ).datepicker("option","dateFormat","yy-mm-dd");
    $( "#airPickupDate" ).datepicker(pickerOpts);
    //$( "#airPickupDate" ).datepicker("option","dateFormat","yy-mm-dd");
	
	
    $( "#outstation_Ddate" ).datepicker(
	
	{dateFormat: 'dd-mm-yy',minDate:(0), 
	maxDate:(365),
		onClose: function( selectedDate ) {
		$("#outstation_Rdate").datepicker("option", "minDate", selectedDate);
		 }
	
	});
    
	$( "#outstation_Rdate" ).datepicker(pickerOpts);

	//$( "#outstation_Ddate" ).datepicker("option","dateFormat","yy-mm-dd");
	
	
	$( "#intercity_Ddate" ).datepicker(pickerOpts);
    //$( "#intercity_Ddate" ).datepicker("option","dateFormat","yy-mm-dd");
	$( "#outstationone_Ddate" ).datepicker(pickerOpts);
    
    
    $( "#multicityDep2" ).datepicker();
    //$( "#multicityDep2" ).datepicker("option","dateFormat","yy-mm-dd");
    $( "#multicityDep1" ).datepicker();
    //$( "#multicityDep1" ).datepicker("option","dateFormat","yy-mm-dd");
    $( "#multicityDepature" ).datepicker();
    //$( "#multicityDepature" ).datepicker("option","dateFormat","yy-mm-dd");
    $( "#packages" ).datepicker(pickerOpts);
    //$( "#packages" ).datepicker("option","dateFormat","yy-mm-dd");
	$( "#bus_Ddate" ).datepicker(pickerOpts);
    //$( "#bus_Ddate" ).datepicker("option","dateFormat","yy-mm-dd");
	$( "#bus_Rdate" ).datepicker(pickerOpts);
    //$( "#bus_Rdate" ).datepicker("option","dateFormat","yy-mm-dd");
	$( "#deposit_date" ).datepicker(pickerOpts);
   
   // $( ".date" ).datepicker();
    
  }); 
 var site_url = jQuery.cookie('BaseUrl');
 });
 
 
 function mcClose(el){

	$(el).fadeOut('slow',function(){
					$(el).remove();
					el.next('.mc-unit').find('.from').val(el.prev('.mc-unit').find('.seldest').val());
					
				});
	
	
} 




function booking_point(){
	$("#map").hide();
	$("#booking_local").hide();
	$("#booking_point").show();
	$("#booking_airport").hide();
	$("#booking_intercity").hide();
	$("#booking_outstation").hide();
	$("#booking_bus").hide();
	$("#point").addClass("mactive");
	$("#local").removeClass("mactive");
	$("#airport").removeClass("mactive");
	$("#intercity").removeClass("mactive");
	$("#outstation").removeClass("mactive");
	$("#busbooking").removeClass("mactive");
	 $("#slider").show();
       // $("#map").hide();
	
}
function booking_airport(){
    
    /* if($.cookie('token') == null){
           
             $('#login_form2').bPopup({
            follow: [false, false], //x, y
            position: [150, 200] //x, y
           
        });
        return false; 
        } */
	$("#map").hide();
	$("#booking_local").hide();
	$("#booking_point").hide();
	$("#booking_airport").show();
	$("#booking_intercity").hide();
	$("#booking_outstation").hide();
	$("#booking_bus").hide();
	$("#local").removeClass("mactive");
	$("#point").removeClass("mactive");
	$("#intercity").removeClass("mactive");
	$("#airport").addClass("mactive");
	$("#outstation").removeClass("mactive");
	$("#busbooking").removeClass("mactive");
    $("#slider").show();
       
	
}
function booking_outstation(){
    /* if($.cookie('token') == null){
           
             $('#login_form2').bPopup({
            follow: [false, false], //x, y
            position: [150, 200] //x, y
           
        });
        return false; 
        } */
	$("#map").hide();
        $("#slider").show();
	$("#booking_local").hide();
	$("#booking_point").hide();
	$("#booking_airport").hide();
	$("#booking_intercity").hide();
	$("#booking_outstation").show();
	$("#booking_bus").hide();
	$("#local").removeClass("mactive");
	$("#point").removeClass("mactive");
	$("#airport").removeClass("mactive");
	$("#intercity").removeClass("mactive");
	$("#outstation").addClass("mactive");
	$("#busbooking").removeClass("mactive");
       
        
        
        
         var myOptions = {
          zoom: 4,
          center: new google.maps.LatLng(21.7679, 78.8718),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
	var mapObject = new google.maps.Map(document.getElementById("map"), myOptions);
	
	
	
}


//////// Function called when click on Intercity Starts Here///////////


function booking_intercity(){
	$("#map").hide();
        $("#slider").show();
	$("#booking_local").hide();
	$("#booking_point").hide();
	$("#booking_airport").hide();
	$("#booking_intercity").show();
	$("#booking_outstation").hide();
	$("#booking_bus").hide();
	$("#local").removeClass("mactive");
	$("#point").removeClass("mactive");
	$("#airport").removeClass("mactive");
	$("#intercity").addClass("mactive");
	$("#outstation").removeClass("mactive");
	$("#busbooking").removeClass("mactive");
       
        
        
        
         var myOptions = {
          zoom: 4,
          center: new google.maps.LatLng(21.7679, 78.8718),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
	var mapObject = new google.maps.Map(document.getElementById("map"), myOptions);
	
	
	
}

//////// Function called when click on Intercity Ends Here///////////



function booking_fourth(){
	
	
	alert("finsh");
		
}

function booking_bus(){
	$("#map").hide();
	$("#booking_local").hide();
	$("#booking_point").hide();
	$("#booking_airport").hide();
	$("#booking_intercity").hide();
	$("#booking_outstation").hide();
	$("#booking_bus").show();
	$("#point").removeClass("mactive");
	$("#local").removeClass("mactive");
	$("#airport").removeClass("mactive");
	$("#intercity").removeClass("mactive");
	$("#outstation").removeClass("mactive");
	$("#busbooking").addClass("mactive");
	 $("#slider").show();
       // $("#map").hide();
	
}

function booking_local(){
	$("#map").hide();
	$("#booking_local").show();
	$("#booking_point").hide();
	$("#booking_airport").hide();
	$("#booking_intercity").hide();
	$("#booking_outstation").hide();
	$("#booking_bus").hide();
	$("#local").addClass("mactive");
	$("#point").removeClass("mactive");
	$("#airport").removeClass("mactive");
	$("#intercity").removeClass("mactive");
	$("#outstation").removeClass("mactive");
	$("#busbooking").removeClass("mactive");
        $("#slider").show();
        
	
	
}


  
 function goingToAirport(){
 $("#airPickupLocation").show(); 
 $("#airDropLocationText").hide();  
 $("#airport_dropadds").hide(); 
 $("#airport_dropadds").hide();  
 $("#airport_drop_location").hide();
 $("#airport_pickup_location").hide();
 $("#airDropAddress").hide();
 $("#dropadds").hide(); 
 $("#pickupLoc").show();
 $("#dropLoc").hide();
 $("#pickupAdds").show();
 $("#goingToAirport").show();
  $("#airPickupLocation").addClass("AirPick");
  $("#airDropLocationText").removeClass("AirPick");  
 //$("#dropAdds").hide(); 
 //$("#ComingfromAirport").hide();
 $("#airPickupAddress").addClass("airPickupAddress");
  $("#airDropAddress").removeClass("airPickupAddress"); 
  $("#dropAdds").css("display","none");
 $("#airport_pickup_location").show();

 }
 
 function comingToAirport(){
 $("#airPickupLocation").hide();  
 $("#pickupLoc").hide();
 $("#airDropAddress").show(); 
 $("#dropadds").show();   
 $("#dropAdds").show();   
 $("#pickupLoc").hide();
 $("#airport_dropadds").show();
 $("#airport_drop_location").show();
 $("#airDropLocationText").show();
 $("#dropLoc").show();
 $("#pickupAdds").hide();
 //$("#dropAdds").show();
 //$("#ComingfromAirport").show();
 $("#goingToAirport").show();
 $("#airPickupLocation").val("");
 $("#airPickupAddress").val("");
 $("#airport_pickup_location").hide();
 $("#dropAdds").css("display","block");
 
 $("#airPickupLocation").removeClass("AirPick");   // ===for validation
 //$("#airDropLocationText").addClass("AirPick");
 $("#airPickupAddress").removeClass("airPickupAddress");
 $("#airDropAddress").addClass("airPickupAddress");
 }
 
  function roundtripbus(){
    

 $("#outReturnbus").show();
 
 }
 
   function onewaybus(){
    

 $("#outReturnbus").hide();
 
 }
 

 function roundtrip(){
    
 $("#addCityForm").hide();
 $("#oustation_return_text").show();
 $("#outReturn").show();
 $("#roundtrip").show();
 $("#countryMultiple").show();
 $("#countryoneway").hide();
  $("#multicity").hide();
  $("#addCitybutton").hide();
   $("#outstationdate").show();
  $("#addCity").hide();
  $("#outstation_return").show();
  $("#bookingGroundTrip").show();
  $("#bookingMultyCity").hide();
  $("#roundtriprow").show();
   $("#no_ofdays").show();
  
 }
function oneway(){
    $("#addCityForm").hide();
    $("#oustation_return_text").hide();
	$("#roundtriprow").hide();
    $("#outstation_text").show();
   $("#outReturn").hide();
 $("#roundtrip").show();
 $("#countryMultiple").show();
 $("#multicity").hide();
 $("#addCitybutton").hide();
 $("#addCity").hide();
 $("#outstation_return").hide();
 $("#outstation_Rdate").val("");
  $("#bookingGroundTrip").show();
  $("#bookingMultyCity").hide();
   $("#outstationdate").show();
   $("#countryMultiple").show();
   $("#no_ofdays").hide();
 }
 function multicity(){
     $(".datepick").datepicker(pickerOpts);
     $("#addCityForm").show();
   $("#outstation_return").hide();
   $("#mulityoutstation").hide();
   $("#oustation_return_text").hide();
   $("#outstation_text").hide();
    $("#outstationdate").hide();
   $("#outDeparture").hide();
   $("#outReturn").hide();
  $("#roundtrip").hide();
  $("#countryMultiple").hide();
  $("#multicity").show();
  $("#addCity").show();
  $("#addCitybutton").show();
  $("#bookingGroundTrip").hide();
  $("#bookingMultyCity").show();
  $("#countryoneway").hide();
 }
 
 var pickerOpts = {dateFormat: 'dd-mm-yy', minDate:(0), maxDate:(365)};
 
 
/* function bookingPointSubmit(id){
	var bookingtype=id;
	var pointCabIn=$("#pointCabIn").val();
	var pointNationality=$("#pointNationality").val();
	var pointAdults=$("#pointAdults").val();
	var pointChilds=$("#pointChilds").val();
	var pointLuggages=$("#pointLuggages").val();
	var pointPickupArea=$("#pointPickupArea").val();
	var pointDropArea=$("#pointDropArea").val();
	var pointAddress=$("#pointAddress").val();
	var point_date=$("#point_date").val();
	var pointH=$("#pointH").val();
	var pointS=$("#pointS").val();
	
	var cartypes="";
	var username="";
	var mobileNumber="";
	var emailId="";
    var site_url = jQuery.cookie('BaseUrl');
	
	
	var url = site_url+'/tunnel/menu/pointBooking';
	
	$.ajax({
        type: 'POST',
        url: url,
        dataType: "json",
		data : {
				bookingType:bookingtype,
				pointcabindata : pointCabIn,
				pointnationalitydata:pointNationality,
				pointadultsdata:pointAdults,
				pointchildsdata : pointChilds,
				pointluggagesdata:pointLuggages,
				pointpickupareadata:pointPickupArea,
				pointdropareadata:pointDropArea,
				pointaddressdata:pointAddress,
				pointdate:point_date,
				pointH:pointH,
				pointS:pointS,
				carType:cartypes,
				userName:username,
				mobileNumbers:mobileNumber,
				emailId:emailId,
				},		 
        success: function(data){
		
					alert("update");	
					
					},
        error: function(error){
		
				console.log(error);	
				
		}
	});
}
*/





/*function bookingmulitCity(){

var multicityfromData=$("#multicityfrom").val();
var multicitytoData=$("#multicityto").val();
var multicityDepData=$("#multicityDep").val();

var multicityfromData1=$("#multicityfrom1").val();
var multicitytoData1=$("#multicityto1").val();
var multicityDepData1=$("#multicityDep1").val();

var outstationNationalityData=$("#outstationNationality").val();
var outstationAdultsData=$("#outstationAdults").val();
var outstationChildsData=$("#outstationChilds").val();
var outstationLuggagesData=$("#outstationLuggages").val();
var outPickupAddressData=$("#outPickupAddress").val();
var outPickupTimeHData=$("#outPickupTimeH").val();
var outPickupTimeMData=$("#outPickupTimeM").val();
 var site_url = jQuery.cookie('BaseUrl');			
			
var url = site_url+'/tunnel/menu/outstationmulticity';
	
	$.ajax({
        type: 'POST',
        url: url,
        dataType: "json",
		data : {multicityfrom : multicityfromData,multicityto:multicitytoData,multicityDep:multicityDepData,
				multicityfrom1:multicityfromData1,multicityto1:multicitytoData1,multicityDep1:multicityDepData1,
				outNationality:outstationNationalityData,outAdults:outstationAdultsData,outChilds:outstationChildsData,
				outLuggages:outstationLuggagesData,outPickupAddress:outPickupAddressData,outPickupH:outPickupTimeHData,
				outPickupM:outPickupTimeMData},		 
        success: function(data){
		
					alert("update");	
					
					},
        error: function(error){
		
				console.log(error);	
				
		}
	});





}
*/

/*function bookingmulitCity(objName){
	
			var arr = new Array(objName);
			
            arr = document.getElementsByName(objName);
            
           // var lo = arr.length;
            for (var i = 0; i < arr.length; i++) {
            	
                a = "this is the total value";
                var obj = document.getElementsByName(objName).item(i);
                var ioo = obj.value; 
                var ass = String(ioo);
                alert(ass);
               // obj.value = a;
            }
           

}
*/
//var countm=2;
function addCitybutton(){
	
	//debugger;
	var w = $("#cityFrom0").val();
	// alert(w);
     var dateSelect = new Date();
     var dd = dateSelect.getDate();
     var m = dateSelect.getMonth()+1; //January is 0!
     var yyyy = dateSelect.getFullYear();
     if(m<10){
         var mm = '0'+m;
     }
       
/*  $("#addCityForm").append('<div class="row multiplerow" ><div class="Xbtn" onclick="mcClose($(this).parent())">X</div> <label id="" class="col-sm-21" style="color:#fff;">From<span style="color:#F00">*</span></label><div class="col-sm-4 col-md-4 col-lg-2" style="padding-top:5px !important;">
<input id="cityFrom'+count+'" type="text" class="form-control inputfild" ></div>
<label id="outstation_to"  class="col-sm-21 col-sm-21inter" style="display:block; color:#fff; width:4% !important; padding-top:10px !important;">To<span style="color:#F00">*</span></label><div class="col-md-4 col-lg-2" style="padding-top:5px;">
<select id="multicityapd'+count+'" name="multicityapd'+count+'" class=" form-control" onchange="citySelection2(this.id);"><option>Please Select</option><option value="Agra">Agra</option><option value="Ahmedabad">Ahmedabad</option><option value="Ajanta">Ajanta</option><option value="Ajmer">Ajmer</option><option value="Alleppey">Alleppey</option><option value="Amritsar">Amritsar</option><option value="Aurangabad">Aurangabad</option><option value="Ayodhya">Ayodhya</option><option value="Bandhavgarh">Bandhavgarh</option><option value="Bandhavgarh">Bandhavgarh</option><option value="Bangalore">Bangalore</option><option value="Barmer">Barmer</option><option value="Baroda">Baroda</option><option value="Belur">Belur</option><option value="Bharatpur">Bharatpur</option><option value="Bhavnagar">Bhavnagar</option><option value="Bhopal">Bhopal</option><option value="Bhubaneshwar">Bhubaneshwar</option><option value="Bhuj">Bhuj</option><option value="Bikaner">Bikaner</option><option value="Bodhgaya">Bodhgaya</option><option value="Bundi">Bundi</option><option value="Chennai">Chennai</option><option value="Chettinad">Chettinad</option><option value="Chittorgarh">Chittorgarh</option><option value="Cochin">Cochin</option><option value="Coimbatore">Coimbatore</option><option value="Coonoor">Coonoor</option><option value="Coorg">Coorg</option><option value="Corbett">Corbett</option><option value="Dalhousie">Dalhousie</option><option value="Darjeeling">Darjeeling</option><option value="Dehradun">Dehradun</option><option value="Delhi/NCR">Delhi/NCR</option><option value="Dharamsala">Dharamsala</option><option value="Dispur">Dispur</option><option value="Dudhwa">Dudhwa</option><option value="Ellora">Ellora</option><option value="Gandhi Nagar">Gandhi Nagar</option><option value="Gangtok">Gangtok</option><option value="Gondal">Gondal</option><option value="Gorakhpur">Gorakhpur</option><option value="Guwahati">Guwahati</option><option value="Gwalior">Gwalior</option><option value="Halebid">Halebid</option><option value="Hampi">Hampi</option><option value="Haridwar">Haridwar</option><option value="Hyderabad">Hyderabad</option><option value="Imphal">Imphal</option><option value="Indore">Indore</option><option value="Itanagar">Itanagar</option><option value="Jabalpur">Jabalpur</option><option value="Jaipur">Jaipur</option><option value="Jaisalmer">Jaisalmer</option><option value="Jodhpur">Jodhpur</option><option value="Kalimpong">Kalimpong</option><option value="Kanchipuram">Kanchipuram</option><option value="Kanha">Kanha</option><option value="Kanyakumari">Kanyakumari</option><option value="Kaziranga">Kaziranga</option><option value="Kedarnath">Kedarnath</option><option value="Khajuraho">Khajuraho</option><option value="Kodaikanal">Kodaikanal</option><option value="Kohima">Kohima</option><option value="Kolkata">Kolkata</option><option value="konark">konark</option><option value="Kota">Kota</option><option value="Kovalam">Kovalam</option><option value="Kullu">Kullu</option><option value="Kumarakom">Kumarakom</option><option value="Kumbhalgarh">Kumbhalgarh</option><option value="Kushinagar">Kushinagar</option><option value="Ladakh">Ladakh</option><option value="Leh">Leh</option><option value="Lucknow">Lucknow</option><option value="Madurai">Madurai</option><option value="Mahabalipuram">Mahabalipuram</option><option value="Manali">Manali</option><option value="Mandawa">Mandawa</option><option value="Mangalore">Mangalore</option><option value="Matheran">Matheran</option><option value="Mount Abu">Mount Abu</option><option value="Mukundgarh">Mukundgarh</option><option value="Mumbai">Mumbai</option><option value="Munnar">Munnar</option><option value="Mysore">Mysore</option><option value="Nagpur">Nagpur</option><option value="Nainital">Nainital</option><option value="Nalanda">Nalanda</option><option value="Nawalgarh">Nawalgarh</option><option value="Noida">Noida</option><option value="Ooty">Ooty</option><option value="Orchha">Orchha</option><option value="Panaji">Panaji</option><option value="Patna">Patna</option><option value="Periyar">Periyar</option><option value="Puri">Puri</option><option value="Pushkar">Pushkar</option><option value="Rajgir">Rajgir</option><option value="Ranakpur">Ranakpur</option><option value="Ranchi">Ranchi</option><option value="Ranthambore">Ranthambore</option><option value="Rishikesh">Rishikesh</option><option value="Rohtang Pass">Rohtang Pass</option><option value="Samode">Samode</option><option value="Sanchi">Sanchi</option><option value="Sariska">Sariska</option><option value="Sarnath">Sarnath</option><option value="Sasangir">Sasangir</option><option value="Sawai Madhopur">Sawai Madhopur</option><option value="Shekhawati">Shekhawati</option><option value="Shimla">Shimla</option><option value="Siliguri">Siliguri</option><option value="Silvassa">Silvassa</option><option value="Sravasti">Sravasti</option><option value="Srinagar">Srinagar</option><option value="Surat">Surat</option><option value="Tanjore">Tanjore</option><option value="Thrissur">Thrissur</option><option value="Trichy">Trichy</option><option value="Trivandrum">Trivandrum</option><option value="Udaipur">Udaipur</option><option value="Umaria">Umaria</option><option value="Utelia">Utelia</option><option value="Uttarkashi">Uttarkashi</option><option value="Vaishali">Vaishali</option><option value="Varanasi">Varanasi</option><option value="Varkala">Varkala</option><option value="Vizag">Vizag</option><option value="Zainabad">Zainabad</option><option value="Mathura">Mathura</option></select>
</div><label id="" class="col-sm-21"  style="color:#fff; margin-right:; padding-top:10px !important; margin-right:9px;">Departure<span style="color:#F00">*</span></label><div class="col-md-4 col-lg-2" style="padding-top:5px;"><input onclick="datePick();" id="datepickeCity'+count+'" type="text"  class="form-control datepick inputfild" placeholder="'+mm+'-'+dd+'-'+yyyy+'"></div><div class="col-sm-21 col-sm-21out" style="padding-top:5px !important;"><select id="outPickupTimeH'+count+'" name="outPickupTimeH" onchange="StationpickHour();" class="form-control"><option value="hrs">Hrs</option></select></div> <div class="col-sm-21 col-sm-21out" style="padding-top:5px !important;"><select id="outPickupTimeM'+count+'" name="outPickupTimeM" class=" form-control"><option value="min">Min</option></select></div><div class="col-sm-21 col-sm-21out" style="padding-top:5px !important;"><input type="textbox" name="Nofday" class="form-control inputfild" placeholder="No of Day"></div></div>');
 $(".datepick").datepicker(pickerOpts); */
 
 
  $("#addCityForm").append('<div class="row multiplerow" ><div class="Xbtn" onclick="mcClose($(this).parent())">X</div> <label id="" class="col-sm-21" style="color:#fff;">From<span style="color:#F00">*</span></label><div class="col-sm-4 col-md-4 col-lg-2" style="padding-top:5px !important;"><input id="cityFrom'+countm+'" type="text" class="form-control inputfild" ></div><label id="outstation_to"  class="col-sm-21 col-sm-21inter" style="display:block; color:#fff; width:4% !important; padding-top:10px !important;">To<span style="color:#F00">*</span></label><div class="col-md-4 col-lg-2" style="padding-top:5px;"><input id="multicityapd'+countm+'" class="targeth'+countm+' form-control inputfild" name="multicityapd'+countm+'"  onfocus="citySelect2('+countm+');"  type="text"></div><label id="" class="col-sm-21"  style="color:#fff; margin-right:; padding-top:10px !important; margin-right:9px;">Departure<span style="color:#F00">*</span></label><div class="col-md-4 col-lg-2" style="padding-top:5px;"><input onclick="datePick();" onchange="DaysBetween();" id="datepickeCity'+countm+'" type="text"  class="form-control datepick inputfild" placeholder="'+dd+'-'+mm+'-'+yyyy+'"></div><div class="col-sm-21 col-sm-21out" style="padding-top:5px !important;"><select id="outPickupTimeH'+countm+'" name="outPickupTimeH" onchange="StationpickHour();" class="form-control"><option value="hrs">Hrs</option></select></div> <div class="col-sm-21 col-sm-21out" style="padding-top:5px !important;"><select id="outPickupTimeM'+countm+'" name="outPickupTimeM" class=" form-control"><option value="min">Min</option></select></div><div class="col-sm-21 col-sm-21out" style="padding-top:5px !important;"><input type="textbox" name="Nofday" class="form-control inputfild" placeholder="No of Day"></div></div>');
 $(".datepick").datepicker(pickerOpts);
 
	//$('select[name^="multicityapd'+count+'"] option[value=w]').attr("selected","selected");
	//$('#multicityapd'+count+' option[value=w]').attr("selected", "selected");

 
 //var value=1;
 //debugger;
 var site_url = jQuery.cookie('BaseUrl');
 var url = site_url+'/tunnel/menu/allcities';
 // var rese = "multicityapd" ;
	// rese = rese.concat(count);
	 
	 
 
 /* $( "#rese").autocomplete({
      source: site_url+'/tunnel/menu/allcities2?key='+API_KEY,minLength: 2,
      focus: function( event, ui ) {
        $( "#rese" ).val( ui.item.label );
        return false;
      },
      select: function( event, ui ) {
        $(  "#rese" ).val( ui.item.label );
       
        $( "#rese"+"_value" ).val( ui.item.value ).trigger('change');
     
        return false;
      }
    }); */

	//alert(countm);
	//debugger;
	
  getCityDetails(countm);
 
  countm++;
  	//alert(countm);
 }
 var countvalue;
 function getCityDetails(countvalue)
 {   
 
 //alert(count);
// debugger;
	 var DropCity;
	 var Newpickcity;
	 var NewDropCity;
	// var value1=count;
	 var value2 = countvalue;
	 //alert(value2)
	 
	 var picity0 =document.getElementById("cityFrom0").value;
	 //alert(picity0);
	 var res = "multicityapd" ;
	 res = res.concat(value2);
	 //res = res.concat('2');
	 var piCity1 = ( "multicityapd" + value2 );

	//  debugger;
    //	document.getElementById("multicityapd2").value = picity0 ;
	//  document.getElementById(res).value = picity0 ;
	//  $("#res option[value='picity0']").attr("selected", "selected");
	//  debugger;
	
	
	if(value2 == 2){
			document.getElementById("multicityapd2").value = picity0;
			document.getElementById("cityFrom2").value = document.getElementById("multicityapd1").value ;
			document.getElementById("multicityapd1").placeholder = "Please Select";
	}else if(value2 == 3){
			document.getElementById("multicityapd3").value = picity0;
			document.getElementById("cityFrom3").value = document.getElementById("multicityapd2").value ;
			document.getElementById("multicityapd2").placeholder = "Please Select";
			
	}else if(value2 == 4){
			document.getElementById("multicityapd4").value = picity0;
			document.getElementById("cityFrom4").value = document.getElementById("multicityapd3").value ;
			document.getElementById("multicityapd3").placeholder = "Please Select";
	}else if(value2 == 5){
			document.getElementById("multicityapd5").value = picity0;
			document.getElementById("cityFrom5").value = document.getElementById("multicityapd4").value ;
			document.getElementById("multicityapd4").placeholder = "Please Select";
	} else if(value2== 6){
			document.getElementById("multicityapd6").value = picity0;
			document.getElementById("cityFrom6").value = document.getElementById("multicityapd5").value ;
			document.getElementById("multicityapd5").placeholder = "Please Select";
	}else if(value2 == 7){
			document.getElementById("multicityapd7").value = picity0;
			document.getElementById("cityFrom7").value = document.getElementById("multicityapd6").value ;
			document.getElementById("multicityapd6").placeholder = "Please Select";
	}
	 else if(value2 == 8){
			document.getElementById("multicityapd8").value = picity0;
			document.getElementById("cityFrom8").value = document.getElementById("multicityapd7").value ;
			document.getElementById("multicityapd7").placeholder = "Please Select";
	}else if(value2 == 9){
			document.getElementById("multicityapd9").value = picity0;
			document.getElementById("cityFrom9").value = document.getElementById("multicityapd8").value ;
			document.getElementById("multicityapd8").placeholder = "Please Select";
	}else if(value2 == 10){
			document.getElementById("multicityapd10").value = picity0;
			document.getElementById("cityFrom10").value = document.getElementById("multicityapd9").value ;
		    document.getElementById("multicityapd9").placeholder = "Please Select";
	}else if(value2 == 11){
			document.getElementById("multicityapd11").value = picity0;
			document.getElementById("cityFrom11").value = document.getElementById("multicityapd10").value ;
		    document.getElementById("multicityapd10").placeholder = "Please Select";
	}else if(value2 == 12){
			document.getElementById("multicityapd12").value = picity0;
			document.getElementById("cityFrom12").value = document.getElementById("multicityapd11").value ;
		    document.getElementById("multicityapd11").placeholder = "Please Select";
	}

	 
 }
 
  // auto complete for multicity
function citySelect2(id){
	//alert(id);
 var site_url = jQuery.cookie('BaseUrl');
    
    $( ".targeth"+id).autocomplete({
		
      source: site_url+'/tunnel/menu/outStationCities2?key='+API_KEY,minLength:0,delay:0,
      focus: function( event, ui ) {		 
        $( ".targeth"+id ).val( ui.item.label );
        return false;
      },
      select: function( event, ui ) {
        $( ".targeth"+id ).val( ui.item.label );
        $( ".targeth_value" ).val( ui.item.value );
 
        return false;
      }
    });
	
	$(".targeth"+id).keyup(function(){
		//alert(id);
		var gT =  id+1;
           // alert($(this).val());
		  var m = 10;
		  if(!document.getElementById("cityFrom"+gT)){
			 // alert(gT);
			   for(var i = gT; i<=m; i++){
			    $("#cityFrom"+i).val($(this).val());
		   }
		  }else{
			  $("#cityFrom"+gT).val($(this).val());
		  }
		  
        });
	

}



function citySelec3(id){
	//alert(id);
 var site_url = jQuery.cookie('BaseUrl');
    
    $( ".targeth"+id).autocomplete({
		
      source: site_url+'/tunnel/menu/outStationCities2?key='+API_KEY,minLength:0,delay:0,
      focus: function( event, ui ) {		 
        $( ".targeth"+id ).val( ui.item.label );
        return false;
      },
      select: function( event, ui ) {
        $( ".targeth"+id ).val( ui.item.label );
        $( ".targeth_value" ).val( ui.item.value );
       
 
        return false;
      }
    });
	
	$(".targeth"+id).keyup(function(){
		//alert(id);
		var gT =  id+1;
           // alert($(this).val());
		  var m = 10;
		  if(!document.getElementById("cityFrom"+gT)){
			 // alert(gT);
			   for(var i = gT; i<=m; i++){
			    $("#cityFrom"+i).val($(this).val());
		   }
		  }else{
			  $("#cityFrom"+gT).val($(this).val());
		  }
		  
        });
	

}


  // auto complete for multicity
function citySelefrm2(){
	//alert(id);
 var site_url = jQuery.cookie('BaseUrl');
    
	
	 var availableTags = [
      "New Delhi",
      "Noida",
      "Gurgaon",
      "Faridabad",
	  "Gaziyabad",
      "Greater Noida"
    ];
	
    $( ".targeth").autocomplete({
		
      source: availableTags,
      focus: function( event, ui ) {		 
        $( ".targeth" ).val( ui.item.label );
        return false;
      },
      select: function( event, ui ) {
        $( ".targeth" ).val( ui.item.label );
        $( ".targeth_value" ).val( ui.item.value );
       
 
        return false;
      }
    });
	
	$(".targeth").keyup(function(){
		//alert(id);
		
			  $("#multicityapdfixed").val($(this).val());
		 
		  
        });
	
	/* $( ".targeth"+id).blur(function(){
		var gT =  id+1;
		//debugger;
		var Bn = $(".targeth"+id).text();
		alert(Bn);
		//$("#cityFrom"+gT).val();
		
		
		
    //alert("This input field has lost its focus.");
});
 */
}
 
 
 
function bookingmulitCity(){
    
      /* var userId = $("#loginId").val();
        if(userId== ""){
            alert("please login");
        }
        else{  */
    
    
var cityFromData =[];
var cityToData = new Array();
var cityDateData = new Array();

for(var i =0; i<count; i++){
		
 
 var cityfrom = $("#cityFrom"+i).val();
 var cityto = $("#multicityapd"+i).val();
 var cityDate = $("#datepickeCity"+i).val();
 
 
// var info = cityfrom.serialize();
	//alert(cityfrom);
	//alert(cityto);
	//alert(cityDate);
//cityFromData=cityfrom;
	cityFromData.push(cityfrom);
	cityToData.push(cityto);
	cityDateData.push(cityDate);
}

//alert(cityFromData);
//alert(cityToData);
//alert(cityDateData);
//var myCityFrom = cityFromData;
var myCityFrom = JSON.parse(JSON.stringify(cityFromData));
var myCityTo = JSON.stringify(cityToData);
var myCityDate = JSON.stringify(cityDateData);
//var myCityFrom = JSON.stringify(cityFromData);




var national = $("#outstationNationality").val();	
var adults = $("#outstationAdults").val();	
var child = $("#outstationChilds").val();	
var luggages = $("#outstationLuggages").val();	
var adds = $("#outPickupAddress").val();	
var timeH = $("#outPickupTimeH").val();	
var timeM = $("#outPickupTimeM").val();	



var site_url = jQuery.cookie('BaseUrl');			
			
var url = site_url+'/tunnel/menu/outstationmulticitySecond';
	
	$.ajax({
		type: 'POST',
        url: url,
        dataType: "json",
		data : {
			
				countData:count,
				nationalData:national,
				adultsdata:adults,
				childData:child,
				luggagesData:luggages,
				cityfromData:cityFromData.serializeArray(),
				citytoData:myCityTo,
				addsdata:adds,
				cityDatedata:myCityDate,
				timeHData:timeH,
				timeMData:timeM,
                                key:API_KEY
				},	
					 
        success: function(data){
		
					alert(myCityFrom);	
					
					},
        error: function(error){
		
				console.log(error);	
				
		}
	});



    //}

	
}

function citySelection(){
	
var city =$("#multicityto option:selected").text();
var city1 =$("#multicityto1 option:selected").text();


$("#multicityfrom1").val(city);
$("#cityFrom0").val(city1);



}
function citySelection2(id){
	var suffix = id.replace ( /[^\d.]/g, '' );
	//suffix++;
	var neww = parseInt(suffix)+1;
	//alert(suffix);
//var hi =$("#cityFrom"+neww);
//alert(hi);
//$("#multicityapd"+suffix).val($("#cityFrom"+neww+" option:selected").text());	
$("#cityFrom"+neww).val($("#multicityapd"+suffix+" option:selected").text());	
}

function citySelection3(id){
	$("#cityFromr1").val($("#outRoundtripto option:selected").text());	
}

function citySelection4(value){
	$("#cityFromt1").val(value);
}

function citySelection5(vale){

	var selectedValue = vale;
	$('#multicityapd1 option').map(function () {
    if ($(this).val() == selectedValue) return this;
}).attr('selected', 'selected');
	
}




//function city(){
//	
//	var url = 'http://api.geonames.org/searchJSON?featureClass=P&lang=local&style=full&maxRows=12&country=in&username=hello42';
//	
//	$.ajax({
//		type: 'POST',
//        url: url,
//        dataType: "json",
//		
//					 
//        success: function(data){
//		
//					alert("update");	
//					
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

function  booking_immediately(){
   $("#pickdate").hide(); 
}

function booking_later(){
    
    $("#pickdate").show();
}



 

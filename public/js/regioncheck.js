/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




var geocoder;

$(document).ready(function(){
	//alert('Mohit');
	changelang('New Delhi','Delhi','India');
	
 /* if (navigator.geolocation) {
      
    navigator.geolocation.getCurrentPosition(successFunction, errorFunction);
}   */  
    
    
   var site_url = jQuery.cookie('BaseUrl');
   var strst = site_url.substring(0, site_url.length - 7);
    var url = site_url+'/tunnel/menu/dropdowncity';  
   
    
                $.ajax({
                    type: 'POST',
                    url: url,
                    dataType: "json",
                    data : {    
                                key:API_KEY,
				
				
				},		 
                                success: function(data){        
                                        var i=0;
                                       var dataItems='';
                                       // alert(ids);
                                       //var text = data.Data[i].city;
                                        var length = data.Nos;
                                       // alert(length);
                                        for( i=0; i<length;i++){
                                            
//                                             var ids= data.text[i].ids;
//                                             var texts = data.text[i].text;

                                         dataItems +='<li><a href="#" id="'+i+'">'+data.Data[i].city+'</a></li>';
                                            
                                            
                                         
                                         // alert(ids);
                                         }
					$("#cities_drop").html(dataItems);
					
					},
        error: function(error){
		
				console.log(error);
                                alert(console.log(error));
				
		}
	});

}) ; 

//Get the latitude and the longitude;
function successFunction(position) {
    var lat = position.coords.latitude;
    var lng = position.coords.longitude;
    console.log(lat+','+lng);
    codeLatLng(lat, lng)
}
function choose_cities(){
                                                  //data loaded in document.ready function
 $( "#cities_drop" ).toggle();
}

/*function errorFunction(){
    alert("Geocoder failed");
}*/

 
  function codeLatLng(lat, lng) {

    var latlng = new google.maps.LatLng(lat, lng);
    geocoder = new google.maps.Geocoder();
    geocoder.geocode({'latLng': latlng}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
      console.log(results)
        if (results[0]) {
         //formatted address
        // alert(results[0].formatted_address)
        //find country name
             for (var i=0; i<results[0].address_components.length; i++) {
            for (var b=0;b<results[0].address_components[i].types.length;b++) {

            //there are different types that might hold a city admin_area_lvl_1 usually does in come cases looking for sublocality type will be more appropriate
                if (results[0].address_components[i].types[b] == "administrative_area_level_1") {
                    //this is the object you are looking for
                  state= results[0].address_components[i];
                   
                }
                if (results[0].address_components[i].types[b] == "country") {
                    //this is the object you are looking for
                  country= results[0].address_components[i];
                    break;
                }
                if (results[0].address_components[i].types[b] == "locality") {
                    //this is the object you are looking for
                    city= results[0].address_components[i];
                 
                }
            }
        }
        //city data
        //alert(city.long_name + " " + state.long_name+" "+country.long_name);

 //changelang(city.long_name,state.long_name,country.long_name);
        } else {
          alert("No results found");
        }
      } else {
        alert("Geocoder failed due to: " + status);
      }
    });
  }



function changelang(cityId,stateId,countryId){
	//alert(cityId+stateId+countryId);
    var state = stateId;
    var country = countryId;
    var city =cityId;
     var site_url = jQuery.cookie('BaseUrl');
	  var strst = site_url.substring(0, site_url.length - 7);
    var url = site_url+'/tunnel/menu/alltext';
    
                    $.ajax({
                    type: 'POST',
                    url: url,
                    dataType: "json",
                    data : {    
                key:API_KEY,
				stateId:state,
                countryId:country,
				cityId:city
				},		 
        success: dataTextsuccess,        				
        error: function(error){
		
				console.log(error);
                              
				
		}
	});

}





function dataTextsuccess(data){

                              // alert(data.localfare.Economy['e1']);       
                                       var i=0;
                                       var dataItem ='';
                                       // alert(ids);
                                        //alert(texts);
                                        var length = data.Data.length;
                                       // alert(length);
/* 								   $('.economy').attr('title',"Minimum Charge: "+data.economy);
								   $('.sedan').attr('title'," Minimum Charge: "+data.sidan);
								   $('.prime').attr('title',"Minimum Charge: "+data.prime);
								   
								   $('.PTP_EconomyMinimumCharges').attr('title',"Minimum Charge: "+data.PTP_EconomyMinimumCharges);
								   $('.PTP_SedanMinimumCharges').attr('title'," Minimum Charge: "+data.PTP_SedanMinimumCharges);
								   $('.PTP_PrimeMinimumCharges').attr('title',"Minimum Charge: "+data.PTP_PrimeMinimumCharges);
 */                                       
										// for local fare
									   //4- 40 KM
									   
									   $('.loc4e1').html(data.localfare.Economy4['e1']);
									   $('.loc4e2').html(data.localfare.Economy4['e2']);
									   $('.loc4e3').html(data.localfare.Economy4['e3']);
									   $('.loc4e4').html(data.localfare.Economy4['e4']);
                                       
									   $('.loc4s1').html(data.localfare.Sedan4['s1']);
									   $('.loc4s2').html(data.localfare.Sedan4['s2']);
									   $('.loc4s3').html(data.localfare.Sedan4['s3']);
									   $('.loc4s4').html(data.localfare.Sedan4['s4']);
                                       
									   $('.loc4p1').html(data.localfare.Prime4['Prime1']);
									   $('.loc4p2').html(data.localfare.Prime4['Prime2']);
									   $('.loc4p3').html(data.localfare.Prime4['Prime3']);
									   $('.loc4p4').html(data.localfare.Prime4['Prime4']);
									   //alert(data.localfare.Economy8['e1']);
									   
									   // First Package will be select for LOCAL HIRE///
									   
									   /// Show Ist entry of localpackage STARTS HERE /// 
									   // 8- 80 km 
																		   
									   $('.loce1').html(data.localfare.Economy8['e1']);
									   $('.loce2').html(data.localfare.Economy8['e2']);
									   $('.loce3').html(data.localfare.Economy8['e3']);
									   $('.loce4').html(data.localfare.Economy8['e4']);
                                       
									   $('.locs1').html(data.localfare.Sedan8['s1']);
									   $('.locs2').html(data.localfare.Sedan8['s2']);
									   $('.locs3').html(data.localfare.Sedan8['s3']);
									   $('.locs4').html(data.localfare.Sedan8['s4']);
                                       
									   $('.locp1').html(data.localfare.Prime8['Prime1']);
									   $('.locp2').html(data.localfare.Prime8['Prime2']);
									   $('.locp3').html(data.localfare.Prime8['Prime3']);
									   $('.locp4').html(data.localfare.Prime8['Prime4']);
									   
									   /// Show Ist entry of localpackage ENDS HERE /// 
									   
									   // 12- 120 km
									   $('.loc12e1').html(data.localfare.Economy12['e1']);
									   $('.loc12e2').html(data.localfare.Economy12['e2']);
									   $('.loc12e3').html(data.localfare.Economy12['e3']);
									   $('.loc12e4').html(data.localfare.Economy12['e4']);
                                       
									   $('.loc12s1').html(data.localfare.Sedan12['s1']);
									   $('.loc12s2').html(data.localfare.Sedan12['s2']);
									   $('.loc12s3').html(data.localfare.Sedan12['s3']);
									   $('.loc12s4').html(data.localfare.Sedan12['s4']);
                                       
									   $('.loc12p1').html(data.localfare.Prime12['Prime1']);
									   $('.loc12p2').html(data.localfare.Prime12['Prime2']);
									   $('.loc12p3').html(data.localfare.Prime12['Prime3']);
									   $('.loc12p4').html(data.localfare.Prime12['Prime4']);
									   
									   // 16- 160 km
									   $('.loc16e1').html(data.localfare.Economy16['e1']);
									   $('.loc16e2').html(data.localfare.Economy16['e2']);
									   $('.loc16e3').html(data.localfare.Economy16['e3']);
									   $('.loc16e4').html(data.localfare.Economy16['e4']);
                                       
									   $('.loc16s1').html(data.localfare.Sedan16['s1']);
									   $('.loc16s2').html(data.localfare.Sedan16['s2']);
									   $('.loc16s3').html(data.localfare.Sedan16['s3']);
									   $('.loc16s4').html(data.localfare.Sedan16['s4']);
                                       
									   $('.loc16p1').html(data.localfare.Prime16['Prime1']);
									   $('.loc16p2').html(data.localfare.Prime16['Prime2']);
									   $('.loc16p3').html(data.localfare.Prime16['Prime3']);
									   $('.loc16p4').html(data.localfare.Prime16['Prime4']);
									   
									   // for PTP
									   
									   $('.pott1').html(data.PTP_forFront.Economy['ptpe5']);
									   $('.pott2').html(data.PTP_forFront.Economy['ptpe3']);
									   $('.pott3').html(data.PTP_forFront.Economy['ptpe1']);
									   var round = data.PTP_forFront.Economy['ptpe4']/'60';
									   $('.pott4').html(round.toFixed(2));
									   
									   $('.potte').html(data.PTP_forFront.Economy['ptpe2']);
									   
                                       
									   $('.pots1').html(data.PTP_forFront.Sedan['ptps3']);
									   $('.pots2').html(data.PTP_forFront.Sedan['ptps1']);
									   var roundpots3 = data.PTP_forFront.Sedan['ptps4']/'60';
									   $('.pots3').html(roundpots3.toFixed(2));
									   
									   $('.potts').html(data.PTP_forFront.Sedan['ptps2']);
                                       
									   $('.potp1').html(data.PTP_forFront.Prime['ptppr3']);
									   $('.potp2').html(data.PTP_forFront.Prime['ptppr1']);
									   var roundpotp3 = data.PTP_forFront.Prime['ptppr4']/'60';
									   $('.potp3').html(roundpotp3.toFixed(2));
									   //alert(data.PTP_forFront.Prime['ptppr2']);
									   $('.pottp').html(data.PTP_forFront.Prime['ptppr2']);
									   
                                       // for Outstation
									   
									   $('.oute1').html(data.EconomyOutStationF.Economy['eo1']);
									   $('.oute2').html(data.EconomyOutStationF.Economy['eo2']);
									   $('.oute3').html(data.EconomyOutStationF.Economy['eo3']);
									   $('.oute4').html(data.EconomyOutStationF.Economy['eo4']);
									   
									   $('.outs1').html(data.EconomyOutStationF.Sedan['so1']);
									   $('.outs2').html(data.EconomyOutStationF.Sedan['so2']);
									   $('.outs3').html(data.EconomyOutStationF.Sedan['so3']);
									   $('.outs4').html(data.EconomyOutStationF.Sedan['so4']);
									   
									   $('.outp1').html(data.EconomyOutStationF.Prime['po1']);
									   $('.outp2').html(data.EconomyOutStationF.Prime['po2']);
									   $('.outp3').html(data.EconomyOutStationF.Prime['po3']);
									   $('.outp4').html(data.EconomyOutStationF.Prime['po4']);
									   
									   // For AirPort Fare
									   // For 10 KM
									   $('.Air4e1').html(data.EconomyAirPortFare.Economy10['e1']);
									   $('.Air4e2').html(data.EconomyAirPortFare.Economy10['e2']);
									   $('.Air4e3').html(data.EconomyAirPortFare.Economy10['e3']);
									  
                                       
									   $('.Air4s1').html(data.EconomyAirPortFare.Sedan10['s1']);
									   $('.Air4s2').html(data.EconomyAirPortFare.Sedan10['s2']);
									   $('.Air4s3').html(data.EconomyAirPortFare.Sedan10['s3']);
									   
                                       
									   $('.Air4p1').html(data.EconomyAirPortFare.Prime10['Prime1']);
									   $('.Air4p2').html(data.EconomyAirPortFare.Prime10['Prime2']);
									   $('.Air4p3').html(data.EconomyAirPortFare.Prime10['Prime3']);
									   
									   //alert(data.EconomyAirPortFare.Economy20['e3']);
									   // 15 km
									   $('.Aire1').html(data.EconomyAirPortFare.Economy15['e1']);
									   $('.Aire2').html(data.EconomyAirPortFare.Economy15['e2']);
									   $('.Aire3').html(data.EconomyAirPortFare.Economy15['e3']);
									   
                                       
									   $('.Airs1').html(data.EconomyAirPortFare.Sedan15['s1']);
									   $('.Airs2').html(data.EconomyAirPortFare.Sedan15['s2']);
									   $('.Airs3').html(data.EconomyAirPortFare.Sedan15['s3']);
									   
                                       
									   $('.Airp1').html(data.EconomyAirPortFare.Prime15['Prime1']);
									   $('.Airp2').html(data.EconomyAirPortFare.Prime15['Prime2']);
									   $('.Airp3').html(data.EconomyAirPortFare.Prime15['Prime3']);
									   
									   
									   // 20 km
									   $('.Air12e1').html(data.EconomyAirPortFare.Economy20['e1']);
									   $('.Air12e2').html(data.EconomyAirPortFare.Economy20['e2']);
									   $('.Air12e3').html(data.EconomyAirPortFare.Economy20['e3']);
									   
                                       
									   $('.Air12s1').html(data.EconomyAirPortFare.Sedan20['s1']);
									   $('.Air12s2').html(data.EconomyAirPortFare.Sedan20['s2']);
									   $('.Air12s3').html(data.EconomyAirPortFare.Sedan20['s3']);
									   
                                       
									   $('.Air12p1').html(data.EconomyAirPortFare.Prime20['Prime1']);
									   $('.Air12p2').html(data.EconomyAirPortFare.Prime20['Prime2']);
									   $('.Air12p3').html(data.EconomyAirPortFare.Prime20['Prime3']);
									   
									   
									   // 30 km
									   $('.Air16e1').html(data.EconomyAirPortFare.Economy30['e1']);
									   $('.Air16e2').html(data.EconomyAirPortFare.Economy30['e2']);
									   $('.Air16e3').html(data.EconomyAirPortFare.Economy30['e3']);
									   
                                       
									   $('.Air16s1').html(data.EconomyAirPortFare.Sedan30['s1']);
									   $('.Air16s2').html(data.EconomyAirPortFare.Sedan30['s2']);
									   $('.Air16s3').html(data.EconomyAirPortFare.Sedan30['s3']);
									   
                                       
									   $('.Air16p1').html(data.EconomyAirPortFare.Prime30['Prime1']);
									   $('.Air16p2').html(data.EconomyAirPortFare.Prime30['Prime2']);
									   $('.Air16p3').html(data.EconomyAirPortFare.Prime30['Prime3']);
									   
									   
                                        for( i=0; i<length;i++){
                                            
                                             var ids= data.Data[i].text_id;
                                             var texts = data.Data[i].text;
                                            
                                            
                                         $("#"+ids+"").html(texts);
                                         
                                         // alert(ids);
                                         }
                                         
                                    // cab  
                                   
								   
								   var lengthlocalfare = data.localfare.length;
                                   
                                   //alert(lengthlocalfare);
                                  // var i=0;
                                       var dataItems='';
                                       // alert(ids);
                                       //var text = data.Data[i].city;
                                        var lengthcab = data.cabName.length;
                                       // alert(length);
                                       dataItems = '<option value="" >Select City</option>';
                                        for( var i=0; i<lengthcab;i++){
                                            
//                                             var ids= data.text[i].ids;
//                                             var texts = data.text[i].text;

                                         dataItems +='<option value="'+data.cabName[i].selected_region+'" id="local_p_select'+i+'">'+data.cabName[i].selected_region+'</option>';
                                            
                                            
                                         
                                         // alert(ids);
                                         }
										$("#localCabIn,#pointCabIn,#outRoundtripfrom,#intercitytripfrom,#packagefrom,#citylist").html(dataItems);
                                        $(".outstationCities").html(dataItems);
                                    //end cab/////
                                    
                                    
                                    //cab for//
                                     var dataItemscabfor='';
                                       // alert(ids);
                                       //var text = data.Data[i].city;
                                        var lengthcabfor = data.cabFor.length;
                                       // alert(length);
                                        for( i=0; i<lengthcabfor;i++){
                                            
//                                             var ids= data.text[i].ids;
//                                             var texts = data.text[i].text;

                                         dataItemscabfor +='<option value="'+data.cabFor[i].PackageName+'" id="localCabFor'+i+'">'+data.cabFor[i].PackageName+'</option>';
                                            
                                            
                                         
                                         // alert(ids);
                                         }
					$("#localCabFor").html(dataItemscabfor);
                                    
                                    //end cab for//
                                    
                                    
                                    
                                    //nationality//
                                     var dataItemsnationality='';
                                       // alert(ids);
                                       //var text = data.Data[i].city;
                                        var length = data.Nationality.length;
                                       // alert(length);
                                        for( i=0; i<length;i++){
                                            
//                                             var ids= data.text[i].ids;
//                                             var texts = data.text[i].text;
                                               
                                         dataItemsnationality +='<option value="'+data.Nationality[i].user_nationality+'" id="localCabFor'+i+'">'+data.Nationality[i].user_nationality+'</option>';
                                            
                                            
                                         
                                         // alert(ids);
                                         }
					$("#localNationality,#pointNationality,#airNationality,#outstationNationality").html(dataItemsnationality);
                                        
                                        
                                    
                                    //end nationality//
									
									
//masterpackage//
var dataItemsmasterpackage='';
var dataItemsmasterpackageinner='';
var length = data.Masterpackage.length;
for( i=0; i<length;i++){
dataItemsmasterpackage +='<label class="checkbox-inline checkboxtext col-md-2 col-sm-2new"><input type="checkbox" name="drAir[]" value="'+data.Masterpackage[i].Package_Id+'"/>'+data.Masterpackage[i].master_package_ref+'</label>';
dataItemsmasterpackageinner +='<div style="float:left;"><input type="checkbox" class="checkbox driver'+data.Masterpackage[i].Package_Id+'" name="drAir[]" value="'+data.Masterpackage[i].Package_Id+'" style="margin-right:3px;">'+data.Masterpackage[i].master_package_ref+'</div>';
}
$("#duty_type").html(dataItemsmasterpackage);
$("#duty_type_inner").html(dataItemsmasterpackageinner);
//end masterpackage//
                                    
                                    
                                    
                                    //numbers data//
                                    
                                     var dataItemsNos='';
                                       // alert(ids);
                                       //var text = data.Data[i].city;
                                        var length = data.Number.length;
                                         
                                       // alert(length);
                                 //=================start=======   
                                  
                              
                                    
                                      for( i=1; i<length;i++){
                                            
//                                             var ids= data.text[i].ids;
//                                             var texts = data.text[i].text;

                           dataItemsNos +='<option value="'+data.Number[i].nos+'" id="localCabFor'+i+'">'+data.Number[i].nos+'</option>';
                                            
                                            
                                         
                                         // alert(ids);
                                         }
                                          for(var j=0; j<length;j++){
                                            
//                                             var ids= data.text[i].ids;
//                                             var texts = data.text[i].text;

                           dataItem +='<option value="'+data.Number[j].nos+'" id="CabFor'+j+'">'+data.Number[j].nos+'</option>';
                                            
                                            
                                         
                                         // alert(ids);
                                         }
									$("#localChilds,#localLuggages,#pointChilds,#pointLuggages,#airChilds,#airLuggages,#outstationChilds,#intercityChilds,#outstationLuggages,#intercityLuggages").html(dataItem);
									$("#localAdults,#pointAdults,#airAdults,#outstationAdults,#intercityAdults,#localNoCars,#PointNoCars,#airportNoCars,#intercitynocars,#NoOfCar_outstation").html(dataItemsNos);
                                 
                                       
                                       
                                         
                                       
                                    
                                  //end numbers data//
                                    
                                    //=================end=======   
                                    
                                    
                                    
                                        
    
                                        // min//
                                      
                                       var dataItemsmin='';
                                       // alert(ids);
                                       //var text = data.Data[i].city;
                                        var length = data.Min.length;
                                       // alert(length);
                                       
// minutes option cutting start
                                       
                                      //  for( i=0; i<length;i++){
                                            
//                                             var ids= data.text[i].ids;
//                                             var texts = data.text[i].text;

                                      //   dataItemsmin +='<option value="'+data.Min[i].Min+'" id="localCabFor'+i+'">'+data.Min[i].Min+'</option>';
                                            
                                            
                                         
                                         // alert(ids);
                                //         }
				//	$("#localPikupTimemin").html(dataItemsmin);
                                //        $("#pointS").html(dataItemsmin);
                                //        $("#airPickupTimeS").html(dataItemsmin);
                                //        $("#outPickupTimeM").html(dataItemsmin);
                                      //end min//
                                      
					//datacabin(state,country);
                                        //datacabfor(state,country);
                                        //nationality(state,country);
                                        //nos(state,country);
//                                                                hours(state,country);
//                                                                mins(state,country);
                                                                terminals('Delhi','India');
                                                               outstationcities('Delhi','India');
//                                                                globalDataCheck();
   globalDataCheck();    
}
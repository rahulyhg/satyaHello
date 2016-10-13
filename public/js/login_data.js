/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




var geocoder;

$(document).ready(function(){
 if (navigator.geolocation) {
      
    navigator.geolocation.getCurrentPosition(successFunction, errorFunction);
}    
    
}) ; 

//Get the latitude and the longitude;
function successFunction(position) {
    var lat = position.coords.latitude;
    var lng = position.coords.longitude;
    console.log(lat+','+lng);
    codeLatLng(lat, lng)
}

function errorFunction(){
    alert("Geocoder failed");
}

 
  function codeLatLng(lat, lng) {

    var latlng = new google.maps.LatLng(lat, lng);
    geocoder = new google.maps.Geocoder();
    geocoder.geocode({'latLng': latlng}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
      console.log(results)
        if (results[0]) {
         //formatted address
         //alert(results[0].formatted_address)
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

 changelang(city.long_name,state.long_name,country.long_name);
        } else {
          alert("No results found");
        }
      } else {
        alert("Geocoder failed due to: " + status);
      }
    });
  }



function changelang(cityId,stateId,countryId){
    var state = stateId;
    var country = countryId;
    var city =cityId;
     var site_url = jQuery.cookie('BaseUrl');
	 var strst = site_url.substring(0, site_url.length - 7);
    var url = strst+'/tunnel/menu/alltext';
    
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
                                alert(console.log(error));
				
		}
	});

}





function dataTextsuccess(data){
   
                                      
                                       var i=0;
                                       var dataItem ='';
                                        var length = data.Data.length;
                                     
                                       
                                        for( i=0; i<length;i++){
                                            
                                             var ids= data.Data[i].text_id;
                                             var texts = data.Data[i].text;
                                            
                                            
                                         $("#"+ids+"").html(texts);
                                         
                                         }
                                         
}
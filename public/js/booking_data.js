/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


  



function datacabin(state,country){
    
var stateId= state;
var countryId= country;
//    
  var site_url = jQuery.cookie('BaseUrl');  
    var url = site_url+'/tunnel/menu/selectIn';  
     $.ajax({
                    type: 'POST',
                    url: url,
                    dataType: "json",
                    data : {    
                                key:API_KEY,
                                state:stateId,
                                country:countryId,
				
				
				},		 
                                success: function(data){        
                                        var i=0;
                                       var dataItems='';
                                       // alert(ids);
                                       //var text = data.Data[i].city;
                                        var length = data.cabName.length;
                                       // alert(length);
                                        for( i=0; i<length;i++){
                                            
//                                             var ids= data.text[i].ids;
//                                             var texts = data.text[i].text;

                                         dataItems +='<option value="'+data.cabName[i].selected_region+'" id="local_p_select'+i+'">'+data.cabName[i].selected_region+'</option>';
                                            
                                            
                                         
                                         // alert(ids);
                                         }
					$("#localCabIn,#pointCabIn,#outRoundtripfrom").html(dataItems);

					
					},
        error: function(error){
		
				console.log(error);
                                alert(console.log(error));
				
		}
	});
    
    
}
function datacabfor(state,country){
    
 var stateId= state;
var countryId= country;
 var site_url = jQuery.cookie('BaseUrl');  
var url = site_url+'/tunnel/menu/selectFor'; 
//    alert(text);
     /* $.ajax({
                    type: 'POST',
                    url: url,
                    dataType: "json",
                    data : {    
                                key:API_KEY,
				 state:stateId,
                                country:countryId,
				
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

                                         dataItems +='<option value="'+data.data[i].value_hours+'" id="localCabFor'+i+'">'+data.data[i].value_hours+'</option>';
                                            
                                            
                                         
                                         // alert(ids);
                                         }
					$("#localCabFor").html(dataItems);
					
					},
        error: function(error){
		
				console.log(error);
                                alert(console.log(error));
				
		}
	});
     */
    
}

function nationality(state,country){
    
     var stateId= state;
    var countryId= country;
 var site_url = jQuery.cookie('BaseUrl');  
var url = site_url+'/tunnel/menu/selectnationality'; 

     $.ajax({
                    type: 'POST',
                    url: url,
                    dataType: "json",
                    data : {    
                                key:API_KEY,
				 state:stateId,
                                country:countryId,
				
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

                                         dataItems +='<option value="'+data.data[i].user_nationality+'" id="localCabFor'+i+'">'+data.data[i].user_nationality+'</option>';
                                            
                                            
                                         
                                         // alert(ids);
                                         }
					$("#localNationality,#pointNationality,#airNationality,#outstationNationality").html(dataItems);
					},
        error: function(error){
		
				console.log(error);
                                alert(console.log(error));
				
		}
	});
    
    
}


function nos(state,country){
    
     var stateId= state;
    var countryId= country;
 var site_url = jQuery.cookie('BaseUrl');  
var url = site_url+'/tunnel/menu/selectNos'; 
//    alert(text);
     $.ajax({
                    type: 'POST',
                    url: url,
                    dataType: "json",
                    data : {    
                                key:API_KEY,
				 state:stateId,
                                country:countryId,
				
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

                                         dataItems +='<option value="'+data.data[i].nos+'" id="localCabFor'+i+'">'+data.data[i].nos+'</option>';
                                            
                                            
                                         
                                         // alert(ids);
                                         }
$("#localAdults,#localChilds,#localLuggages,#pointAdults,#pointChilds,#pointLuggages,#airAdults,#airChilds,#airLuggages,#outstationAdults,#outstationChilds,#outstationLuggages").html(dataItems);
					},
        error: function(error){
		
				console.log(error);
                                alert(console.log(error));
				
		}
	});
    
}

function hours(state,country){
    
    
         var stateId= state;
        var countryId= country;
 var site_url = jQuery.cookie('BaseUrl');  
var url = site_url+'/tunnel/menu/selectHours'; 
//    alert(text);
     $.ajax({
                    type: 'POST',
                    url: url,
                    dataType: "json",
                    data : {    
                                key:API_KEY,
				 state:stateId,
                                country:countryId,
				
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

                                         dataItems +='<option value="'+data.data[i].hours+'" id="localCabFor'+i+'">'+data.data[i].hours+'</option>';
                                            
                                            
                                         
                                         // alert(ids);
                                         }
					$("#localTimehours,#pointH,#airPickupTimeH,#intercityPickupTimeH,#outPickupTimeH").html(dataItems);                                       
					
					},
        error: function(error){
		
				console.log(error);
                                alert(console.log(error));
				
		}
	});
    
}

function  mins(state,country){
    
    
     var stateId= state;
        var countryId= country;
         var site_url = jQuery.cookie('BaseUrl');  
var url = site_url+'/tunnel/menu/selectMins'; 
//    alert(text);
     $.ajax({
                    type: 'POST',
                    url: url,
                    dataType: "json",
                    data : {    
                                key:API_KEY,
				 state:stateId,
                                country:countryId,
				
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

                                         dataItems +='<option value="'+data.data[i].Min+'" id="localCabFor'+i+'">'+data.data[i].Min+'</option>';
                                            
                                            
                                         
                                         // alert(ids);
                                         }
					$("#localPikupTimemin,#pointS,#airPickupTimeS,#intercityPickupTimeM,#outPickupTimeM").html(dataItems);					
					},
        error: function(error){
		
				console.log(error);
                                alert(console.log(error));
				
		}
	});
    
    
}



function  terminals(state,country){
    
    
         var stateId= state;
         var countryId= country;
         var site_url = jQuery.cookie('BaseUrl');  
         var url = site_url+'/tunnel/menu/terminals'; 
//    alert(text);
                 $.ajax({
                    type: 'POST',
                    url: url,
                    dataType: "json",
                    data : {    
                                key:API_KEY,
				 state:stateId,
                                country:countryId,
				
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

                                         dataItems +='<option value="'+data.data[i].terminals+'" id="localCabFor'+i+'">'+data.data[i].terminals+'</option>';
                                            
                                            
                                         
                                         // alert(ids);
                                         }
					$("#airAirportTerminals").html(dataItems);
                                       
					
					},
        error: function(error){
		
				console.log(error);
                                alert(console.log(error));
				
		}
	});
    
    
}

function  outstationcities(state,country){
    
    
         var stateId= state;
         var countryId= country;
         var site_url = jQuery.cookie('BaseUrl');  
         var url = site_url+'/tunnel/menu/outstationCities'; 
//    alert(text);
                 $.ajax({
                    type: 'POST',
                    url: url,
                    dataType: "json",
                    data : {    
                                key:API_KEY,
				 state:stateId,
                                country:countryId,
				
				},		 
                                success: function(data){        
                                        var i=0;
                                       var dataItems='';
                                       // alert(ids);
                                       //var text = data.Data[i].city;
                                        var length = data.Nos;
                                       // alert(length);
										dataItems +='<option value="">Please Select</option>';
                                        for( i=0; i<length;i++){
                                            
//                                             var ids= data.text[i].ids;
//                                             var texts = data.text[i].text;

                                         dataItems +='<option value="'+data.data[i].cities+'" id="localCabFor'+i+'">'+data.data[i].cities+'</option>';
                                            
                                            
                                         
                                         // alert(ids);
                                         }
					$("#intercitytripto,#outRoundtripto,#multicityapd0,#multicityapd1,#multicityapd2,#outRoundtripto2").html(dataItems);                                      					
					},
        error: function(error){
		
				console.log(error);
                                alert(console.log(error));
				
		}
	});
    
    
}






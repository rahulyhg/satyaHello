

function  calculateRoute(from,to) {
        // Center initialized to Naples, Italy
       alert("Yahoo");
        var myOptions = {
          zoom: 8,
          center: new google.maps.LatLng(28.6100, 77.2300),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        // Draw the map
        var mapObject = new google.maps.Map(document.getElementById("map2"), myOptions);
 
        var directionsService = new google.maps.DirectionsService();
        var directionsRequest = {
          origin: from,
          destination: to,
          travelMode: google.maps.DirectionsTravelMode.DRIVING,
          unitSystem: google.maps.UnitSystem.METRIC
        };
        directionsService.route(
          directionsRequest,
          function(response, status)
          {
            if (status == google.maps.DirectionsStatus.OK)
            {
              new google.maps.DirectionsRenderer({
                map: mapObject,
                directions: response
              });
            }
            else
              $("#error").append("Unable to retrieve your route<br />");
          }
        );
      }
      
      
      
     
 
        
     
      
      
      
      function mapping(id){
           var start = '28.6100, 77.2300';
       var to=    $("#"+id+" option:selected").text()
      // var p = '12.97 ,77.59'
       //calculateRoute(start,to);
   // = $("#id").val();
    //alert(o);
    
    //==========================hide map in each tab===============
    $(".mactive").click(function(){
        $("#map").hide();
    });
    
    
}
function hide(){
    
     $("#slider").hide();
        $("#map").show();
      
	//var mapObject = new google.maps.Map(document.getElementById("map"), myOptions);
}
i=0;
function mapping2(){
     
    //hide();
       $("#slider").hide();
     $("#map").show(); 
  
    
     var myOptions = {
          zoom: 16,
          center: new google.maps.LatLng(21.7679, 78.8718),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
	var mapObject = new google.maps.Map(document.getElementById("map"), myOptions);
       if( $('#pointDropArea').val()=="" || $('#pointAddress').val()==""){
        return false;   
       }
       
       //var to =$('#pointDropArea').val();
       //var start= $("#pointAddress").val();
       
       var pointPickupArea		=	$('#pointPickupArea').val();
       var pointCabIn			=	$('#pointCabIn').val();
       var pointDropArea 		=	$('#pointDropArea').val();
       var pointAddress			=	$("#pointAddress").val();
       var to 					=	pointDropArea +" "+ pointCabIn;
       var start				= 	pointAddress +" "+ pointPickupArea +" "+pointCabIn; 
       //alert(start);
       //address1 +" "+ localCabIn;
       
     // var p = '12.97 ,77.59'
    
 
        var directionsService = new google.maps.DirectionsService();
        var directionsRequest = {
          origin: start,
          destination: to,
          travelMode: google.maps.DirectionsTravelMode.DRIVING,
          unitSystem: google.maps.UnitSystem.METRIC
        };
        directionsService.route(
          directionsRequest,
          function(response, status)
          {
            if (status == google.maps.DirectionsStatus.OK)
            {
                
              new google.maps.DirectionsRenderer({
                map: mapObject,
                directions: response
              });
            }
            else
              $("#error").append("Unable to retrieve your route<br />");
          }
        );
   // = $("#id").val();
    //alert(o);
    
    
}

function multiMap(){
    
    var start = '28.6100, 77.2300';
    
    
for(var i=0;i<2;i++){
     var cityfrom = $("#cityFrom"+i).val();
     var cityto = $("#multicityapd"+i).val();

}


calculateRouteMulti(start,cityto);
    
}



function  calculateRouteMulti(start,cityto) {
        // Center initialized to Naples, Italy
         var myOptions = {
          zoom: 8,
          center: new google.maps.LatLng(28.6100, 77.2300),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        // Draw the map
        var mapObject = new google.maps.Map(document.getElementById("map"), myOptions);
 
        var directionsService = new google.maps.DirectionsService();
        
        var waypts = [];
        var dlen = cityto.length;
  for (var i = 0; i < dlen-1; i++) {
      waypts.push({
          location:cityto[i],
          stopover:true
      });
  }
        
        
        
        var directionsRequest = {
          origin: start,
          destination: start,
          waypoints: waypts,
          travelMode: google.maps.DirectionsTravelMode.DRIVING,
          unitSystem: google.maps.UnitSystem.METRIC
        };
        directionsService.route(
          directionsRequest,
          function(response, status)
          {
            if (status == google.maps.DirectionsStatus.OK)
            {
              new google.maps.DirectionsRenderer({
                map: mapObject,
                directions: response
              });
            }
            else
              $("#error").append("Unable to retrieve your route<br />");
          }
        );
      }
      
      
      /////////pickup area//////////////////////////////////////////////////////////////////////////////////
      
      
      
      
      
      
      
      
     
    

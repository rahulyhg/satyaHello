function call_ajax_normal(querystring,action,callback) {

	$.ajax({
		url :action,
		type : "POST",
		data : querystring,
		success : callback
	})

}      

function call_ajax_json(querystring,action,callback) {
  $.ajax({
    url :action,
    type : "POST",
    dataType : "json",
    data : querystring,
    success : callback
  })

}  

function setreschdtime(val){
        // var reschdtime = val;
        if(val == ""){
         alert("Rechedule time can not be null");
         $("#change").attr("disabled","disabled");
       }
       else $("#change").removeAttr("disabled");
      }


     var drvrrateresults = function(data){
     	alert("Rating given successfully");
     	// location.reload();
     } 

function drvrrate (bok_id,rating,action,callback,agent_id) {
// alert(rating);
		querystring = "BookingId="+bok_id+"&UserRating="+rating+"&agent_id="+agent_id;
		call_ajax_normal(querystring,action,callback);

}

 var ExecuteCancelresults = function(html){
       if(html.status == 1){

         alert("Booking cancelled Successfully");
         // console.log(html);
          // $(".clickme").each(function(){
          // var element = $(this).attr("data");
          // if(element == html.bokid){
          //   $(this).parent().nextAll().eq(7).contents().first()[0].textContent = html.text;
          // }
          // })
       }
       else alert(html.message);
    
    } 

function ExecuteCancel (bok_id,reason,other,id,action,callback,agent_id) {
// alert(bok_id+reason+other+id+action+callback);
if(reason == "0"){
    alert("Please select reason");
    // $("#otherreason").addAttr("disabled");
    $("#otherreason").hide();
    $("#ExeSubmit").attr("disabled","disabled");
    valid = false;
  }
else if(reason == "other"){
  if(other == ""){
    alert("Please Specify other reason");

    $("#ExeSubmit").attr("disabled","disabled");
    valid = false;

  }else {
    resn = other;
      valid = true;
    }
}
else {
  resn = reason;
    
      valid = true;
  
}

if(valid){
   querystring = "id="+bok_id+"&reason="+resn+"&agent_id="+agent_id;
    call_ajax_json(querystring,action,callback);
}
// alert(querystring);
    
      return false;

}

function shoattr (val) {
  // body...
  if(val!="") 
    { 
      $('#ExeSubmit').removeAttr('disabled');
    }
}
function Exereason (val) {
  
  if(val == "0"){
    alert("Please select reason");
    // $("#otherreason").addAttr("disabled");
    $("#otherreason").hide();
    $("#ExeSubmit").attr("disabled","disabled");
  }
  else if(val == "other"){
    $("#otherreason").show();
    $("#otherreason").removeAttr("disabled");
    $("#ExeSubmit").removeAttr("disabled");
  }
  else {
    // $("#otherreason").addAttr("disabled");
    $("#otherreason").hide();
    $("#ExeSubmit").removeAttr("disabled");

  }

}


 var ExeNoShowresults = function(html){
       // if(html.status == 1){

         alert(html.message);
         // console.log(html);
          // $(".clickme").each(function(){
          // var element = $(this).attr("data");
          // if(element == html.bokid){
          //   $(this).parent().nextAll().eq(7).contents().first()[0].textContent = html.text;
          // }
          // })
       // }
       // else alert(html.message);
    
    } 


function ExeNoShow (bok_id,action,callback,agent_id) {

// alert(bok_id+action+callback);
var querystring = "BookingId="+bok_id+"&agent_id="+agent_id;
  call_ajax_json(querystring,action,callback);
}

var ReDispatchresults = function(html){
       // if(html.status == 1){

         alert(html.message);
         // console.log(html);
          // $(".clickme").each(function(){
          // var element = $(this).attr("data");
          // if(element == html.bokid){
          //   $(this).parent().nextAll().eq(7).contents().first()[0].textContent = html.text;
          // }
          // })
       // }
       // else alert(html.message);
    
    } 

    
function ReDispatch (bok_id,action,callback,agent_id) {
    
    var querystring = "BookingId="+bok_id+"&agent_id="+agent_id;
  call_ajax_json(querystring,action,callback);
   
}

var drvrtypsurlresults = function (html) {
    $("#drvrtypes").html("");
  $.each(html[0],function(i,val){
    $("#drvrtypes").append("<tr><td>"+val.CabType+"</td><td><div style='width:30px;height:10px;background:"+val.color+"'></div></td></tr>");

  });

  $("#drvrlistings").html("");
  $("#drvrlistings").append("<option value=''> Select Driver </option>");
  $.each(html[1],function(i,val){

    $("#drvrlistings").append("<option value='"+val.UID+"' style='color:"+val.color+";'>"+val.vehicle+"---"+val.name+"---"+val.contact+" ("+val.distance+" Km)</option>");

  });

}

var addextrachrgresults = function (html) {

   $("#genbillresults").html("");
      // console.log(data.record);
      // $.each(data.totalbill,function(i,val){

      $("#genbillresults").html(html.totalbill);
      
      if(html.message!=0)
        {
          alert(html.message);
        }

      if(html.nexstep==1)
        {
          // $("#Generatebilltab").hide();
          $("#myModal").bPopup();
        }

      $(".slidingDiv").hide();
  $(".show_hide").show();
  
  $('.show_hide').click(function(){
  $(".slidingDiv").slideToggle();
  });
}


function addextrachrg (id,action,callback,othrs,BookingId_i,user_id) {

  var chngchrgsform = $("#chngchrgsform").serialize();
  // alert(chngchrgsform); return false;
  var appndstr = '"ParkingCharge = '+$("#Parking_Charge").val()+' Rs""TollTax = '+$("#Toll_Taxe").val()+' Rs""OtherCharge = '+$("#Other_Charge").val()+' Rs"';

// alert(action+callback);
     // var prevchrgs = $("#extrachrgesdesc").text(); 

   if(othrs == 1){ 
    var querystring = $("#"+id).serialize()+chngchrgsform+"&othrchrgs="+othrs+"&booking_id="+BookingId_i+"&driver_id="+user_id;
      var extrchrgsdata = $("#extrachrgesdesc").text();
      var str1 = new String( extrchrgsdata );
      var postn = str1.indexOf("ParkingCharge");
      var scndstr = "";
      var str = "";
      if(postn>0){
        scndstr = str1.substr(0,postn); 
        str = scndstr+appndstr;
      }
      else {
        str = extrchrgsdata+appndstr; 
      } 

      // $("#extrachrgesdesc").text("");
      $("#extrachrgesdesc").text(str);
      $("#myModal").modal('hide');
  }
    else {
      var querystring = chngchrgsform+"&othrchrgs=4&booking_id="+BookingId_i+"&driver_id="+user_id;
      alert(querystring);
    call_ajax_json(querystring,action,callback);
    }
    // console.log(querystring);


// "&hour="+total_trip_hours+"&km="+distance+"&pre_wait="+prewaitym+"&wait="+delay_time+
// "hour="+total_trip_hours+"&km="+distance+"&pre_wait="+prewaitym+"&wait="+delay_time+
    // alert(prevchrgs);
    return false;
}

var tripdoneadminresults = function(data){
  alert(data.message);
  $("#myModal").hide();
  $("#Generatebilltab").hide();
}

function tripdoneadmin (id,action,callback,exepaymnt) {

var bok_ref = $("#bokref").text();
var querystring = "bokref="+bok_ref; 
  // alert(id+action+callback+exepaymnt+bok_ref);
  call_ajax_json(querystring,action,callback);
}

var ExePymntByAdminResults = function(data){
  alert(data.message);
  //$("#myModal").hide();
}


function ExePymntByAdmin (id,val,action,callback,exepaymnt) {

  var bok_ref = $("#bokref").text();
  var querystring=$("#"+id).serialize()+"&bokref="+bok_ref;

  if(val ==0){

      alert("please select any one of payment type");
  
  }
  else {
    call_ajax_json(querystring,action,callback);
  }

  return false;
}


var sendclntinvoiceresults = function(data){
	//alert(1);
  alert(data.msg);
    //$("#Generateinvoice").bPopup();
      //$("#geninvoiceresults").html("");
}

function sendclntinvoice (bokid,action,callback) {

 var querystring = "bokid="+bokid;

 // alert(querystring+action+callback);
  call_ajax_json(querystring,action,callback);

}

// function calculateRoute(from, to) {
//         // Center initialized to Naples, Italy
//         var myOptions = {
//           zoom: 10,
//           center: new google.maps.LatLng(40.84, 14.25),
//           mapTypeId: google.maps.MapTypeId.ROADMAP
//         };
//         // Draw the map
//         var mapObject = new google.maps.Map(document.getElementById("map"), myOptions);

//         var directionsService = new google.maps.DirectionsService();
//         var directionsRequest = {
//           origin: from,
//           destination: to,
//           travelMode: google.maps.DirectionsTravelMode.DRIVING,
//           unitSystem: google.maps.UnitSystem.METRIC
//         };
//         directionsService.route(
//           directionsRequest,
//           function(response, status)
//           {
//             if (status == google.maps.DirectionsStatus.OK)
//             {
//               new google.maps.DirectionsRenderer({
//                 map: mapObject,
//                 directions: response
//               });
//             }
//             else
//               $("#error").append("Unable to retrieve your route<br />");
//           }
//         );
//       }
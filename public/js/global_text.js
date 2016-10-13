

function globalDataCheck(){
    
   
    var pointCabIn = $("#local_cabIn").text();
    var pointnationality = $("#local_nationality").text();
    var pointadults= $("#local_nosOfAdult").text();
    var pointchilds= $("#local_nosofChild").text();
    var pointluggages = $("#local_nosofLuggages").text();
    var pointpickupads=$("#local_pickup_address").text();
    var pointpickupdate = $("#local_pickup_date").text();
    var pointPickupTime = $("#local_pickup_time").text();
    var airPickUpLocs = $("#local_pickup_location").text();
    var outstationDeparture= $("#outstation_departure").text();
    var outstaionFrom = $("#outstation_from").text();
    var outstationTo = $("#outstation_to").text();
    var booking= $("#102").text();
    var booknow = $("#picknow_local").text();
    var booknow_conds = $("#picknow_condions_local").text();
    var booklater = $("#booklater_local").text();
    var booklater_cods = $("#booklater_conditions_local").text();
    var carechonomy = $("#local_economy").html();
    var carprime = $("#local_prime").html();
    var carhigher = $("#local_higher").html();
    var carTypes = $("#local_carsTypes").html();
	var pickupTime = $("#intercity_pickup_time").text();
	var dropArea   = $("#intercity_dropadds").text();
    
    $("#point_cabin").text(pointCabIn);
    $("#point_nationality,#airport_nationality,#outstaion_nationality").text(pointnationality);
    $("#point_nosAdults,#airport_adults,#intercity_adults,#oustation_adults").text(pointadults);
    $("#points_noschild,#airport_childs,#intercity_childs,#oustation_childs").text(pointchilds);
    $("#point_luggages,#airport_luggages,#intercity_luggages,#outstaion_luggages").text(pointluggages);
    $("#point_pickup_adds,#airport_pickup_adds,#intercity_pickupadds,#outstation_pickupadds").text(pointpickupads);
    $("#point_pickup_date,#airport_pickdate").text(pointpickupdate);
    $("#point_pickup_time,#airport_pickup_time,#outstation_pickup_time").text(pointPickupTime);
    $("#airport_pickup_locs").text(airPickUpLocs);
    $("#outstation_departure2,#outstation_departure3").text(outstationDeparture);
    $("#outstation_from2,#outstation_from3").text(outstaionFrom);
    $("#outstation_to2,#outstation_to3").text(outstationTo);
	$("#intercity_pickup_time").text(pickupTime);
	$("#intercity_dropadds").text(dropArea);
	

	
    $("#101,#103,#104,#105,#outstation_book,#point_booking,#make_book").text(booking);

    $("#picknow_point,#picknow_airport,#picknow_outstation").text(booknow);
    $("#booknowcondions_point,#picknowcondions_airport,#picknowcondions_outstation").text(booknow_conds);
    $("#booklater_point,#booklater_airport,#booklater_outstation").text(booklater);
    $("#booklaterconditions_point,#booklaterconditions_airport,#booklaterconditions_outstation").text(booklater_cods);

    
    $("#point_carsTypes,#air_carsTypes,#intercity_carsTypes,#outstation_carsTypes").text(carTypes);
    $("#point_economy,#air_economy,#intercity_economy,#out_economy").text(carechonomy);
    $("#point_prime,#air_prime,#intercity_prime,#out_prime").text(carprime);
    $("#point_higher,#air_higher,#intercity_higher,#out_higher").text(carhigher);
   
   
}
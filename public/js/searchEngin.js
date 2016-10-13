// JScript File
var totalPax = 0;
function TransValue() {
debugger

    var cityCountry;
    var CntryCodeCityCode;
    var CntryCodeCityCode_ARR;
    var CntryNameCityName_ARR;
    var areaFlag = "";
    var areaValue = "";
    //    if (SearchType == 'Domestic') {
    cityCountry = document.getElementById('HDestTo').value;
    //    }
    //    else {
    //        cityCountry = document.getElementById('HDestToInter').value;
    //    }
    //cityCountry = document.getElementById('HDestTo').value;
    CntryCodeCityCode = document.getElementById('cph_main_CntryCodeCityCode').value;

    CntryCodeCityCode = CntryCodeCityCode.replace(")", "");
    if ((cityCountry == "Please Enter your Destination")) {
        alert("Please enter destination");
        //        if (SearchType == 'Domestic') {
        document.getElementById('HDestTo').focus();
        //        }
        //        else {
        //            document.getElementById('HDestToInter').focus();
        //        }
        //document.getElementById('HDestTo').focus();
        return false;
    }

    txt_ChekinDate = document.getElementById("txt_ChekinDate");

    txt_ChekOut = document.getElementById("txt_ChekOut");

    if (trim(txt_ChekinDate.value) == "" || trim(txt_ChekinDate.value) == null) {

        alert("Checkin date cannot be blank");
        document.getElementById("txt_ChekinDate").focus();

        return false;

    }

    if (trim(txt_ChekOut.value) == "" || trim(txt_ChekOut.value) == null) {

        alert("Checkout date cannot be blank");
        document.getElementById("txt_ChekOut").focus();
        return false;

    }

    CntryNameCityName_ARR = cityCountry.split(":");
    CntryCodeCityCode_ARR = CntryCodeCityCode.split("#");
    var cntryCode = CntryCodeCityCode_ARR[1];
    var cityCode = CntryCodeCityCode_ARR[0];

    if (cntryCode == undefined) {
        alert("Please enter valid destination,In City:Country Formate");
        //        if (SearchType == 'Domestic') {
        document.getElementById('HDestTo').focus();
        //        }
        //        else {
        //            document.getElementById('HDestToInter').focus();
        //        }
        // document.getElementById('HDestTo').focus();
        return false;
    }
    
    document.getElementById('cph_main_hdnCntryCode').value = cntryCode;
    document.getElementById('cph_main_hdnCityCode').value = cityCode;
    document.getElementById('cph_main_hdnCityName').value = CntryNameCityName_ARR[0];
    document.getElementById('cph_main_hdnCntryName').value = CntryNameCityName_ARR[1];


    //areaFlag = document.frm_Search.cbo_Area.options[document.frm_Search.cbo_Area.selectedIndex].value;
    try {
        // if (areaFlag == "Area") {
        // var areaValue = document.frm_Search.cbo_All_Area.options[document.frm_Search.cbo_All_Area.selectedIndex].value;
        // }
    }
    catch (err) {
        //Handle errors here
    }
    return true;
}


function ChangeChildSearchPage(rownum) {

    try {

        var chdAge1, chdAge12, chdAge21, chdAge22, chdAge31, chdAge32, chdAge41, chdAge42
        var drpChild = document.getElementById('drpChilds' + rownum);
        var numchilds = drpChild.options[drpChild.selectedIndex].value;
        var ddRooms = document.getElementById('cph_main_roomsNo');
        var numRooms = ddRooms.options[ddRooms.selectedIndex].value;
        // countPax();
        //document.getElementById("spnTotalPax").innerHTML = numRooms * numchilds
        if (rownum == 1) {
            switch (numchilds) {
                case "0":

                    // document.getElementById("ChildAgeText1").style.display = 'none';
                    chdAge1 = document.getElementById("drpChdAge11");
                    chdAge1.style.display = 'none';
                    chdAge1.selectedIndex.value = 0;

                    chdAge12 = document.getElementById("drpChdAge12");
                    chdAge12.style.display = 'none';
                    chdAge12.selectedIndex = 0;
                    //document.getElementById("drpChdAge12").style.display = 'none';


                    //  document.getElementById("drpExtraBed1").style.display = 'none';
                    break;

                case "1":

                    //document.getElementById("ChildAgeText1").style.display = 'Block';
                    chdAge1 = document.getElementById("drpChdAge11");
                    chdAge1.style.display = 'Block';

                    chdAge1.selectedIndex = 0;

                    document.getElementById("drpChdAge12").style.display = 'none';

                    //  document.getElementById("drpExtraBed1").style.display = 'Block';

                    break;
                case "2":

                    //document.getElementById("ChildAgeText1").style.display = 'Block';
                    document.getElementById("drpChdAge11").style.display = 'Block';
                    document.getElementById("drpChdAge12").style.display = 'Block';
                    // document.getElementById("drpExtraBed1").style.display = 'Block';

                    break;

            }
        }
        else if (rownum == 2) {
            switch (numchilds) {
                case "0":

                    chdAge21 = document.getElementById("drpChdAge21");
                    chdAge21.style.display = 'none';
                    chdAge21.selectedIndex = 0;

                    //document.getElementById("drpChdAge21").style.display = 'none';

                    chdAge22 = document.getElementById("drpChdAge22");
                    chdAge22.style.display = 'none';
                    chdAge22.selectedIndex = 0;


                    document.getElementById("drpExtraBed2").style.display = 'none';

                    break;

                case "1":

                    chdAge21 = document.getElementById("drpChdAge21");
                    chdAge21.selectedIndex = 0;
                    chdAge21.style.display = 'Block';

                    //document.getElementById("drpChdAge21").style.display = 'Block';

                    chdAge22 = document.getElementById("drpChdAge22");
                    chdAge22.style.display = 'none';
                    chdAge22.selectedIndex = 0;
                    //document.getElementById("drpChdAge22").style.display = 'none';

                    //document.getElementById("drpExtraBed2").style.display = 'Block';

                    break;
                case "2":

                    chdAge21 = document.getElementById("drpChdAge21");
                    chdAge21.selectedIndex = 0;
                    chdAge21.style.display = 'Block';

                    chdAge22 = document.getElementById("drpChdAge22");
                    chdAge22.selectedIndex = 0;
                    chdAge22.style.display = 'Block';
                    //document.getElementById("drpChdAge21").style.display = 'Block';
                    //document.getElementById("drpChdAge22").style.display = 'Block';
                    //document.getElementById("drpExtraBed2").style.display = 'Block';

                    break;
            }
        }
        else if (rownum == 3) {
            switch (numchilds) {
                case "0":


                    chdAge31 = document.getElementById("drpChdAge31");
                    chdAge31.style.display = 'none';
                    chdAge31.selectedIndex = 0;

                    //document.getElementById("drpChdAge31").style.display = 'none';

                    chdAge32 = document.getElementById("drpChdAge32");
                    chdAge32.style.display = 'none';
                    chdAge32.selectedIndex = 0;

                    //document.getElementById("drpChdAge32").style.display = 'none';
                    //  document.getElementById("drpExtraBed3").style.display = 'none';

                    break;

                case "1":


                    document.getElementById("drpChdAge31").selectedIndex = 0;
                    document.getElementById("drpChdAge31").style.display = 'Block';

                    chdAge32 = document.getElementById("drpChdAge32");
                    chdAge32.style.display = 'none';
                    chdAge32.selectedIndex = 0;

                    //document.getElementById("drpChdAge32").style.display = 'none';

                    // document.getElementById("drpExtraBed3").style.display = 'Block';

                    break;
                case "2":

                    document.getElementById("drpChdAge31").selectedIndex = 0;
                    document.getElementById("drpChdAge31").style.display = 'Block';
                    document.getElementById("drpChdAge32").selectedIndex = 0;
                    document.getElementById("drpChdAge32").style.display = 'Block';
                    //  document.getElementById("drpExtraBed3").style.display = 'Block';

                    break;

            }
        }
        else if (rownum == 4) {
            switch (numchilds) {
                case "0":

                    chdAge41 = document.getElementById("drpChdAge41");
                    chdAge41.style.display = 'none';
                    chdAge41.selectedIndex = 0;

                    //document.getElementById("drpChdAge41").style.display = 'none';

                    chdAge42 = document.getElementById("drpChdAge42");
                    chdAge42.style.display = 'none';
                    chdAge42.selectedIndex = 0;

                    //document.getElementById("drpChdAge42").style.display = 'none';
                    // document.getElementById("drpExtraBed4").style.display = 'none';
                    break;

                case "1":

                    document.getElementById("drpChdAge41").selectedIndex = 0;
                    document.getElementById("drpChdAge41").style.display = 'Block';

                    chdAge42 = document.getElementById("drpChdAge42");
                    chdAge42.style.display = 'none';
                    chdAge42.selectedIndex = 0;

                    //document.getElementById("drpChdAge42").style.display = 'none';
                    // document.getElementById("drpExtraBed4").style.display = 'Block';

                    break;
                case "2":

                    document.getElementById("drpChdAge41").selectedIndex = 0;
                    document.getElementById("drpChdAge41").style.display = 'Block';
                    document.getElementById("drpChdAge42").selectedIndex = 0;
                    document.getElementById("drpChdAge42").style.display = 'Block';
                    //  document.getElementById("drpExtraBed4").style.display = 'Block';

                    break;
            }
        }
    }
    catch (e) {


    }


}


function showRoomsSearchPage() {

    
    var childageid = 1;
    ////////Remove All rows Start
    var ddRooms = document.getElementById('cph_main_roomsNo');
    var numRooms = ddRooms.options[ddRooms.selectedIndex].value;



    switch (numRooms) {
        case "1":

            document.getElementById("trRoom1").style.display = '';
            document.getElementById('drpChilds2').value = 0;
            document.getElementById("trRoom2").style.display = 'none';
            document.getElementById("trRoom3").style.display = 'none';
            document.getElementById("trRoom4").style.display = 'none';

            document.getElementById("drpChdAge21").style.display = 'none';
            document.getElementById("drpChdAge22").style.display = 'none';
            // document.getElementById("drpExtraBed2").style.display = 'none';

            // document.getElementById("spnTotalPax").innerHTML = totalPax;
            break;

        case "2":
            document.getElementById("trRoom1").style.display = '';
            document.getElementById("trRoom2").style.display = '';
            document.getElementById('drpChilds3').value = 0;
            document.getElementById("trRoom3").style.display = 'none';
            document.getElementById("trRoom4").style.display = 'none';

            document.getElementById("drpChdAge21").style.display = 'none';
            document.getElementById("drpChdAge22").style.display = 'none';
            // document.getElementById("drpExtraBed2").style.display = 'none';

            document.getElementById("drpChdAge31").style.display = 'none';
            document.getElementById("drpChdAge32").style.display = 'none';
            // document.getElementById("drpExtraBed3").style.display = 'none';

            // document.getElementById("spnTotalPax").innerHTML = totalPax ;


            break;
        case "3":
            document.getElementById("trRoom1").style.display = '';
            document.getElementById("trRoom2").style.display = '';
            document.getElementById("trRoom3").style.display = '';
            document.getElementById('drpChilds4').value = 0;
            document.getElementById("trRoom4").style.display = 'none';



            document.getElementById("drpChdAge21").style.display = 'none';
            document.getElementById("drpChdAge22").style.display = 'none';
            // document.getElementById("drpExtraBed2").style.display = 'none';

            document.getElementById("drpChdAge31").style.display = 'none';
            document.getElementById("drpChdAge32").style.display = 'none';
            // document.getElementById("drpExtraBed3").style.display = 'none';

            document.getElementById("drpChdAge41").style.display = 'none';
            document.getElementById("drpChdAge42").style.display = 'none';
            //  document.getElementById("drpExtraBed4").style.display = 'none';


            //document.getElementById("spnTotalPax").innerHTML = totalPax;
            break;
        case "4":

            document.getElementById("trRoom1").style.display = '';
            document.getElementById("trRoom2").style.display = '';
            document.getElementById("trRoom3").style.display = '';
            document.getElementById("trRoom4").style.display = '';
            document.getElementById('drpChilds4').value = 0;


            break;

    }

    // countPax();
    //  document.getElementById("spnTotalPax").innerHTML = totalPax;

}



function showRooms(a) {
    
    var childageid = 1;
    ////////Remove All rows Start
    //var ddRooms = document.getElementById('cph_main_roomsNo');
    var numRooms = a;//  ddRooms.options[ddRooms.selectedIndex].value;

    

    switch (numRooms) {
  
        case "1":

            document.getElementById("trRoom1").style.display = '';
            document.getElementById('drpChilds1').value = 0;
            document.getElementById("cph_main_A1").style.display = '';
            document.getElementById("trRoom2").style.display = 'none';
            document.getElementById("trRoom3").style.display = 'none';
            document.getElementById("trRoom4").style.display = 'none';
            document.getElementById("trRoom5").style.display = 'none';

            document.getElementById("cage1").style.display = 'none';
            document.getElementById("cage2").style.display = 'none';
            document.getElementById("cage2_1").style.display = 'none';
            document.getElementById("cage2_2").style.display = 'none';
            document.getElementById("cage3_1").style.display = 'none';
            document.getElementById("cage3_2").style.display = 'none';
            document.getElementById("cage4_1").style.display = 'none';
            document.getElementById("cage4_2").style.display = 'none';
            document.getElementById("cage5_1").style.display = 'none';
            document.getElementById("drpChdAge52").style.display = 'none';
            // document.getElementById("drpExtraBed2").style.display = 'none';

            // document.getElementById("spnTotalPax").innerHTML = totalPax;
            ShowRoomType(1);
            break;

        case "2":
            document.getElementById("trRoom1").style.display = '';
            document.getElementById("trRoom2").style.display = '';
            //document.getElementById('drpChilds1').value = 0;
            document.getElementById('drpChilds2').value = 0;
            document.getElementById("cph_main_A1").style.display = 'none';
            document.getElementById("divA2").style.display = '';
          
            document.getElementById("trRoom3").style.display = 'none';
            document.getElementById("trRoom4").style.display = 'none';
            document.getElementById("trRoom5").style.display = 'none';

            document.getElementById("cage2_1").style.display = 'none';
            document.getElementById("cage2_2").style.display = 'none';
            document.getElementById("cage3_1").style.display = 'none';
            document.getElementById("cage3_2").style.display = 'none';
            document.getElementById("cage4_1").style.display = 'none';
            document.getElementById("cage4_2").style.display = 'none';
            document.getElementById("cage5_1").style.display = 'none';
            document.getElementById("drpChdAge52").style.display = 'none';
            
            // document.getElementById("drpExtraBed2").style.display = 'none';

            // document.getElementById("drpExtraBed3").style.display = 'none';

            // document.getElementById("spnTotalPax").innerHTML = totalPax ;

            for (r = 1; r <= 2; r++) {
                ShowRoomType(r);
            }
            break;
        case "3":
           
            document.getElementById("trRoom1").style.display = '';
            document.getElementById("trRoom2").style.display = '';
            document.getElementById("trRoom3").style.display = '';
            document.getElementById("divA2").style.display = 'none';
            document.getElementById("DivA3").style.display = '';
          
           // document.getElementById('drpChilds1').value = 0;
           // document.getElementById('drpChilds2').value = 0;
            document.getElementById('drpChilds3').value = 0;
            document.getElementById("trRoom4").style.display = 'none';
            document.getElementById("trRoom5").style.display = 'none';



            //document.getElementById("drpChdAge21").style.display = 'none';
           // document.getElementById("drpChdAge22").style.display = 'none';
            // document.getElementById("drpExtraBed2").style.display = 'none';

           
            document.getElementById("cage3_1").style.display = 'none';
            document.getElementById("cage3_2").style.display = 'none';
            document.getElementById("cage4_1").style.display = 'none';
            document.getElementById("cage4_2").style.display = 'none';
            document.getElementById("cage5_1").style.display = 'none';
            document.getElementById("drpChdAge52").style.display = 'none';
            //  document.getElementById("drpExtraBed4").style.display = 'none';


            //document.getElementById("spnTotalPax").innerHTML = totalPax;
            for (r = 1; r <= 3; r++) {
                ShowRoomType(r);
            }
            break;
        case "4":

            document.getElementById("trRoom1").style.display = '';
            document.getElementById("trRoom2").style.display = '';
            document.getElementById("trRoom3").style.display = '';
            document.getElementById("trRoom4").style.display = '';
            document.getElementById("DivA3").style.display = 'none';
          
            document.getElementById("trRoom5").style.display = 'none';

          //  document.getElementById('drpChilds1').value = 0;
            //document.getElementById('drpChilds2').value = 0;
           // document.getElementById('drpChilds3').value = 0;
            document.getElementById('drpChilds4').value = 0;

           
            document.getElementById("cage4_1").style.display = 'none';
            document.getElementById("cage4_2").style.display = 'none';
            document.getElementById("cage5_1").style.display = 'none';
            document.getElementById("drpChdAge52").style.display = 'none';
            for (r = 1; r <= 4; r++) {
                ShowRoomType(r);
            }
            break;
        case "5":
          
            document.getElementById("trRoom1").style.display = '';
            document.getElementById("trRoom2").style.display = '';
            document.getElementById("trRoom3").style.display = '';
            document.getElementById("trRoom4").style.display = '';
            document.getElementById("trRoom5").style.display = '';

//            document.getElementById('drpChilds1').value = 0;
//            document.getElementById('drpChilds2').value = 0;
//            document.getElementById('drpChilds3').value = 0;
//            document.getElementById('drpChilds4').value = 0;
            document.getElementById('drpChilds5').value = 0;

            document.getElementById("drpChdAge51").style.display = 'none';
            document.getElementById("drpChdAge52").style.display = 'none';
            for (r = 1; r <= 5; r++) {
                ShowRoomType(r);
            }
            break;

    }

    // countPax();
    //  document.getElementById("spnTotalPax").innerHTML = totalPax;

}


function showModifiedRooms(a) {

    var childageid = 1;
    ////////Remove All rows Start
    //var ddRooms = a;  //document.getElementById('cph_main_roomsNo');
    var numRooms = a; //ddRooms.options[ddRooms.selectedIndex].value;



    switch (numRooms) {
        case "1":
            



            document.getElementById("cph_main_trRoom1").style.display = '';
            document.getElementById('cph_main_drpChilds1').value = 0;
            document.getElementById("cph_main_DivmodiA2").style.display = 'none';
            document.getElementById("cph_main_DivmodiA3").style.display = 'none';
            document.getElementById("cph_main_DivmodiA4").style.display = 'none';
            document.getElementById("cph_main_DivmodiA1").style.display = '';
            document.getElementById("cph_main_trRoom2").style.display = 'none';
            document.getElementById("cph_main_trRoom3").style.display = 'none';
            document.getElementById("cph_main_trRoom4").style.display = 'none';
            document.getElementById("cph_main_trRoom5").style.display = 'none';


            document.getElementById("cph_main_drpChdAge11").style.display = 'none';
            document.getElementById("cph_main_drpChdAge12").style.display = 'none';

            // document.getElementById("drpExtraBed2").style.display = 'none';

            // document.getElementById("spnTotalPax").innerHTML = totalPax;
            ShowRoomTypeModified(1);
            document.getElementById("cph_main_hdnNoofRooms").value = 1;
            break;

        case "2":
            
          
          
            document.getElementById("cph_main_trRoom1").style.display = '';
            document.getElementById("cph_main_trRoom2").style.display = '';
            document.getElementById("cph_main_DivmodiA1").style.display = 'none';
            document.getElementById("cph_main_DivmodiA2").style.display = '';
            document.getElementById("cph_main_DivmodiA3").style.display = 'none';
            document.getElementById("cph_main_DivmodiA4").style.display = 'none';
            document.getElementById("cph_main_trRoom3").style.display = 'none';
            document.getElementById("cph_main_trRoom4").style.display = 'none';
            document.getElementById("cph_main_trRoom5").style.display = 'none';
            document.getElementById('cph_main_drpChilds2').value = 0;
            


            document.getElementById("cph_main_drpChdAge21").style.display = 'none';
            document.getElementById("cph_main_drpChdAge22").style.display = 'none';
            document.getElementById("cph_main_hdnNoofRooms").value = numRooms;
            // document.getElementById("drpExtraBed2").style.display = 'none';

            //            document.getElementById("cph_main_drpChdAge31").style.display = 'none';
            //            document.getElementById("cph_main_drpChdAge32").style.display = 'none';
            // document.getElementById("drpExtraBed3").style.display = 'none';

            // document.getElementById("spnTotalPax").innerHTML = totalPax ;

            for (r = 1; r <= 2; r++) {
                ShowRoomTypeModified(r);
            }
            
            break;
        case "3":
           // document.getElementById("cph_main_r3").style.display = '';
            document.getElementById("cph_main_trRoom1").style.display = '';
            document.getElementById("cph_main_trRoom2").style.display = '';
            document.getElementById("cph_main_trRoom3").style.display = '';
            document.getElementById("cph_main_DivmodiA1").style.display = 'none';
            document.getElementById("cph_main_DivmodiA2").style.display = 'none';
            document.getElementById("cph_main_DivmodiA3").style.display = '';
            document.getElementById("cph_main_DivmodiA4").style.display = 'none';
            document.getElementById('cph_main_drpChilds3').value = 0;
            document.getElementById("cph_main_trRoom4").style.display = 'none';
            document.getElementById("cph_main_trRoom5").style.display = 'none';
            document.getElementById("cph_main_hdnNoofRooms").value = numRooms;



//            document.getElementById("cph_main_drpChdAge21").style.display = 'none';
//            document.getElementById("cph_main_drpChdAge22").style.display = 'none';
            // document.getElementById("drpExtraBed2").style.display = 'none';

            document.getElementById("cph_main_drpChdAge31").style.display = 'none';
            document.getElementById("cph_main_drpChdAge32").style.display = 'none';
            // document.getElementById("drpExtraBed3").style.display = 'none';

//            document.getElementById("cph_main_drpChdAge41").style.display = 'none';
//            document.getElementById("cph_main_drpChdAge42").style.display = 'none';
            //  document.getElementById("drpExtraBed4").style.display = 'none';


            //document.getElementById("spnTotalPax").innerHTML = totalPax;
            for (r = 1; r <= 3; r++) {
                ShowRoomTypeModified(r);
            }
            
            break;
        case "4":
          //  document.getElementById("cph_main_r4").style.display = '';
            document.getElementById("cph_main_trRoom1").style.display = '';
            document.getElementById("cph_main_trRoom2").style.display = '';
            document.getElementById("cph_main_trRoom3").style.display = '';
            document.getElementById("cph_main_trRoom4").style.display = '';
            document.getElementById("cph_main_trRoom5").style.display = 'none';
            document.getElementById('cph_main_drpChilds4').value = 0;
            document.getElementById("cph_main_DivmodiA1").style.display = 'none';
            document.getElementById("cph_main_DivmodiA2").style.display = 'none';
            document.getElementById("cph_main_DivmodiA3").style.display = 'none';
            document.getElementById("cph_main_DivmodiA4").style.display = '';


            document.getElementById("cph_main_drpChdAge41").style.display = 'none';
            document.getElementById("cph_main_drpChdAge42").style.display = 'none';
            document.getElementById("cph_main_hdnNoofRooms").value = numRooms;
            for (r = 1; r <= 4; r++) {
                ShowRoomTypeModified(r);
            }
            
            break;

        case "5":
          //  document.getElementById("cph_main_r5").style.display = '';
            document.getElementById("cph_main_trRoom1").style.display = '';
            document.getElementById("cph_main_trRoom2").style.display = '';
            document.getElementById("cph_main_trRoom3").style.display = '';
            document.getElementById("cph_main_trRoom4").style.display = '';
            document.getElementById("cph_main_trRoom5").style.display = '';
            document.getElementById('cph_main_drpChilds5').value = 0;

            document.getElementById("cph_main_drpChdAge51").style.display = 'none';
            document.getElementById("cph_main_drpChdAge52").style.display = 'none';

            for (r = 1; r <= 5; r++) {
                ShowRoomTypeModified(r);
            }
            break;

    }

    //  countPaxModified();
    //  document.getElementById("spnTotalPax").innerHTML = totalPax;

}

function countPaxModified() {
    var ddRooms = document.getElementById('cph_main_roomsNo');
    var numRooms = ddRooms.options[ddRooms.selectedIndex].value;

    var ddAdt1 = document.getElementById("cph_main_drpAdults1");
    var nAdt1 = ddAdt1.options[ddAdt1.selectedIndex].value;

    var ddAdt2 = document.getElementById("cph_main_drpAdults2");
    var nAdt2 = 0;

    var ddAdt3 = document.getElementById("cph_main_drpAdults3");
    var nAdt3 = 0;
    var ddAdt4 = document.getElementById("cph_main_drpAdults4");
    var nAdt4 = 0;



    var ddChd1 = document.getElementById("cph_main_drpChilds1");
    var nChd1 = ddChd1.options[ddChd1.selectedIndex].value;
    var ddChd2 = document.getElementById("cph_main_drpChilds2");
    var nChd2 = 0;
    var ddChd3 = document.getElementById("cph_main_drpChilds3");
    var nChd3 = 0;
    var ddChd4 = document.getElementById("cph_main_drpChilds4");
    var nChd4 = 0;


    if (numRooms == "2") {

        nAdt2 = ddAdt2.options[ddAdt2.selectedIndex].value;
        nChd2 = ddChd2.options[ddChd2.selectedIndex].value;
    }

    if (numRooms == "3") {

        nAdt2 = ddAdt2.options[ddAdt2.selectedIndex].value;
        nAdt3 = ddAdt3.options[ddAdt3.selectedIndex].value;
        nChd2 = ddChd2.options[ddChd2.selectedIndex].value;
        nChd3 = ddChd3.options[ddChd3.selectedIndex].value;
    }

    if (numRooms == "4") {

        nAdt2 = ddAdt2.options[ddAdt2.selectedIndex].value;
        nAdt3 = ddAdt3.options[ddAdt3.selectedIndex].value;
        nAdt4 = ddAdt4.options[ddAdt4.selectedIndex].value;

        nChd2 = ddChd2.options[ddChd2.selectedIndex].value;
        nChd3 = ddChd3.options[ddChd3.selectedIndex].value;
        nChd4 = ddChd4.options[ddChd4.selectedIndex].value;

    }







    totalPax = parseInt(nAdt1) + parseInt(nAdt2) + parseInt(nAdt3) + parseInt(nAdt4) + parseInt(nChd1) + parseInt(nChd2) + parseInt(nChd3) + parseInt(nChd4);
    // document.getElementById("spnTotalPax").innerHTML = totalPax;


}



function ChangeChildModified(rownum) {

    try {

        debugger;

        var chdAge1, chdAge12, chdAge21, chdAge22, chdAge31, chdAge32, chdAge41, chdAge42, chdAge51, chdAge52
        var drpChild = document.getElementById('cph_main_drpChilds' + rownum);
        var numchilds = drpChild.options[drpChild.selectedIndex].value;
        var ddRooms = document.getElementById('cph_main_roomsNo');
        var numRooms = ddRooms.options[ddRooms.selectedIndex].value;

        var drpAdults = document.getElementById('cph_main_drpAdults' + rownum);
        var numAdults = drpAdults.options[drpAdults.selectedIndex].value;
        // countPax();
        //document.getElementById("spnTotalPax").innerHTML = numRooms * numchilds
        if (rownum == 1) {
            switch (numchilds) {
                case "0":
                    debugger;
                    document.getElementById("cph_main_drpExtraBed" + rownum).style.display = 'none';
                    document.getElementById("lblbed" + rownum).style.display = 'none';
                    var lblShowHide = document.getElementById("cph_main_c1");
                    lblShowHide.style.visibility = 'hidden';
                    var lblShowHide1 = document.getElementById("cph_main_c2");
                    lblShowHide1.style.visibility = 'hidden';
                    chdAge1 = document.getElementById("cph_main_modicage" + rownum + "_1");
                    chdAge1.style.display = 'none';
                    chdAge1.selectedIndex.value = 0;

                    chdAge12 = document.getElementById("cph_main_modicage" + rownum + "_2");
                    chdAge12.style.display = 'none';
                    chdAge12.selectedIndex = 0;

                    document.getElementById("cph_main_drpExtraBed" + rownum).style.display = 'none';
                    document.getElementById("lblbed" + rownum).style.display = 'none';

                    break;

                case "1":
                    var lblShowHide = document.getElementById("cph_main_c1");
                    lblShowHide.style.visibility = 'visible';
                    chdAge1 = document.getElementById("cph_main_modicage" + rownum + "_1");
                    chdAge1.style.display = 'Block';

                    chdAge1.selectedIndex = 0;
                    var lblShowHide1 = document.getElementById("cph_main_c2");
                    lblShowHide1.style.visibility = 'hidden';
                    document.getElementById("cph_main_modicage" + rownum + "_2").style.display = 'none';

                    if (numAdults == 2) {
                        document.getElementById("cph_main_drpExtraBed" + rownum).style.display = 'block';
                        document.getElementById("lblbed" + rownum).style.display = 'block';
                    }
                    break;
                case "2":
                    var lblShowHide1 = document.getElementById("cph_main_c1");
                    lblShowHide1.style.visibility = 'visible';
                    document.getElementById("cph_main_modicage" + rownum + "_1").style.display = 'Block';
                    var lblShowHide1 = document.getElementById("cph_main_c2");
                    lblShowHide1.style.visibility = 'visible';
                    document.getElementById("cph_main_modicage" + rownum + "_2").style.display = 'Block';
                    if (numAdults == 2) {
                        document.getElementById("cph_main_drpExtraBed" + rownum).style.display = 'Block';
                        document.getElementById("lblbed" + rownum).style.display = 'block';
                    }
                    break;

            }
        }
        else if (rownum == 2) {
            switch (numchilds) {
                case "0":


                    var lblShowHide1 = document.getElementById("cph_main_c1");
                    lblShowHide1.style.visibility = 'hidden';
                    chdAge21 = document.getElementById("cph_main_modicage" + rownum + "_1");
                    chdAge21.style.display = 'none';
                    chdAge21.selectedIndex = 0;

                    var lblShowHide1 = document.getElementById("cph_main_c2");
                    lblShowHide1.style.visibility = 'hidden';
                    chdAge22 = document.getElementById("cph_main_modicage" + rownum + "_2");
                    chdAge22.style.display = 'none';
                    chdAge22.selectedIndex = 0;


                    document.getElementById("cph_main_drpExtraBed" + rownum).style.display = 'none';
                    document.getElementById("lblbed" + rownum).style.display = 'none';
                    break;

                case "1":
                    var lblShowHide1 = document.getElementById("cph_main_c1");
                    lblShowHide1.style.visibility = 'visible';
                    chdAge21 = document.getElementById("cph_main_modicage" + rownum + "_1");
                    chdAge21.selectedIndex = 0;
                    chdAge21.style.display = 'Block';
                    var lblShowHide1 = document.getElementById("cph_main_c2");
                    lblShowHide1.style.visibility = 'visible';
                    chdAge22 = document.getElementById("cph_main_modicage" + rownum + "_2");
                    chdAge22.style.display = 'none';
                    chdAge22.selectedIndex = 0;
                    if (numAdults == 2) {
                        document.getElementById("lblbed" + rownum).style.display = 'block';
                        document.getElementById("cph_main_drpExtraBed" + rownum).style.display = 'Block';
                    }

                    break;
                case "2":
                    var lblShowHide1 = document.getElementById("cph_main_c1");
                    lblShowHide1.style.visibility = 'visible';
                    chdAge21 = document.getElementById("cph_main_modicage" + rownum + "_1");
                    chdAge21.selectedIndex = 0;
                    chdAge21.style.display = 'Block';
                    var lblShowHide1 = document.getElementById("cph_main_c2");
                    lblShowHide1.style.visibility = 'visible';
                    chdAge22 = document.getElementById("cph_main_modicage" + rownum + "_2");
                    chdAge22.selectedIndex = 0;
                    chdAge22.style.display = 'Block';
                    if (numAdults == 2) {
                        document.getElementById("lblbed" + rownum).style.display = 'block';
                        document.getElementById("cph_main_drpExtraBed" + rownum).style.display = 'Block';
                    }

                    break;
            }
        }
        else if (rownum == 3) {
            switch (numchilds) {
                case "0":

                    var lblShowHide1 = document.getElementById("cph_main_c1");
                    lblShowHide1.style.visibility = 'hidden';
                    chdAge31 = document.getElementById("cph_main_modicage" + rownum + "_1");
                    chdAge31.style.display = 'none';
                    chdAge31.selectedIndex = 0;
                    var lblShowHide1 = document.getElementById("cph_main_c2");
                    lblShowHide1.style.visibility = 'hidden';
                    chdAge32 = document.getElementById("cph_main_modicage" + rownum + "_2");
                    chdAge32.style.display = 'none';
                    chdAge32.selectedIndex = 0;

                    document.getElementById("cph_main_drpExtraBed" + rownum).style.display = 'none';
                    document.getElementById("lblbed" + rownum).style.display = 'none';
                    break;

                case "1":

                    var lblShowHide1 = document.getElementById("cph_main_c1");
                    lblShowHide1.style.visibility = 'visible';
                    document.getElementById("cph_main_modicage" + rownum + "_1").selectedIndex = 0;
                    document.getElementById("cph_main_modicage" + rownum + "_1").style.display = 'Block';
                    var lblShowHide1 = document.getElementById("cph_main_c1");
                    lblShowHide1.style.visibility = 'hidden';
                    chdAge32 = document.getElementById("cph_main_modicage" + rownum + "_2");
                    chdAge32.style.display = 'none';
                    chdAge32.selectedIndex = 0;
                    if (numAdults == 2) {
                        document.getElementById("lblbed" + rownum).style.display = 'block';
                        document.getElementById("cph_main_drpExtraBed" + rownum).style.display = 'Block';
                    }

                    break;
                case "2":
                    var lblShowHide1 = document.getElementById("cph_main_c1");
                    lblShowHide1.style.visibility = 'visible';
                    var lblShowHide1 = document.getElementById("cph_main_c2");
                    lblShowHide1.style.visibility = 'visible';
                    document.getElementById("cph_main_modicage" + rownum + "_1").selectedIndex = 0;
                    document.getElementById("cph_main_modicage" + rownum + "_1").style.display = 'Block';
                    document.getElementById("cph_main_modicage" + rownum + "_2").selectedIndex = 0;
                    document.getElementById("cph_main_modicage" + rownum + "_2").style.display = 'Block';
                    if (numAdults == 2) {
                        document.getElementById("lblbed" + rownum).style.display = 'block';
                        document.getElementById("cph_main_drpExtraBed" + rownum).style.display = 'Block';
                    }

                    break;

            }
        }
        else if (rownum == 4) {
            switch (numchilds) {
                case "0":
                    var lblShowHide1 = document.getElementById("cph_main_c1");
                    lblShowHide1.style.visibility = 'hidden';
                    var lblShowHide1 = document.getElementById("cph_main_c2");
                    lblShowHide1.style.visibility = 'hidden';
                    chdAge41 = document.getElementById("cph_main_modicage" + rownum + "_1");
                    chdAge41.style.display = 'none';
                    chdAge41.selectedIndex = 0;

                    chdAge42 = document.getElementById("cph_main_modicage" + rownum + "_2");
                    chdAge42.style.display = 'none';
                    chdAge42.selectedIndex = 0;

                    document.getElementById("cph_main_drpExtraBed" + rownum).style.display = 'none';
                    document.getElementById("lblbed" + rownum).style.display = 'none';
                    break;

                case "1":
                    var lblShowHide1 = document.getElementById("cph_main_c1");
                    lblShowHide1.style.visibility = 'visible';
                    var lblShowHide1 = document.getElementById("cph_main_c2");
                    lblShowHide1.style.visibility = 'hidden';
                    document.getElementById("cph_main_modicage" + rownum + "_1").selectedIndex = 0;
                    document.getElementById("cph_main_modicage" + rownum + "_1").style.display = 'Block';

                    chdAge42 = document.getElementById("cph_main_modicage" + rownum + "_2");
                    chdAge42.style.display = 'none';
                    chdAge42.selectedIndex = 0;
                    if (numAdults == 2) {
                        document.getElementById("cph_main_drpExtraBed" + rownum).style.display = 'Block';
                        document.getElementById("lblbed" + rownum).style.display = 'block';
                    }

                    break;
                case "2":
                    var lblShowHide1 = document.getElementById("cph_main_c1");
                    lblShowHide1.style.visibility = 'visible';
                    var lblShowHide1 = document.getElementById("cph_main_c2");
                    lblShowHide1.style.visibility = 'visible';
                    document.getElementById("cph_main_modicage" + rownum + "_1").selectedIndex = 0;
                    document.getElementById("cph_main_modicage" + rownum + "_1").style.display = 'Block';
                    document.getElementById("cph_main_modicage" + rownum + "_2").selectedIndex = 0;
                    document.getElementById("cph_main_modicage" + rownum + "_2").style.display = 'Block';
                    if (numAdults == 2) {
                        document.getElementById("cph_main_drpExtraBed" + rownum).style.display = 'Block';
                        document.getElementById("lblbed" + rownum).style.display = 'block';
                    }
                    break;
            }
        }
        else if (rownum == 5) {
            switch (numchilds) {
                case "0":

                    chdAge51 = document.getElementById("cph_main_drpChdAge51");
                    chdAge51.style.display = 'none';
                    chdAge51.selectedIndex = 0;

                    chdAge52 = document.getElementById("cph_main_drpChdAge52");
                    chdAge52.style.display = 'none';
                    chdAge52.selectedIndex = 0;

                    document.getElementById("cph_main_drpExtraBed" + rownum).style.display = 'none';
                    document.getElementById("lblbed" + rownum).style.display = 'none';
                    break;

                case "1":

                    document.getElementById("cph_main_drpChdAge51").selectedIndex = 0;
                    document.getElementById("cph_main_drpChdAge51").style.display = 'Block';

                    chdAge52 = document.getElementById("cph_main_drpChdAge52");
                    chdAge52.style.display = 'none';
                    chdAge52.selectedIndex = 0;

                    if (numAdults == 2) {
                        document.getElementById("lblbed" + rownum).style.display = 'block';
                        document.getElementById("cph_main_drpExtraBed" + rownum).style.display = 'Block';
                    }
                    break;
                case "2":

                    document.getElementById("cph_main_drpChdAge51").selectedIndex = 0;
                    document.getElementById("cph_main_drpChdAge51").style.display = 'Block';
                    document.getElementById("cph_main_drpChdAge52").selectedIndex = 0;
                    document.getElementById("cph_main_drpChdAge52").style.display = 'Block';
                    if (numAdults == 2) {
                        document.getElementById("lblbed" + rownum).style.display = 'block';
                        document.getElementById("cph_main_drpExtraBed" + rownum).style.display = 'Block';
                    }
                    break;
            }
        }
    }
    catch (e) {


    }


}

function ChangeChild(rownum) {
    
    try {
        
        var chdAge1, chdAge12, chdAge21, chdAge22, chdAge31, chdAge32, chdAge41, chdAge42, chdAge51, chdAge52
        var drpChild = document.getElementById('drpChilds' + rownum);
        var numchilds = drpChild.options[drpChild.selectedIndex].value;
        var ddRooms = document.getElementById('cph_main_roomsNo');
        var numRooms = ddRooms.options[ddRooms.selectedIndex].value;

        var drpAdults = document.getElementById('drpAdults' + rownum);
        var numAdults = drpAdults.options[drpAdults.selectedIndex].value;
        // countPax();
        //document.getElementById("spnTotalPax").innerHTML = numRooms * numchilds
        if (rownum == 1) {
            switch (numchilds) {
                case "0":

                    // document.getElementById("ChildAgeText1").style.display = 'none';
                    chdAge1 = document.getElementById("cage1");
                    chdAge1.style.display = 'none';
                    chdAge1.selectedIndex.value = 0;

                    chdAge12 = document.getElementById("cage2");
                    chdAge12.style.display = 'none';
                    chdAge12.selectedIndex = 0;
                    //document.getElementById("drpChdAge12").style.display = 'none';


                    document.getElementById("drpExtraBed1").style.display = 'none';
                    break;

                case "1":

                    //document.getElementById("ChildAgeText1").style.display = 'Block';
                    chdAge1 = document.getElementById("cage1");
                    chdAge1.style.display = 'Block';

                    chdAge1.selectedIndex = 0;

                    document.getElementById("cage2").style.display = 'none';

                    if (numAdults == 2)
                        document.getElementById("drpExtraBed1").style.display = 'Block';

                    break;
                case "2":

                    //document.getElementById("ChildAgeText1").style.display = 'Block';
                    document.getElementById("cage1").style.display = 'Block';
                    document.getElementById("cage2").style.display = 'Block';
                    if (numAdults == 2)
                        document.getElementById("drpExtraBed1").style.display = 'Block';
                    // document.getElementById("drpExtraBed1").style.display = 'Block';

                    break;

            }
        }
        else if (rownum == 2) {
            switch (numchilds) {
                case "0":

                    chdAge21 = document.getElementById("cage2_1");
                    chdAge21.style.display = 'none';
                    chdAge21.selectedIndex = 0;

                    //document.getElementById("drpChdAge21").style.display = 'none';

                    chdAge22 = document.getElementById("cage2_2");
                    chdAge22.style.display = 'none';
                    chdAge22.selectedIndex = 0;


                    document.getElementById("drpExtraBed2").style.display = 'none';

                    break;

                case "1":

                    chdAge21 = document.getElementById("cage2_1");
                    chdAge21.selectedIndex = 0;
                    chdAge21.style.display = 'Block';

                    //document.getElementById("drpChdAge21").style.display = 'Block';

                    chdAge22 = document.getElementById("cage2_2");
                    chdAge22.style.display = 'none';
                    chdAge22.selectedIndex = 0;
                    if (numAdults == 2)
                        document.getElementById("drpExtraBed2").style.display = 'Block';
                    //document.getElementById("drpChdAge22").style.display = 'none';

                    //document.getElementById("drpExtraBed2").style.display = 'Block';

                    break;
                case "2":

                    chdAge21 = document.getElementById("cage2_1");
                    chdAge21.selectedIndex = 0;
                    chdAge21.style.display = 'Block';

                    chdAge22 = document.getElementById("cage2_2");
                    chdAge22.selectedIndex = 0;
                    chdAge22.style.display = 'Block';
                    if (numAdults == 2)
                        document.getElementById("drpExtraBed2").style.display = 'Block';
                    //document.getElementById("drpChdAge21").style.display = 'Block';
                    //document.getElementById("drpChdAge22").style.display = 'Block';
                    //document.getElementById("drpExtraBed2").style.display = 'Block';

                    break;
            }
        }
        else if (rownum == 3) {
            switch (numchilds) {
                case "0":


                    chdAge31 = document.getElementById("cage3_1");
                    chdAge31.style.display = 'none';
                    chdAge31.selectedIndex = 0;

                    //document.getElementById("drpChdAge31").style.display = 'none';

                    chdAge32 = document.getElementById("cage3_2");
                    chdAge32.style.display = 'none';
                    chdAge32.selectedIndex = 0;

                    //document.getElementById("drpChdAge32").style.display = 'none';
                      document.getElementById("drpExtraBed3").style.display = 'none';

                    break;

                case "1":


                    document.getElementById("cage3_1").selectedIndex = 0;
                    document.getElementById("cage3_1").style.display = 'Block';

                    chdAge32 = document.getElementById("cage3_2");
                    chdAge32.style.display = 'none';
                    chdAge32.selectedIndex = 0;
                    if (numAdults == 2)
                        document.getElementById("drpExtraBed3").style.display = 'Block';
                    //document.getElementById("drpChdAge32").style.display = 'none';

                    // document.getElementById("drpExtraBed3").style.display = 'Block';

                    break;
                case "2":

                    document.getElementById("cage3_1").selectedIndex = 0;
                    document.getElementById("cage3_1").style.display = 'Block';
                    document.getElementById("cage3_2").selectedIndex = 0;
                    document.getElementById("cage3_2").style.display = 'Block';
                    if (numAdults == 2)
                        document.getElementById("drpExtraBed3").style.display = 'Block';
                    //  document.getElementById("drpExtraBed3").style.display = 'Block';

                    break;

            }
        }
        else if (rownum == 4) {
            switch (numchilds) {
                case "0":

                    chdAge41 = document.getElementById("cage4_1");
                    chdAge41.style.display = 'none';
                    chdAge41.selectedIndex = 0;

                    //document.getElementById("drpChdAge41").style.display = 'none';

                    chdAge42 = document.getElementById("cage4_2");
                    chdAge42.style.display = 'none';
                    chdAge42.selectedIndex = 0;

                    //document.getElementById("drpChdAge42").style.display = 'none';
                     document.getElementById("drpExtraBed4").style.display = 'none';
                    break;

                case "1":

                    document.getElementById("cage4_1").selectedIndex = 0;
                    document.getElementById("cage4_1").style.display = 'Block';

                    chdAge42 = document.getElementById("cage4_2");
                    chdAge42.style.display = 'none';
                    chdAge42.selectedIndex = 0;
                    if (numAdults == 2)
                        document.getElementById("drpExtraBed4").style.display = 'Block';
                    //document.getElementById("drpChdAge42").style.display = 'none';
                    // document.getElementById("drpExtraBed4").style.display = 'Block';

                    break;
                case "2":

                    document.getElementById("cage4_1").selectedIndex = 0;
                    document.getElementById("cage4_1").style.display = 'Block';
                    document.getElementById("cage4_2").selectedIndex = 0;
                    document.getElementById("cage4_2").style.display = 'Block';
                    if (numAdults == 2)
                        document.getElementById("drpExtraBed4").style.display = 'Block';
                    //  document.getElementById("drpExtraBed4").style.display = 'Block';

                    break;
            }
        }
        else if (rownum == 5) {
            switch (numchilds) {
                case "0":

                    chdAge51 = document.getElementById("drpChdAge51");
                    chdAge51.style.display = 'none';
                    chdAge51.selectedIndex = 0;

                    //document.getElementById("drpChdAge41").style.display = 'none';

                    chdAge52 = document.getElementById("drpChdAge52");
                    chdAge52.style.display = 'none';
                    chdAge52.selectedIndex = 0;

                    //document.getElementById("drpChdAge42").style.display = 'none';
                     document.getElementById("drpExtraBed5").style.display = 'none';
                    break;

                case "1":

                    document.getElementById("drpChdAge51").selectedIndex = 0;
                    document.getElementById("drpChdAge51").style.display = 'Block';

                    chdAge52 = document.getElementById("drpChdAge52");
                    chdAge52.style.display = 'none';
                    chdAge52.selectedIndex = 0;

                    if (numAdults == 2)
                        document.getElementById("drpExtraBed5").style.display = 'Block';
                    //document.getElementById("drpChdAge42").style.display = 'none';
                    // document.getElementById("drpExtraBed4").style.display = 'Block';

                    break;
                case "2":

                    document.getElementById("drpChdAge51").selectedIndex = 0;
                    document.getElementById("drpChdAge51").style.display = 'Block';
                    document.getElementById("drpChdAge52").selectedIndex = 0;
                    document.getElementById("drpChdAge52").style.display = 'Block';
                    //  document.getElementById("drpExtraBed4").style.display = 'Block';
                    if (numAdults == 2)
                        document.getElementById("drpExtraBed5").style.display = 'Block';
                    break;
            }
        }
    }
    catch (e) {


    }


}



function ShowRoomTypeModified(rownum) {
    
    var drpAdults = document.getElementById('cph_main_drpAdults' + rownum);
    var numAdults = drpAdults.options[drpAdults.selectedIndex].value;
    var ddRooms = document.getElementById('cph_main_roomsNo');
    var numRooms = ddRooms.options[ddRooms.selectedIndex].value;

    var drpChilds = document.getElementById('cph_main_drpChilds' + rownum);
    var numChilds = drpChilds.options[drpChilds.selectedIndex].value;

    //countPax();
    switch (rownum) { 
        case rownum:
            {
                switch (numAdults) {
                    case "1":
                        {
                            document.getElementById('cph_main_drpRoomType' + rownum).style.display = 'none';
                            document.getElementById('cph_main_drpRoomType' + rownum + '_1').style.display = 'Block';
                            document.getElementById('cph_main_drpRoomType' + rownum + '_1').options[0].selected = true;
                            document.getElementById('cph_main_drpRoomType' + rownum + '_1').disabled = true;
                            document.getElementById('cph_main_drpExtraBed' + rownum).style.display = 'none';

                            if (numChilds == 0) {
                                document.getElementById('cph_main_drpChdAge' + rownum + '1').style.display = 'none';
                                document.getElementById('cph_main_drpChdAge' + rownum + '2').style.display = 'none';
                                document.getElementById('cph_main_drpExtraBed' + rownum).style.display = 'none';
                            }
                            else if (numChilds == 1) {
                                document.getElementById('cph_main_drpChdAge' + rownum + '1').style.display = 'Block';
                                document.getElementById('cph_main_drpChdAge' + rownum + '2').style.display = 'none';
                                document.getElementById('cph_main_drpExtraBed' + rownum).style.display = 'none';
                            }
                            else if (numChilds == 2) {
                                document.getElementById('cph_main_drpChdAge' + rownum + '1').style.display = 'Block';
                                document.getElementById('cph_main_drpChdAge' + rownum + '2').style.display = 'Block';
                                document.getElementById('cph_main_drpExtraBed' + rownum).style.display = 'none';
                            }
                            break;
                        }
                    case "2":
                        {
                            document.getElementById('cph_main_drpRoomType' + rownum).style.display = 'Block';
                            document.getElementById('cph_main_drpRoomType' + rownum).disabled = false;
                            document.getElementById('cph_main_drpRoomType' + rownum + '_1').style.display = 'none';

                            if (numChilds == 0) {
                                document.getElementById('cph_main_drpChdAge' + rownum + '1').style.display = 'none';
                                document.getElementById('cph_main_drpChdAge' + rownum + '2').style.display = 'none';
                                document.getElementById('cph_main_drpExtraBed' + rownum).style.display = 'none';
                            }
                            else if (numChilds == 1) {
                                document.getElementById('cph_main_drpChdAge' + rownum + '1').style.display = 'Block';
                                document.getElementById('cph_main_drpChdAge' + rownum + '2').style.display = 'none';
                                document.getElementById('cph_main_drpExtraBed' + rownum).style.display = 'Block';
                            }
                            else if (numChilds == 2) {
                                document.getElementById('cph_main_drpChdAge' + rownum + '1').style.display = 'Block';
                                document.getElementById('cph_main_drpChdAge' + rownum + '2').style.display = 'Block';
                                document.getElementById('cph_main_drpExtraBed' + rownum).style.display = 'Block';
                            }
                            //                            if (numChilds != 0)
                            //                                document.getElementById('drpExtraBed' + rownum).style.display = 'Block';
                            break;
                        }
                    case "3":
                        {
                            document.getElementById('cph_main_drpRoomType' + rownum).style.display = 'none';
                            document.getElementById('cph_main_drpRoomType' + rownum + '_1').style.display = 'Block';
                            document.getElementById('cph_main_drpRoomType' + rownum + '_1').options[1].selected = true;
                            document.getElementById('cph_main_drpRoomType' + rownum + '_1').disabled = true;
                            document.getElementById('cph_main_drpExtraBed' + rownum).style.display = 'none';

                            if (numChilds == 0) {
                                document.getElementById('cph_main_drpChdAge' + rownum + '1').style.display = 'none';
                                document.getElementById('cph_main_drpChdAge' + rownum + '2').style.display = 'none';
                                document.getElementById('cph_main_drpExtraBed' + rownum).style.display = 'none';
                            }
                            else if (numChilds == 1) {
                                document.getElementById('cph_main_drpChdAge' + rownum + '1').style.display = 'Block';
                                document.getElementById('cph_main_drpChdAge' + rownum + '2').style.display = 'none';
                                document.getElementById('cph_main_drpExtraBed' + rownum).style.display = 'none';
                            }
                            else if (numChilds == 2) {
                                document.getElementById('cph_main_drpChdAge' + rownum + '1').style.display = 'Block';
                                document.getElementById('cph_main_drpChdAge' + rownum + '2').style.display = 'Block';
                                document.getElementById('cph_main_drpExtraBed' + rownum).style.display = 'none';
                            }
                            break;
                        }
                    case "4":
                        {
                            document.getElementById('cph_main_drpRoomType' + rownum).style.display = 'none';
                            document.getElementById('cph_main_drpRoomType' + rownum + '_1').style.display = 'Block';
                            document.getElementById('cph_main_drpRoomType' + rownum + '_1').options[2].selected = true;
                            document.getElementById('cph_main_drpRoomType' + rownum + '_1').disabled = true;
                            document.getElementById('cph_main_drpExtraBed' + rownum).style.display = 'none';

                            if (numChilds == 0) {
                                document.getElementById('cph_main_drpChdAge' + rownum + '1').style.display = 'none';
                                document.getElementById('cph_main_drpChdAge' + rownum + '2').style.display = 'none';
                                document.getElementById('cph_main_drpExtraBed' + rownum).style.display = 'none';
                            }
                            else if (numChilds == 1) {
                                document.getElementById('cph_main_drpChdAge' + rownum + '1').style.display = 'Block';
                                document.getElementById('cph_main_drpChdAge' + rownum + '2').style.display = 'none';
                                document.getElementById('cph_main_drpExtraBed' + rownum).style.display = 'none';
                            }
                            else if (numChilds == 2) {
                                document.getElementById('cph_main_drpChdAge' + rownum + '1').style.display = 'Block';
                                document.getElementById('cph_main_drpChdAge' + rownum + '2').style.display = 'Block';
                                document.getElementById('cph_main_drpExtraBed' + rownum).style.display = 'none';
                            }
                            break;
                        }
                }
            }
    }
}


function ShowRoomType(rownum) {

    var drpAdults = document.getElementById('drpAdults' + rownum);
    var numAdults = drpAdults.options[drpAdults.selectedIndex].value;
    var ddRooms = document.getElementById('cph_main_roomsNo');
    var numRooms = ddRooms.options[ddRooms.selectedIndex].value;

    var drpChilds = document.getElementById('drpChilds' + rownum);
    var numChilds = drpChilds.options[drpChilds.selectedIndex].value;

    countPax();
    switch (rownum) {
        case rownum:
            {
                switch (numAdults) {
                    case "1":
                        {
                            document.getElementById('drpRoomType' + rownum).style.display = 'none';
                            document.getElementById('drpRoomType' + rownum + '_1').style.display = 'Block';
                            document.getElementById('drpRoomType' + rownum + '_1').options[0].selected = true;
                            document.getElementById('drpRoomType' + rownum + '_1').disabled = true;
                            document.getElementById('drpExtraBed' + rownum).style.display = 'none';

                            if (numChilds == 0) {
                                document.getElementById('drpChdAge'  + rownum + '1').style.display = 'none';
                                document.getElementById('drpChdAge' + rownum + '2').style.display = 'none';
                                document.getElementById('drpExtraBed' + rownum).style.display = 'none';
                            }
                            else if (numChilds == 1) {
                                document.getElementById('drpChdAge' + rownum + '1').style.display = 'Block';
                                document.getElementById('drpChdAge' + rownum + '2').style.display = 'none';
                                document.getElementById('drpExtraBed' + rownum).style.display = 'none';
                            }
                            else if (numChilds == 2) {
                                document.getElementById('drpChdAge' + rownum + '1').style.display = 'Block';
                                document.getElementById('drpChdAge' + rownum + '2').style.display = 'Block';
                                document.getElementById('drpExtraBed' + rownum).style.display = 'none';
                            }
                            break;
                        }
                    case "2":
                        {
                            document.getElementById('drpRoomType' + rownum).style.display = 'Block';
                            document.getElementById('drpRoomType' + rownum).disabled = false;
                            document.getElementById('drpRoomType' + rownum + '_1').style.display = 'none';

                            if (numChilds == 0) {
                                document.getElementById('drpChdAge' + rownum + '1').style.display = 'none';
                                document.getElementById('drpChdAge' + rownum + '2').style.display = 'none';
                                document.getElementById('drpExtraBed' + rownum).style.display = 'none';
                            }
                            else if (numChilds == 1) {
                                document.getElementById('drpChdAge' + rownum + '1').style.display = 'Block';
                                document.getElementById('drpChdAge' + rownum + '2').style.display = 'none';
                                document.getElementById('drpExtraBed' + rownum).style.display = 'Block';
                            }
                            else if (numChilds == 2) {
                                document.getElementById('drpChdAge' + rownum + '1').style.display = 'Block';
                                document.getElementById('drpChdAge' + rownum + '2').style.display = 'Block';
                                document.getElementById('drpExtraBed' + rownum).style.display = 'Block';
                            }
                            //                            if (numChilds != 0)
                            //                                document.getElementById('drpExtraBed' + rownum).style.display = 'Block';
                            break;
                        }
                    case "3":
                        {
                            document.getElementById('drpRoomType' + rownum).style.display = 'none';
                            document.getElementById('drpRoomType' + rownum + '_1').style.display = 'Block';
                            document.getElementById('drpRoomType' + rownum + '_1').options[1].selected = true;
                            document.getElementById('drpRoomType' + rownum + '_1').disabled = true;
                            document.getElementById('drpExtraBed' + rownum).style.display = 'none';

                            if (numChilds == 0) {
                                document.getElementById('drpChdAge' + rownum + '1').style.display = 'none';
                                document.getElementById('drpChdAge' + rownum + '2').style.display = 'none';
                                document.getElementById('drpExtraBed' + rownum).style.display = 'none';
                            }
                            else if (numChilds == 1) {
                                document.getElementById('drpChdAge' + rownum + '1').style.display = 'Block';
                                document.getElementById('drpChdAge' + rownum + '2').style.display = 'none';
                                document.getElementById('drpExtraBed' + rownum).style.display = 'none';
                            }
                            else if (numChilds == 2) {
                                document.getElementById('drpChdAge' + rownum + '1').style.display = 'Block';
                                document.getElementById('drpChdAge' + rownum + '2').style.display = 'Block';
                                document.getElementById('drpExtraBed' + rownum).style.display = 'none';
                            }
                            break;
                        }
                    case "4":
                        {
                            document.getElementById('drpRoomType' + rownum).style.display = 'none';
                            document.getElementById('drpRoomType' + rownum + '_1').style.display = 'Block';
                            document.getElementById('drpRoomType' + rownum + '_1').options[2].selected = true;
                            document.getElementById('drpRoomType' + rownum + '_1').disabled = true;
                            document.getElementById('drpExtraBed' + rownum).style.display = 'none';

                            if (numChilds == 0) {
                                document.getElementById('drpChdAge' + rownum + '1').style.display = 'none';
                                document.getElementById('drpChdAge' + rownum + '2').style.display = 'none';
                                document.getElementById('drpExtraBed' + rownum).style.display = 'none';
                            }
                            else if (numChilds == 1) {
                                document.getElementById('drpChdAge' + rownum + '1').style.display = 'Block';
                                document.getElementById('drpChdAge' + rownum + '2').style.display = 'none';
                                document.getElementById('drpExtraBed' + rownum).style.display = 'none';
                            }
                            else if (numChilds == 2) {
                                document.getElementById('drpChdAge' + rownum + '1').style.display = 'Block';
                                document.getElementById('drpChdAge' + rownum + '2').style.display = 'Block';
                                document.getElementById('drpExtraBed' + rownum).style.display = 'none';
                            }
                            break;
                        }
                }
            }
    }
}

function countPax() {
    var ddRooms = document.getElementById('cph_main_roomsNo');
    var numRooms = ddRooms.options[ddRooms.selectedIndex].value;

    var ddAdt1 = document.getElementById("drpAdults1");
    var nAdt1 = ddAdt1.options[ddAdt1.selectedIndex].value;

    var ddAdt2 = document.getElementById("drpAdults2");
    var nAdt2 = 0;

    var ddAdt3 = document.getElementById("drpAdults3");
    var nAdt3 = 0;
    var ddAdt4 = document.getElementById("drpAdults4");
    var nAdt4 = 0;



    var ddChd1 = document.getElementById("drpChilds1");
    var nChd1 = ddChd1.options[ddChd1.selectedIndex].value;
    var ddChd2 = document.getElementById("drpChilds2");
    var nChd2 = 0;
    var ddChd3 = document.getElementById("drpChilds3");
    var nChd3 = 0;
    var ddChd4 = document.getElementById("drpChilds4");
    var nChd4 = 0;


    if (numRooms == "2") {

        nAdt2 = ddAdt2.options[ddAdt2.selectedIndex].value;
        nChd2 = ddChd2.options[ddChd2.selectedIndex].value;
    }

    if (numRooms == "3") {

        nAdt2 = ddAdt2.options[ddAdt2.selectedIndex].value;
        nAdt3 = ddAdt3.options[ddAdt3.selectedIndex].value;
        nChd2 = ddChd2.options[ddChd2.selectedIndex].value;
        nChd3 = ddChd3.options[ddChd3.selectedIndex].value;
    }

    if (numRooms == "4") {

        nAdt2 = ddAdt2.options[ddAdt2.selectedIndex].value;
        nAdt3 = ddAdt3.options[ddAdt3.selectedIndex].value;
        nAdt4 = ddAdt4.options[ddAdt4.selectedIndex].value;

        nChd2 = ddChd2.options[ddChd2.selectedIndex].value;
        nChd3 = ddChd3.options[ddChd3.selectedIndex].value;
        nChd4 = ddChd4.options[ddChd4.selectedIndex].value;

    }







    totalPax = parseInt(nAdt1) + parseInt(nAdt2) + parseInt(nAdt3) + parseInt(nAdt4) + parseInt(nChd1) + parseInt(nChd2) + parseInt(nChd3) + parseInt(nChd4);
    // document.getElementById("spnTotalPax").innerHTML = totalPax;


}

function trim(str) {
    if (!str || typeof str != 'string')
        return null;

    return str.replace(/^[\s]+/, '').replace(/[\s]+$/, '').replace(/[\s]{2,}/, ' ');
}

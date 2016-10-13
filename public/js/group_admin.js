/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//var site_url = jQuery.cookie('BaseUrl');

//$("#group_mang").on('click','.groupdata',function(){
//     var id = $(this).attr("data");
//     //alert(id);
//       // deviceProfile(id); 
//      $("#device_update_form").bPopup();
//  
//      editGroup(id);
//});

function editGroup(id){
     var site_url = jQuery.cookie('BaseUrl');
    var url = site_url+'/tunnel/Admin/editGroupdata';
                 $.ajax({
                    dataType:'json',
                    url:url,
                    type:'POST',
                    data:{groupIds:id},
                    success:function(data){
                        
                        $(".groupVendor").val(data.data.id);
                        $("#group_name").val(data.data.vname);
                        $("#group_boss").val(data.data.vgroup);
                        
                      
                    },
                    error:function(error){
                       console.log(error);
                    }
                });
    
}

function groupdateAdmin(){
   var site_url = jQuery.cookie('BaseUrl'); 
    var url = site_url+'/tunnel/Admin/groupUpdate';
                 $.ajax({
                    dataType:'json',
                    url:url,
                    type:'POST',
                    data:$("#frm_update_group").serialize(),
                    success:function(data){
                        
                       if(data.data == true){
                           alert("Update successfully");
                           location.reload();
                       }
                       else{
                           alert("Not Updated");
                           location.reload();
                       }
                    },
                    error:function(error){
                       console.log(error);
                    }
                });
    
    
}

function deleteGroup(){
   var site_url = jQuery.cookie('BaseUrl');
    var id = $("#group_id_val").val();
    alert(id);
     var url = site_url+'/tunnel/Admin/groupDelete';
                 $.ajax({
                    dataType:'json',
                    url:url,
                    type:'POST',
                    data:{ids:id},
                    success:function(data){
                        
                       if(data.data == true){
                           alert("DELETE successfully");
                           location.reload();
                       }
                       else{
                           alert("Not Deleted");
                           location.reload();
                       }
                    },
                    error:function(error){
                       console.log(error);
                    }
                });
    
}

function vehicleEdit(vid){
   $("#vehicleprofileedit").show(); 
    var id = vid;
    //alert(id);
    var site_url = jQuery.cookie('BaseUrl');
     var url = site_url+'/tunnel/Admin/vehicleEdit';
                 $.ajax({
                    dataType:'json',
                    url:url,
                    type:'POST',
                    data:{ids:vid},
                    success:function(data){
                      
						$("#vids").val(data.data.ids);
						$("#unique_id").val(data.data.uids);
						$("#vidsecond").val(data.data.ids);
						$("#vtype").val(data.data.vehicle_type);
						$("#vmodel").val(data.data.model);
						$("#vEref").val(data.data.eref);
						$("#vyear").val(data.data.year);
						$("#vlplate").val(data.data.lplate);
						$("#vcolor").val(data.data.colorv);
                       if(data.data.actv == 1){
					   $("#vactive").prop('checked',true);
					   
					   }
                    },
                    error:function(error){
                       console.log(error);
                    }
                });
    
}

function update_vehicle(){

	var site_url = jQuery.cookie('BaseUrl');
     var url = site_url+'/tunnel/Admin/vehicleUpdate';
                 $.ajax({
                    dataType:'json',
                    url:url,
                    type:'POST',
                    data:$("#vehicle_form_details").serialize(),
                    success:function(data){
                      
					alert("Update Successfully");	
                     location.reload();
                    },
                    error:function(error){
                       console.log(error);
                    }
                });
	
}
function backbuttonvendor(){

$("#page-wrapper").show();
     $("#vendor_profile").hide();
      $("#vehicleprofileedit").hide();
      $("#driver_detail").hide();


}


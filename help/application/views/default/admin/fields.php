<div class="span12">
	<div class="well-white">
		<div class="page-header">
			<h1>Extra Fields</h1>
		</div>

		<div class="body">


Extra fields will be displayed when opening a ticket with this department.		
			
		
		<p></p>

      
<div class="row">

    <div class="span3">        

<p>
    <a href="#mdlAdd" role="button" class="btn btn-primary" data-toggle="modal">Add New</a>
 </p>
		    <!-- Modal  add-->
		    <div id="mdlAdd" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="myModalLabel">Add Field</h3>
		    </div>
		    <div class="modal-body">
		    <p>
		    	
<form id="frmAdd">		 
	   	<input type="hidden" name="deptid" value="<?php echo $deptid;?>">
				<fieldset>
                    <label>Name</label>
                    <input type="text" name="name"  placeholder="Example: username" />
                </fieldset>
               
                
                 <fieldset>
                    <label>Type</label>
                    <select class="select2" name="type">
                    	<option value="text">Text Field</option>
                    </select>
                </fieldset>
                
</form>
				<div class="alert alert-danger hidden"></div>

             
               </p>
		    </div>
		    <div class="modal-footer">

		<div class="spinner pull-left hidden"><i class="icon-spinner icon-spin"></i>    Processing...</div>	    	
		    	
		    <button id="btnadd" class="btn btn-primary ">Add</button>
		    <button class="btn btn-link" data-dismiss="modal" aria-hidden="true">Close</button>
		    
		    </div>
		    </div>
</div>
</div>

  
<script>
	$(function() {
/**
 *Add 
 */		
		$("#btnadd").click(function(){
			var post = $("#frmAdd").serialize();
			$("#mdlAdd .spinner").removeClass('hidden') ;
			$.post('<?php echo site_url();?>/admin/departments/addfield', post, function(data){
					var result = $.parseJSON(data) ;
					if(result.status == 0 )
					{
						$("#mdlAdd .alert-danger").html(result.statusmsg).removeClass('hidden');	
					}
					else 
					 location.reload(); 
				$("#mdlAdd .spinner").addClass('hidden') ;
			})
		})


	})
</script>



<!-- Edit Models -->

<?php
if(isset($fields))
foreach($fields as $field): ?>

   <div id="mdlEdit-<?php echo $field['id'];?>" class="modal hide fade mdlEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="myModalLabel">Edit Field</h3>
		    </div>
		    <div class="modal-body">
		    <p>
		    	
<form id="frmEdit">		   
<input type="hidden" name="id" value="<?php echo $field['id'];?>">	 	

				<fieldset>
                    <label>Name</label>
                    <input type="text" name="name"  placeholder="Example: username" value="<?php echo $field['name'];?>" />
                </fieldset>
               
                 <fieldset>
                    <label>Type</label>
                    <select class="select2" name="type">
                    	<option <?php if($field['type'] == 'text') echo "SELECTED"; ?>  value="text">Text Field</option>
                    </select>
                </fieldset>
              
</form>
				<div class="alert alert-danger hidden"></div>
               </p>
		    </div>
		    <div class="modal-footer">
		<div class="spinner pull-left hidden"><i class="icon-spinner icon-spin"></i>    Processing...</div>	    	
		    <button id="btnsave" class="btn btn-primary ">Save changes</button>
		    <button class="btn btn-link" data-dismiss="modal" aria-hidden="true">Close</button>
		    </div>
		    </div>

<?php
endforeach;
?>


<script>
	$(function(){
		
		$(".mdlEdit #btnsave").click(function(){
	        var parent =  $(this).parent().parent().attr("id");
			var post = $("#" + parent).find("#frmEdit").serialize();
			
			$("#" + parent).find(".spinner").removeClass('hidden') ;
			$.post('<?php echo site_url();?>/admin/departments/updatefield', post, function(data){
					var result = $.parseJSON(data) ;
					if(result.status == 0 )
					{
						$("#" + parent).find(".alert-danger").html(result.statusmsg).removeClass('hidden');	
					}
					else 
					 location.reload(); 
				$("#" + parent).find(".spinner").addClass('hidden') ;
			})
			
			
			
		})
		
	})
</script>




  <table class="sort">

                <thead>
                    <tr class="info">

                        <td>Name</td>
                        <td>Action</td>

                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th colspan="7" class="pager form-horizontal">
                        <button class="btn first">
                            <i class="icon-step-backward"></i>
                        </button>
                        <button class="btn prev">
                            <i class="icon-arrow-left"></i>
                        </button><span class="pagedisplay"></span><!-- this can be any element, including an input -->
                        <button class="btn next">
                            <i class="icon-arrow-right"></i>
                        </button>
                        <button class="btn last">
                            <i class="icon-step-forward"></i>
                        </button>
                        <select class="pagesize input-mini" title="Select page size">
                            <option selected="selected" value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="40">40</option>
                        </select><select class="pagenum input-mini" title="Select page number"></select></th>

                    </tr>
                </tfoot>

                <tbody>

<?php

if(isset($fields))
foreach($fields as $field): ?>

                    <tr>

                        <td><?php echo $field['name'];?></td>
                        
                        <td>
                        	<a href="#mdlEdit-<?php echo $field['id'];?>" role="button" class="btn" data-toggle="modal">Edit</a>
                        	
                        	
                        	<a class="btn btn-danger jsconfirm" href="<?php echo base_url();?>index.php/admin/departments/deletefield/<?php echo $field['id'];?>">Delete</a></td>

                    </tr>
<?php endforeach; ?>
                </tbody>

            </table>
            
          
			
			
			
		</div>

	</div>

</div>

<div class="span12">
    <div class="well-white">
        <div class="page-header">
            <h1>Departments</h1>
        </div>

        <div class="body">


            <?php
            if (validation_errors() == TRUE)
            {
                echo validation_errors('<div class="alert alert-danger">', '</div>');
            }

            if (isset($success))
            {
                echo '<div class="alert alert-success">' . $success . '</div>';
            }
			if (isset($error))
            {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
        ?>



      
<div class="row">

    <div class="span3">        

<p>
    <a href="#mdlAdd" role="button" class="btn btn-primary" data-toggle="modal">Add New</a>
 </p>
		    <!-- Modal  add-->
		    <div id="mdlAdd" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="myModalLabel">Add Department</h3>
		    </div>
		    <div class="modal-body">
		    <p>
		    	
<form id="frmAdd">		    	
				<fieldset>
                    <label>Department Name</label>
                    <input type="text" name="deptname" required placeholder="Name" value="<?php if(isset($dept['deptname'])) echo $dept['deptname']; else echo set_value('deptname'); ?>"  />
                
                </fieldset>
                
</form>
				<div class="alert alert-danger hidden"></div>

             
               </p>
		    </div>
		    <div class="modal-footer">

		<div class="spinner pull-left hidden"><i class="icon-spinner icon-spin"></i>    Processing...</div>	    	
		    	
		    <button id="btnadd" class="btn btn-primary ">Save changes</button>
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
			$.post('<?php echo site_url();?>/admin/departments/add', post, function(data){
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
if(isset($departments))
foreach($departments as $department): ?>

   <div id="mdlEdit-<?php echo $department['deptid'];?>" class="modal hide fade mdlEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="myModalLabel">Edit Department</h3>
		    </div>
		    <div class="modal-body">
		    <p>
		    	
<form id="frmEdit">		   
<input type="hidden" name="deptid" id="deptid" value="<?php echo $department['deptid'];?>">
	 	
				<fieldset>
                    <label>Staff Name</label>
                    <input type="text" name="deptname" required placeholder="First Name" value="<?php if(isset($department['deptname'])) echo $department['deptname'];?>"  />
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
			$.post('<?php echo site_url();?>/admin/departments/update', post, function(data){
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

if(isset($departments))
foreach($departments as $department): ?>

                    <tr>

                        <td><?php echo $department['deptname'];?></td>
                        <td>
                        	<a href="#mdlEdit-<?php echo $department['deptid'];?>" role="button" class="btn" data-toggle="modal">Edit</a>
                        	
                        	<a class="btn btn-warning" href="<?php echo base_url();?>index.php/admin/departments/fields/<?php echo $department['deptid'];?>">Extra Fields </a>
                        	<a class="btn btn-danger jsconfirm" href="<?php echo base_url();?>index.php/admin/departments/delete/<?php echo $department['deptid'];?>">Delete</a></td>

                    </tr>
<?php endforeach; ?>
                </tbody>

            </table>

        </div>

    </div>

</div>

<script>
	$('#tab a').click(function(e) {
		e.preventDefault();
		$(this).tab('show');
	})
</script>
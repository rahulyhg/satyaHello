<div class="">
    <div class="well-white">
        <div class="page-header">
            <h1>Staffs</h1>
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
		    <h3 id="myModalLabel">Add Staff</h3>
		    </div>
		    <div class="modal-body">
		    <p>
		    	
<form id="frmAdd">		    	
				<fieldset>
                 <?php if($this -> session -> userdata('admin')==1){ ?>
                 <select class="select2" id="dept" name="department">
					
		<?php
			foreach($department as $dept): ?>
			<option value="<?php echo $dept['deptid'];?>"><?php echo $dept['deptname'];?> </option>  
		<?php endforeach; ?>
    	</select>
        <?php }elseif($this -> session -> userdata('admin')==2){
		echo '<input type="hidden" name="department" value="'.$this -> session -> userdata('department').'">';	
			
		}?>
        <input type="hidden" name="admin" value="0" />
                    <label>Staff Name</label>
                    <input type="text" name="firstname"  placeholder="First Name" value="<?php if(isset($dept['firstname'])) echo $dept['firstname']; else echo set_value('firstname'); ?>"  />
                    <input type="text" name="lastname"  placeholder="Last Name" value="<?php if(isset($dept['lastname'])) echo $dept['lastname']; else echo set_value('lastname'); ?>"  />
                </fieldset>
                <fieldset>
                    <label>Staff Email</label>
                    <input type="text" name="email" required placeholder="Enter a valid email address" value="<?php if(isset($dept['email'])) echo $dept['email']; else echo set_value('email'); ?>"  />
                </fieldset>
                 <fieldset>
                    <label>Password</label>
                    <input type="password" name="password"  />
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
            
            <div id="mdlAdd2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="myModalLabel">Add Admin</h3>
		    </div>
		    <div class="modal-body">
		    <p>
		    	
<form id="frmAdd2">		    	
				<fieldset>
                
                <select class="select2" id="dept" name="department">
					
		<?php
			foreach($department as $dept): ?>
			<option value="<?php echo $dept['deptid'];?>"><?php echo $dept['deptname'];?> </option>  
		<?php endforeach; ?>
    	</select>
            <input type="hidden" name="admin" value="2" />          <label>Staff Name</label>
                    <input type="text" name="firstname"  placeholder="First Name" value="<?php if(isset($dept['firstname'])) echo $dept['firstname']; else echo set_value('firstname'); ?>"  />
                    <input type="text" name="lastname"  placeholder="Last Name" value="<?php if(isset($dept['lastname'])) echo $dept['lastname']; else echo set_value('lastname'); ?>"  />
                </fieldset>
                <fieldset>
                    <label>Staff Email</label>
                    <input type="text" name="email" required placeholder="Enter a valid email address" value="<?php if(isset($dept['email'])) echo $dept['email']; else echo set_value('email'); ?>"  />
                </fieldset>
                 <fieldset>
                    <label>Password</label>
                    <input type="password" name="password"  />
                </fieldset>
</form>

				<div class="alert alert-danger hidden"></div>

             
               </p>
		    </div>
		    <div class="modal-footer">

		<div class="spinner pull-left hidden"><i class="icon-spinner icon-spin"></i>    Processing...</div>	    	
		    	
		    <button id="btnadd2" class="btn btn-primary ">Save changes</button>
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
			$.post('<?php echo site_url();?>/admin/staffs/add', post, function(data){
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
			$("#btnadd2").click(function(){
			var post = $("#frmAdd2").serialize();
			$("#mdlAdd2 .spinner").removeClass('hidden') ;
			$.post('<?php echo site_url();?>/admin/staffs/add', post, function(data){
					var result = $.parseJSON(data) ;
					if(result.status == 0 )
					{
						$("#mdlAdd2 .alert-danger").html(result.statusmsg).removeClass('hidden');	
					}
					else 
					 location.reload(); 
				$("#mdlAdd2 .spinner").addClass('hidden') ;
			})
		})


	})
</script>


<!-- Edit Models -->

<?php
if(isset($staffs))
foreach($staffs as $staff): ?>

   <div id="mdlEdit-<?php echo $staff['id'];?>" class="modal hide fade mdlEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="myModalLabel">Edit Staff</h3>
		    </div>
		    <div class="modal-body">
		    <p>
		    	
<form id="frmEdit">		   
<input type="hidden" name="id" id="id" value="<?php echo $staff['id'];?>">
	 	
				<fieldset>
                    <label>Staff Name</label>
                    <input type="text" name="firstname" required placeholder="First Name" value="<?php if(isset($staff['firstname'])) echo $staff['firstname'];?>"  />
                    <input type="text" name="lastname" required placeholder="Last Name" value="<?php if(isset($staff['lastname'])) echo $staff['lastname'];?>"  />
                </fieldset>
                <fieldset>
                    <label>Staff Email</label>
                    <input type="text" name="email" required placeholder="Enter a valid email address" value="<?php if(isset($staff['email'])) echo $staff['email']; ?>"  />
                </fieldset>
                 <fieldset>
                    <label>Password</label>
                    <input type="password" name="password"  />
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
			$.post('<?php echo site_url();?>/admin/staffs/update', post, function(data){
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
                        <td>Department</td>
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

if(isset($staffs))
foreach($staffs as $staff): ?>

                    <tr>

                        <td><?php echo $staff['firstname'];?> <?php echo $staff['lastname'];?></td>
                       
<td><?php
			foreach($department as $dept):
			if($staff['department']==$dept['deptid'])
			{
				echo $dept['deptname']
			 ?>
            
 <?php
			}
  endforeach; ?>           
            </td>
             <td>
                        	<a href="#mdlEdit-<?php echo $staff['id'];?>" role="button" class="btn" data-toggle="modal">Edit</a>
                        	<a class="btn btn-danger jsconfirm" href="<?php echo base_url();?>index.php/admin/staffs/delete/<?php echo $staff['id'];?>">Delete</a>
							
							
							
							</td>
                    </tr>
<?php endforeach; ?>
                </tbody>

            </table>




                </tbody>

            </table>
            <?php
            if(isset($staffs_admin))
			{
			?>
            <p>
    <a href="#mdlAdd2" role="button" class="btn btn-primary" data-toggle="modal">Add Admin</a>
 </p>
      <table class="sort">

                <thead>
                    <tr class="info">

                        <td>Name</td>
                        <td>Department</td>
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


foreach($staffs_admin as $staff): ?>
<div id="mdlEdit-<?php echo $staff['id'];?>" class="modal hide fade mdlEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="myModalLabel">Edit Staff</h3>
		    </div>
		    <div class="modal-body">
		    <p>
		    	
<form id="frmEdit">		   
<input type="hidden" name="id" id="id" value="<?php echo $staff['id'];?>">
	 	
				<fieldset>
                    <label>Staff Name</label>
                    <input type="text" name="firstname" required placeholder="First Name" value="<?php if(isset($staff['firstname'])) echo $staff['firstname'];?>"  />
                    <input type="text" name="lastname" required placeholder="Last Name" value="<?php if(isset($staff['lastname'])) echo $staff['lastname'];?>"  />
                </fieldset>
                <fieldset>
                    <label>Staff Email</label>
                    <input type="text" name="email" required placeholder="Enter a valid email address" value="<?php if(isset($staff['email'])) echo $staff['email']; ?>"  />
                </fieldset>
                 <fieldset>
                    <label>Password</label>
                    <input type="password" name="password"  />
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

                    <tr>

                        <td><?php echo $staff['firstname'];?> <?php echo $staff['lastname'];?></td>
                       
<td><?php
			foreach($department as $dept):
			if($staff['department']==$dept['deptid'])
			{
				echo $dept['deptname']
			 ?>
            
 <?php
			}
  endforeach; ?>           
            </td>
             <td>
                        	<a href="#mdlEdit-<?php echo $staff['id'];?>" role="button" class="btn" data-toggle="modal">Edit</a>
                        	                            <a class="btn btn-danger jsconfirm" href="<?php echo base_url();?>index.php/admin/staffs/delete/<?php echo $staff['id'];?>"'>Delete</a></td>
                    </tr>
<?php endforeach; }?>
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
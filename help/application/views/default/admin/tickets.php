<div class="">
	<div class="well-white">
		<div class="page-header" style="border:none">
			
		</div>

		<div class="body">
		<?php
				if (isset($success))
			echo '<div class="alert alert-success">' . $success . '</div>';			
			?>
			<div class="row">
			
			<div class="span4">
				
				<div class="badge badge-info">Ticket Status</div><p></p>
				
				<table class="table table-bordered table-condensed table-hover table-striped" style="font-size:12px">
				
				
				<?php foreach($ticketcount as $status => $count ): ?> 
				
				<tr>
					<td><?php echo ucwords($status); ?> Tickets</td>
					<td><?php echo $count; ?></td>
					<td><a href="<?php echo site_url();?>/admin/tickets/index/<?php echo $status;?>" class="btn">View</a></td>
					
				</tr>
				<?php endforeach; ?>
				
			</table>

			</div>
			
			
				
			</div>
			
			
		</div>

	</div>

</div>

<div class="">
	<div class="well-white">
		<div class="page-header" style="border:none">
			<h1><span style="float:right"><a class="btn btn-inverse" href="<?php echo site_url("admin/tickets/get_csv");?>" style="margin-right:20px;margin-bottom:15px;">Export Data</a></span></h1>
		</div>

		<div class="body" style=";
margin-right: 20px;">
			
			
		
		
	<table class="sort" style="font-size:12px" >
    <thead>
    <tr>
    <th>Ticket#</th>
   <th class="filter-select filter-exact" data-placeholder="">Status</th>	<th>Subject</th>
    <th class="" data-placeholder="">Customer</th>
	
	<th class="" data-placeholder="">Mobile_No</th>
	<th>Travel_Date</th>
	<th>Duration</th>
	<th class="filter-select filter-exact" data-placeholder="">Department</th>
	<th class="filter-select filter-exact">Created_By</th>
	<th class="filter-select filter-exact">Assignee</th>
	
    <th>Created_Date</th>
    <th>Updated_Date</th>
     <th>Due_Date</th>
	 <th class="" data-placeholder="">Email</th>
	 <th class="filter-select filter-exact">Priority</th>
	
	<th>Action</th>
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


//echo date('Y-m-d h(idea)(worry)');

?>
    
<?php
if(isset($tickets))
foreach($tickets as $ticket) : 
date_default_timezone_set("Asia/Calcutta");
$date = new DateTime($ticket['due_date']);
$now = new DateTime();
$comp_d=$now->diff($date)->format("%R%d.%h");
//echo $comp_d."<br>";
if($comp_d<=1){
//echo $comp_d."<br>";
	
echo '<tr class="changetdcolor2">';

}else{
	
echo '<tr>';	
}
?>

    
    <td><a href="<?php echo site_url("admin/tickets/view"); echo '/' . $ticket['id'];?>"><?php echo $ticket['id'];?></a></td>
  <td><?php echo ucfirst($ticket['status']);?></td>
    <td>
    	
   	<a href="<?php echo site_url("admin/tickets/view"); echo '/' . $ticket['id'];?>"><?php echo $ticket['subject'];?></a>
    	
    </td>
	
	 <td><?php echo $ticket['name'];?></td>
   
	 <td><?php echo $ticket['mobileno'];?></td>
	<td><?php echo $ticket['travel_date'];?></td>
	<td><?php echo $ticket['duration'];?></td>
	
    <td><?php echo $ticket['deptname'];?></td>
	<td><?php echo $ticket['firstname'].' '.$ticket['lastname'] ;?></td>
    <td><?php echo $ticket['updated_by'];if($this->session->userdata('admin')=='2' or $this->session->userdata('admin')=='1'){?>
		
		<a href="#mdlEdit-<?php echo $ticket['id'];?>" role="button" class="btn" data-toggle="modal">Edit</a>
		</td>
		 <div id="mdlEdit-<?php echo $ticket['id'];?>" class="modal hide fade mdlEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="myModalLabel">Edit Staff</h3>
		    </div>
		    <div class="modal-body">
		    <p>
		    	
<form id="frmEdit" action="<?php echo site_url();?>/tickets/assignee" method="post">		   
<input type="hidden" name="id" id="id" value="<?php echo $ticket['id'];?>">
	 	
				<fieldset>
                    <label>Assignee</label>
                     <select class="select2" id="dept" name="staff">
					
		<?php
			foreach($staffs as $staff): 
			if($staff['admin']==0)
			{
			?>
		
		
			<option value="<?php echo $staff['id'];?>"><?php echo $staff['firstname'].' '.$staff['lastname'];?> </option>  
		<?php } endforeach; ?>
    	</select>
                </fieldset>
                <div class="alert alert-danger hidden"></div>
               </p>
		    </div>
		    <div class="modal-footer">
		<div class="spinner pull-left hidden"><i class="icon-spinner icon-spin"></i>    Processing...</div>	    	
		    <button id="btnsave" type="submit" class="btn btn-primary ">Save changes</button>
		    <button class="btn btn-link" data-dismiss="modal" aria-hidden="true">Close</button>
		    </div>
		    </div>	
</form>
			
	<?php }?>
   
    <td><?php echo $ticket['created'];?></td>
    <td><?php echo $ticket['modified'];?></td>
    <td><?php echo $ticket['due_date'];?></td>
	 <td><?php echo $ticket['email'];?></td>
	 <td><?php echo $ticket['priority'];?></td>
  
    <td>
    	<a class="btn btn-inverse" href="#mdlclose-<?php echo $ticket['id'];?>">Close</a>
    	
    	</td>
		<div id="mdlclose-<?php echo $ticket['id'];?>" class="modal hide fade mdlEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="myModalLabel">Edit Staff</h3>
		    </div>
		    <div class="modal-body">
		    <p>
		    	
<form id="frmEdit" action="<?php echo site_url();?>/tickets/assignee" method="post">		   
<input type="hidden" name="id" id="id" value="<?php echo $ticket['id'];?>">
	 	
				<fieldset>
                    <label>Assignee</label>
                     <select class="select2" id="dept" name="staff">
					
		<?php
			foreach($staffs as $staff): 
			if($staff['admin']==0)
			{
			?>
		
		
			<option value="<?php echo $staff['id'];?>"><?php echo $staff['firstname'].' '.$staff['lastname'];?> </option>  
		<?php } endforeach; ?>
    	</select>
                </fieldset>
                <div class="alert alert-danger hidden"></div>
               </p>
		    </div>
		    <div class="modal-footer">
		<div class="spinner pull-left hidden"><i class="icon-spinner icon-spin"></i>    Processing...</div>	    	
		    <button id="btnsave" type="submit" class="btn btn-primary ">Save changes</button>
		    <button class="btn btn-link" data-dismiss="modal" aria-hidden="true">Close</button>
		    </div>
		    </div>	
</form>
    </tr>
<?php endforeach ; ?>
    
    </tbody>
    </table>	
			
			
			
			
			
			
			
		</div>

	</div>

</div>

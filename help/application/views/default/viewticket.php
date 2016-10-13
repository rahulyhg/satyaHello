<div class="">
	<div class="well-white">
		<div class="page-header">
			<h1>Viewing Ticket #<?php echo $ticketid;?></h1>
		</div>

		<div class="body">
			
			
<?php
if (isset($error))
	echo '<div class="alert alert-danger">' . $error . '</div>';
if (isset($success))
	echo '<div class="alert alert-success">' . $success . '</div>';

?>
		
<div class="row">
	<div class="span3">
							
		<div class="badge badge-important">Ticket Info</div>	<p></p>
			
			<table class="table table-condensed table-bordered table-hover">
				<tr>
					<td>Ticket ID</td>
					<td>#<?php echo $ticketid; ?></td>
				</tr>
				<tr>
					<td>Status</td>
					<td><?php echo ucfirst($ticket['status']);?></td>
				</tr>
				<tr>
					<td>Department</td>
					<td><?php echo $ticket['deptname'];?></td>
				</tr>
			</table>
	
	</div>
	


<!-- Additional Fields -->

<?php if(is_array($additional) && !empty($additional)): ?>
	<div class="span4">		

	<div class="badge badge-important">Update Additional Fields</div>
	<a rel="tooltip" class="btn btn-link" title="Additional Fields" data-content="Additional field values submitted previously are hidden. Enter new values below if you want to update. These values are encrypted before saving to the database and deleted once the ticket is closed">?</a>
	<p></p>
	
	
	<form id="frmadditional">
	<?php
	foreach ($additional as $item)
    {
        echo '<fieldset>' ;
        echo '<label>' . $item['name'] . '</label>';
        if($item['type'] == 'text') 
        {
            echo '<input type="text" name="' . $item['uniqid'] .  '">  ';
        }     
        echo '</fieldset>' . "\n" ;
    }
	
	?>
	
	<input type="hidden" name="ticketid" value="<?php echo $ticketid; ?>">
	
	</form>
	<div id="fieldsuccess" class="alert alert-success hidden">Updated</div>
	<div class="hidden" id="spinner"><i class="icon-spinner icon-spin "></i><p></p></div>
	<a class="btn" id="updatefield" href="#">Update</a><p></p>
	<p></p>
	
	
<script>
	$(function(){
		$("#updatefield").click(function(){
			
			var ticketid = $("#ticketid").val();
			
			$("#spinner").removeClass("hidden");
			$("#fieldsuccess").addClass("hidden");
			
			var post = $("#frmadditional").serialize();
			
			$.post('<?php echo site_url();?>/tickets/updatefield', post, function(data){
					var result = $.parseJSON(data) ;
					if(result.status == 1 )
					{
						$("#fieldsuccess").html(result.statusmsg).removeClass('hidden');	
					}
					$("#spinner").addClass("hidden");
			})
			
			return false ;
		})
	})
</script>	
	
	
	</div>
<?php endif; ?>





</div>




<!-- Close - Additional Fields -->










<!-- original ticket response -->	

<div class="well">
	<div class="label label-inverse">You - <?php echo $ticket['email'];?></div>
	<div class="label"><?php echo $ticket['created'];?></div>
	 <br><br>
	<?php echo nl2br($ticket['body']) ;?>
	
</div>	
	
	
	
	

<!-- Replies -->

<?php
foreach($replies as $reply) : ?>

<?php
if($reply['replier'] == 'client')
{
	echo '<div class="well">' ;
}
else
{
		echo '<div class="well-white adminreply">' ;
}
?>

<?php
if($reply['replier'] == 'client') : ?>
<div class="label label-inverse">You - <?php echo $reply['email'];?></div>

<?php else:  ?>
	<div class="label label-success"><?php echo $reply['firstname'] .' '.  $reply['lastname']; ?></div>

<?php endif ; ?>


<div class="label"><?php echo $reply['time'];?></div>
 <br><br>
	<?php echo nl2br($reply['body']) ;?>
	
</div>
<?php
endforeach ?>
	
	


<div class="badge badge-important">Post a reply</div>
<p></p>

	<?php echo form_open('tickets/reply'); ?>
	<input type="hidden" name="ticketid" value="<?php echo $ticketid; ?>" >
	<input type="hidden" name="clienthash" value="<?php echo $clienthash; ?>" >
	<fieldset>
		<textarea rows="10" class="span8" name="reply"></textarea>
	</fieldset>
	<input type="submit" class="btn btn-primary" value="Submit" />
	
	</form>
	
		
				
			
			
		</div>

	</div>

</div>

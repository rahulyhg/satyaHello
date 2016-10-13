<div class="">
	<div class="well-white">
		<div class="page-header">
			<h1>Welcome</h1>
		</div>

		<div class="body">
		<?php
				if (isset($success))
			echo '<div class="alert alert-success">' . $success . '</div>';			
			?>
			<div class="row">
			
			<div class="span4">
				
				<div class="badge badge-info">Ticket Status</div><p></p>
				
				<table class="table table-bordered table-condensed table-hover table-striped">
				
				
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

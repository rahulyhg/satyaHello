<div class="span12">
	<div class="well-white">
		<div class="page-header">
			<h1>Welcome to Support Desk</h1>
		</div>

		<div class="body">
		
		<?php
		if (isset($error))
			echo '<div class="alert alert-danger">' . $error . '</div>';
		if (isset($success))
			echo '<div class="alert alert-success">' . $success . '</div>';
	?>

<?php if(isset($message)): ?>
<div class="alert alert-info">
	<?php echo $message;?>
</div>
<?php endif; ?>

		<p>Welcome to our Support Desk. Our Support Desk will help you to contact us for all your questions and get instant answers to it.</p> 	
		<p>In order to open a support ticket, please select a department below.</p>
		
		<div class="badge badge-info">Select Department</div><p></p>
		<select class="select2" id="dept">
					
		<?php
			foreach($departments as $dept): ?>
			<option value="<?php echo $dept['deptid'];?>"><?php echo $dept['deptname'];?> </option>  
		<?php endforeach; ?>
    	</select>
    	<a href="#" id="select" class="btn btn-primary">Select</a>
		</div>

	</div>

</div>

<script>
	$(function(){
		$("#select").click(function(){
			deptid = $("#dept").val();
			if(deptid)
			{
				window.location = '<?php echo site_url();?>/index/openticket/' + deptid ;
			}
			else
			{
				$.bootalert('Error', 'Please select a department');
			}
		})
	})
</script>



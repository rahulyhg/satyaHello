<style>
fieldset{
margin:0 10px 0 0;
display:inline;

}
form{

margin:0px;

}

</style>
<div class="">
    <div class="well-white">
        <div class="page-header">
            <h1>New Ticket</h1>
        </div>

        <div class="body">

<?php
if (validation_errors() == TRUE)
{
	echo validation_errors('<div class="alert alert-danger">', '</div>');
}
if (isset($error))
{
	echo '<div class="alert alert-danger">' . $error . '</div>';
}
?>

<div class="row">
	<div class="span9">
		
<?php echo form_open_multipart('index/create'); ?>
<fieldset>
    <label>Name</label>
    <input type="text" name="name" placeholder="User Name" required="required" value="<?php echo set_value('name'); ?>"  />
</fieldset>
<fieldset>
    <label>Mobile No</label>
    <input type="text" name="mobileno" placeholder="Mobile No" required="required" value="<?php echo set_value('mobileno'); ?>"  />
</fieldset>
<fieldset>
<label>Email Address</label>
<input type="text" name="email" placeholder="Email" required value="<?php echo set_value('email'); ?>"  />
</fieldset>



<fieldset>
    <label>Travel Date</label>
    <input type="text" name="travel_date" id="date" placeholder="Travel Date" required="required" value="<?php echo set_value('travel_date'); ?>"  />
</fieldset>
<fieldset>
    <label>Duration Of Travel</label>
    <select name="travel_dur" placeholder="Travel Duration" required="required" />
	<?php
	for($i=1;$i<=30;$i++)
	{
	echo "<option>$i</option>";
	}
	
	?>
	</select>
</fieldset><fieldset>
	<select name="travel_dur2" placeholder="Travel Duration" required="required" value="<?php echo set_value('subject'); ?>"  />
 <option>Day</option>
 <option>Month</option>
 <option>Year</option>
 </select>
	</fieldset>
          
<fieldset>
    <label>Subject</label>
    <input type="text" name="subject" placeholder="Subject of your request" required="required" value="<?php echo set_value('subject'); ?>"  />
</fieldset>

<fieldset>
    <label>Your Query</label>
    <textarea class="span7" rows="6" name="body"><?php echo set_value('body'); ?></textarea>
</fieldset>
<fieldset>
<div class="badge badge-info">Select Department</div><p></p>
		<select class="select2" id="dept" name="department">
					
		<?php
			foreach($departments as $dept): ?>
			<option value="<?php echo $dept['deptid'];?>"><?php echo $dept['deptname'];?> </option>  
		<?php endforeach; ?>
    	</select>
</fieldset>
		<fieldset>
    <label>Priority</label>
    <select class="select2 " name="priority">
        <?php foreach($priorities as $priority): ?>
            <option value="<?php echo $priority['priority']; ?>"><?php echo $priority['priority']; ?></option>
        <?php endforeach; ?>
    </select>
</fieldset>
<fieldset>
    <label>Due Date</label>
    <input type="text" name="due_date" id="date2" style="margin-bottom:0;" placeholder="Due Date" required="required" value="<?php echo set_value('due_date'); ?>"  />
</fieldset>
<fieldset>
<fieldset style="margin:0;">
<div id="attachments" >    
	<p></p>
  <span class="btn">Attachment <input type="file" name="userfile" size="30" /></span>
<a href="#" title="Allowed File Types" data-content="The following file types are allowed to upload, .gif .jpg, .jpeg, .png and .pdf. Maximum file size : 5MB " rel="tooltip">File Types?</a>    
</div>
</fieldset>


	</div>
	
	
	<div class="span6 ">
		
		
<?php
if (is_array($additional) && !empty($additional))
{
	echo '
	<div class="well">
	<p></p><div class="badge badge-important">Additional Fields</div>';
	echo '<a class="btn btn-link" href="#" rel="tooltip" title="Additional Fields" data-content="Additional fields are useful if you want to send us additional data such as username or password. These values are encrypted before saving to the database and deleted once the ticket is closed"> ?</a>  ';

	foreach ($additional as $item)
	{
		echo '<fieldset>';
		echo '<label>' . $item['name'] . '</label>';
		if ($item['type'] == 'text')
		{
			echo '<input type="text" name="' . $item['uniqid'] . '">  ';
		}
		echo '</fieldset>' . "\n";
	}
	
	echo '</div>';
}
?>


</div>
	
	
</div>






<?php
	$array = array(
		'class' => 'btn btn-primary',
		'name' => 'submit'
	);
	echo form_submit($array, 'Create Ticket');
?>
   <?php echo form_close(); ?>

    </div>

    </div>

</div>

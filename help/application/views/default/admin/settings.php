<div class="">
    <div class="well-white">
        <div class="page-header">
            <h1>Settings</h1>
        </div>

        <div class="body">
        
<?php

if(validation_errors() == TRUE )
{
    echo validation_errors('<div class="alert alert-danger">', '</div>');
}

if(isset($success))
{
    echo '<div class="alert alert-success">' . $success . '</div>';
}
 
?> 

<ul class="nav nav-tabs" id="tab">
<li class="active"><a href="#general">General</a></li>
<li><a href="#mail">Mail</a></li>
</ul>
 
 
<?php 
echo form_open("admin/settings/save");
?> 
 
<div class="tab-content">
<div class="tab-pane active" id="general">
    
<fieldset>
<label>Company Name</label>
<input type="text" name="companyname" required="required" value="<?php echo $settings['companyname'];?>" />
</fieldset>

<fieldset>
<label>Notification Message</label>
<input type="text" name="message" value="<?php if(isset($settings['message'])) echo $settings['message'];?>" />
 <span class="help-inline">Notification message will be displayed on the support desk <a href="<?php echo base_url();?>">home page</a> to the clients</span>
</fieldset>

</div>


<div class="tab-pane" id="mail">

<fieldset>
<label>Mail Sending Protocol</label>
<select class="select2" name="mailprotocol">
	<option <?php if($settings['mailprotocol'] == 'smtp') echo 'SELECTED';?>   value="smtp">SMTP</option>
	<option <?php if($settings['mailprotocol'] == 'mail') echo 'SELECTED';?> value="mail">PHP Mail()</option>
</select>
</fieldset>
	
<fieldset>
	<label>System Mails From</label>
	<input type="text" name="email" placeholder="Enter a valid email address" value="<?php echo $settings['email'];?>" />
</fieldset>	

<div class="label"> Below fields are required only if you select SMTP above </div>
<fieldset>
	<label>SMTP Host</label>
	<input type="text" name="smtp_host" placeholder="Mail Server Address" value="<?php echo $settings['smtp_host'];?>" />
</fieldset>	

<fieldset>
	<label>SMTP Username</label>
	<input type="text" name="smtp_user" placeholder="SMTP Username or Email Address" value="<?php echo $settings['smtp_user'];?>" />
</fieldset>	

<fieldset>
	<label>SMTP Password</label>
	<input type="text" name="smtp_pass" placeholder="SMTP Password" value="<?php echo $settings['smtp_pass'];?>" />
</fieldset>	

<fieldset>
	<label>SMTP Port </label>
	<input type="text" name="smtp_port" placeholder="SMTP Port Number" value="<?php echo $settings['smtp_port'];?>" />
</fieldset>	


	
	
	
	
</div>



</div>

<input type="submit" class="btn btn-primary btn-large" value="Save Settings">

</form>



        </div>

    </div>

</div>

<script>
	$('#tab a').click(function(e) {
		e.preventDefault();
		$(this).tab('show');
	})
</script>
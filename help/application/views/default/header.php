<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo $title;?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Le styles -->
        <link href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>css/bootstrap-responsive.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>css/jquery-ui.min.css" rel="stylesheet">
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <!--[if IE 7]>
        <link rel="stylesheet" href="css/font-awesome-ie7.min.css">
        <![endif]-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/font-awesome.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/select2.css">
        <link href="<?php echo base_url(); ?>css/custom.css" rel="stylesheet">
        <script src="<?php echo base_url(); ?>js/jquery-1.8.3.min.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap.js"></script>
        <script src="<?php echo base_url(); ?>js/select2.js"></script>
        
        <script src="<?php echo base_url(); ?>js/jquery-bootalert.js"></script>
        <script src="<?php echo base_url(); ?>js/custom.js"></script>
        
        <!-- Tablesorter: required for bootstrap -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/theme.bootstrap.css">
        <script src="<?php echo base_url(); ?>js/jquery.tablesorter.js"></script>
        <script src="<?php echo base_url(); ?>js/jquery.tablesorter.widgets.js"></script>
        <script src="<?php echo base_url(); ?>/js/jquery.metadata.js"></script>


    <!-- Tablesorter: optional -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.tablesorter.pager.css">
        <script src="<?php echo base_url(); ?>js/jquery.tablesorter.pager.js"></script>
        <script src="<?php echo base_url(); ?>js/tablesorter.js"></script>
        <script src="<?php echo base_url(); ?>js/bootbox.js"></script>
		<script src="<?php echo base_url(); ?>js/jquery-ui.min.js"></script>
        




    </head>
    <body>
        <div class="wrapper">

            <div class="header" style="display:none">

                <div class="container">
                    <div class="row">


                        <div class="span12">

                            <div class="pull-left">
                                <div class="logo">
                                   
                                </div>
                            </div>

                     </div>
                   </div>

              
                </div>

            </div>

            <div class="navbar navbar-inverse">
                <div class="navbar-inner noborder">
                    <div class="container margin80left">
                        <div class="row">
                            <div class="span12">
                            	<?php if(!isset($this->data['not_admin'])) {?>
                                <ul class="nav navupdate">
                                    <li>
                                        <a href="<?php echo base_url(); ?>index.php/admin">Home</a>
                                    </li>
									<li>
                                        <a href="<?php echo base_url(); ?>index.php">Create</a>
                                    </li>
                                     <li>
                                        <a href="<?php echo base_url(); ?>index.php/admin/tickets">Tickets</a>
                                    </li>
                                  
                                  <?php if(isset($isadmin)) : ?>
                                  
                                    <li>
                                        <a href="<?php echo base_url(); ?>index.php/admin/settings">Settings</a>
                                    </li>
                                    
                                     <li>
                                        <a href="<?php echo base_url(); ?>index.php/admin/departments">Departments</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url(); ?>index.php/admin/staffs">Staffs</a>
                                    </li>
                                    
                                    
                                      
                                   
                                   <?php endif ; ?>
                                    
                                </ul>
                                <ul class="nav pull-right navupdate">
                      				<li><a role="button" href="#profile" data-toggle="modal">Profile</a></li>
                      				<li><a href="<?php echo base_url(). 'index.php/admin/index/logout' ;?>">Logout</a></li>
			                   </ul>
							   <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
<div id="profile" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">Profile</h3>
	</div>
	<div class="modal-body">
		<p>

<form id="frmProfile">		   
	
				<fieldset>
                    <label>Staff Name</label>
                    <input type="text" name="firstname"  placeholder="First Name" value="<?php if(isset($staff['firstname'])) echo $staff['firstname'];?>"  />
                    <input type="text" name="lastname"  placeholder="Last Name" value="<?php if(isset($staff['lastname'])) echo $staff['lastname'];?>"  />
                </fieldset>
                <fieldset>
                    <label>Staff Email</label>
                    <input type="text" name="email"  placeholder="Enter a valid email address" value="<?php if(isset($staff['email'])) echo $staff['email']; ?>"  />
                </fieldset>
                 <fieldset>
                    <label>Password</label>
                    <input type="password" name="password"  />
                </fieldset>
</form>
<div id="profileerror" class="alert alert-danger hidden"></div>

</p>
	</div>
	<div class="modal-footer">
		<div id="profilespinner" class=" pull-left hidden"><i class="icon-spinner icon-spin"></i>    Processing...</div>	
		<button id="profilesave" class="btn btn-primary">Save changes</button>
		<button class="btn btn-link" data-dismiss="modal" aria-hidden="true">Close</button>
		
	</div>
</div>


<script>
	$(function(){
		
		$("#profilesave").click(function(){
	       
			var post = $("#frmProfile").serialize();
			
			$("#profilespinner").removeClass('hidden') ;
			$.post('<?php echo site_url();?>/admin/index/updateprofile', post, function(data){
					var result = $.parseJSON(data) ;
					if(result.status == 0 )
					{
						$("#profileerror").html(result.statusmsg).removeClass('hidden');	
					}
					else 
					 location.reload(); 
				$("#profilespinner").addClass('hidden') ;
			})
			
			
			
		})
		
	})
</script>
            

            <div class="container">

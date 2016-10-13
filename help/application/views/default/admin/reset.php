<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Sign in </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>
    <link href="<?php echo base_url(); ?>css/bootstrap-responsive.css" rel="stylesheet">
  </head>

  <body>

    <div class="container">


          
          
          <?php
          
           $attributes = array('class' => 'form-signin', 'id' => 'frmlogin');
           echo form_open('admin/login/resetpwd', $attributes); 
          ?>
 
          
        <h2 class="form-signin-heading">Password Reset</h2>
        
               
   
<?php
if(isset($error))
echo '<div class="alert alert-danger">' . $error . '</div>';
if(isset($success))
echo '<div class="alert alert-success">' . $success . '</div>';
?>  
Please enter your email address to reset password. The new password will be sent to your email address.
        <input type="text" name="email" class="input-block-level" placeholder="Email address" required="required">
       
        <button class="btn btn-large btn-primary" type="submit">Reset</button>
      </form>

    </div> <!-- /container -->

    
  </body>
</html>

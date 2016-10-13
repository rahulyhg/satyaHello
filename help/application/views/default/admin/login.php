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
           echo form_open('admin/login/validate', $attributes); 
          ?>
 
          
        <h2 class="form-signin-heading">Please sign in</h2>
        
               
   
<?php
if(isset($error))
echo '<div class="alert alert-danger">' . $error . '</div>';
if(isset($success))
echo '<div class="alert alert-success">' . $success . '</div>';
?>  
        <input type="text" name="email" class="input-block-level" placeholder="Email address" required="required">
        <input type="password" name="password" class="input-block-level" placeholder="Password" required="required"  >
        <label class="checkbox">
          <input type="checkbox" name="remember" value="on"> Remember me
        </label>
        <button class="btn btn-large btn-primary" type="submit">Sign in</button>
        <a class="btn btn-link"><?php echo anchor("admin/login/reset",'Reset Password');?> </a>
      </form>

    </div> <!-- /container -->

    
  </body>
</html>

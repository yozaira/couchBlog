<!DOCTYPE html>
<!--
We need to alter the .htaccess file, so that the request for the public files is not passed to the index.php file, but instead 
goes into to the public folder and finds the requested resource.
We just added RewriteRule to bypass our "catch all" rule that directs all requests if it's a public file. We then simplify the 
route to allow the URL to resolve to /css and /js instead of /public/css and /public/js.
-->
<html lang="en">
  <head>
    <link href="<?php echo $this->make_route('/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo $this->make_route('/css/master.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo $this->make_route('/css/bootstrap-responsive.min.css') ?>" rel="stylesheet" type="text/css" />   
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
  <body>    
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="<?php echo $this->make_route('/') ?>">Verge</a>
          <div class="nav-collapse">
            <ul class="nav">
            
              
             <!-- different navigation items are displayed depending on if the user is logged in or not. -->
              <?php if (Author::currentAuthor()) { ?>
              <li><a href="<?php echo $this->make_route('/admin/') ?>">Admin Home</a></li>
              <li><a href="<?php echo $this->make_route('/admin/' . Author::currentAuthor()) ?>">My Profile</a></li> 
              <li><a href="<?php echo $this->make_route('/add-post') ?>">Create Post</a></li>           
              <li><a href="<?php echo $this->make_route('/logout') ?>">Logout</a></li>
              <li><a href="<?php echo $this->make_route('/') ?>">Website Home</a></li>
              <?php } else { ?>
              
              <li><a href="<?php echo $this->make_route('/') ?>">Home</a></li>
              <li><a href="<?php echo $this->make_route('/signup') ?>">Create Account</a></li>
              <li><a href="<?php echo $this->make_route('/login') ?>">Login</a></li>
              
              <?php } ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!--
       We created a function called display_alert that checked to see if a variable with the
       passed variable was set. If it was set, then we displayed the contents of the variable in an
       alert box with help from Bootstrap. We then added two lines of code to layout.php, so we
       can display Flash messages for errors and success. Finally, we added a success Flash message
       to our signup process.
    -->

    <div class="container">
      <?php echo $this->display_alert('error'); ?> <!-- display_alert() function is on bones.php, book page 143-->
    	<?php echo $this->display_alert('success'); ?>  <!--  -->
      <?php include($this->content); ?>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
   <script type="text/javascript" src="<?php echo $this->make_route('/js/bootstrap.min.js') ?>"></script>
   <script type="text/javascript" src="<?php echo $this->make_route('/js/master.js') ?>"></script>
 
  </body>
</html>



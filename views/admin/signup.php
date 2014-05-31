<div class="page-header">
  <h1>Admin Signup</h1>
</div>

<div class="row">
  <div class="span12">
  
  

<form class="form-vertical" action="<?php echo $this->make_route('/signup') ?>" method="post">
<fieldset>
<label for="full_name">Full Name</label>
<input class="input-large" id="full_name" name="full_name" type="text" value="">

<label for="email">Email</label>
<input class="input-large" id="email" name="email" type="text" value="">

<label for="">Username</label>
<input class="input-large" id="username" name="username" type="text" value="">

<label for="">Password2</label>
<input class="input-large" id="password" name="password" type="password" value="">

<div class="form-actions">
<button class="btn btn-primary">Sign Up!</button>
</div>

</fieldset>
</form>
  


  
      <!-- 
  
    <form action="<?php echo $this->make_route('/signup') ?>" method="post">
      <fieldset>
          <?php Bootstrap::make_input('full_name', 'Full Name', 'text'); ?>
          <?php Bootstrap::make_input('email', 'Email', 'text'); ?>
          <?php Bootstrap::make_input('username', 'Username', 'text'); ?>
          <?php Bootstrap::make_input('password', 'Password', 'password'); ?>
        
          <div class="form-actions">
            <button class="btn btn-primary">Sign Up!</button>
          </div>
      </fieldset>
    </form>
    
   -->     
    
  </div>
</div>




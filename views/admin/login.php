
<div class="page-header">
  <h1>Login</h1>
</div>

<div class="row">
  <div class="span12">
    <form action="<?php echo $this->make_route('/login') ?>" method="post">
      <fieldset>
          <?php Bootstrap::make_input('username', 'Username', 'text'); ?>
          <?php Bootstrap::make_input('password', 'Password', 'password'); ?>
        
          <div class="form-actions">
            <button class="btn btn-primary">Login</button>
          </div>
      </fieldset>
    </form>
  </div>
</div>




<!--
<div class="page-header">
  <h1>Admin Login</h1>
</div>

<div class="row">
  <div class="span12">
    <form action="<?php echo $this->make_route('/login') ?>" method="post">
      <fieldset>
      
      <label for="">Username</label>
      <input class="input-large" id="username" name="username" type="text" value="">

       <label for="">Password2</label>
       <input class="input-large" id="password" name="password" type="password" value="">
       
          <?php //Bootstrap::make_input('username', 'Username', 'text'); ?>
          <?php //Bootstrap::make_input('password', 'Password', 'password'); ?>
        
          <div class="form-actions">
            <button class="btn btn-primary">Login</button>
          </div>
      </fieldset>
    </form>
  </div>
</div>


-->

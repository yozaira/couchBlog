<div class="page-header">
  <h1>Add a new Post</h1>
</div>

<div class="row">
  <div class="span12">
    <form action="<?php echo $this->make_route('/create-post') ?>" method="post">
       <!-- 
        The action executed on add-post.php form has to point to the same route used in this post(). Both routes -- make-route() 
        on add-post.php and post() func on index.php  -- has to be the same, so the add-post form knows what is going to execute on submition.
       -->  
      <fieldset>
          <?php Bootstrap::make_input('title', 'Title', 'text' ); ?>
          <?php Bootstrap::make_input('author', 'Author', 'text'); ?>
          <span>Author is added automatically when logged in</span>
          <?php Bootstrap::make_input('content', 'Content', 'textarea'); ?>
        
          <div class="form-actions">
            <button class="btn btn-primary">Submit Post</button>
          </div>
      </fieldset>
    </form>
  </div>
</div>

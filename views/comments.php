
<?php       	
echo $currentPost1.'<br/>';
echo $currentPost2.'<br/>';
echo $currentTitle1.'<br/>';
echo $currentPostId.'<br/><br/>';
echo $currentPostid1.'<br/><br/>';
?>

<div class="page-header">
  <h3>Write a comment</h3>
</div>

<div class="row">
  <div class="span12">
    <form action="<?php echo $this->make_route('/create-comment') ?>" method="post">
      <fieldset>
          <?php Bootstrap::make_input('username', 'Username', 'text'); ?>
          <?php Bootstrap::make_input('email', 'Email', 'text'); ?>
          <?php Bootstrap::make_input('comment', 'Comment', 'textarea'); ?>
        
          <div class="">
            <button class="btn btn-primary">Send</button>
          </div>
      </fieldset>
    </form>
  </div>
</div>


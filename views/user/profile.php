<div class="page-header">
	<h1><?php echo $user->full_name; ?>	
		<?php if ($isCurrentUser) { ?> <!-- $isCurrentUser var was set on index.php in get user route -->
			<code>This is you!</code>
		<?php } ?>
	</h1>
</div>

<div class="container">
  <div class="row">
    <div class="span4">
      <div class="well sidebar-nav">
        <ul class="nav nav-list">
          <li><h3>User Information</h3></li>
          <li><b>Username:</b> <?php echo $user->name; ?></li>
          <li><b>Email:</b> <?php echo $user->email; ?></li>
        </ul>
      </div>
    </div>
 <!--
 We used the $is_current_user variable to determine if the user viewing the profile is equal to the currently 
 logged-in user. Next, we created a form that posts to the post route
 -->   
    
    <div class="span8">
      <?php if ($isCurrentUser) { ?>
        <h2>Create a new post</h2>
        <form action="<?php echo $this->make_route('/post')?>" method="post">
          <textarea id="content" name="content" class="span8" rows="3"></textarea>	
          <button id="create_post" class="btn btn-primary">Submit</button>
        </form>
      <?php } ?>
      <!-- display a list of posts on the user profile page:-->
      
      <h2>Posts (<?php echo $post_count; ?>)</h2>
      
      <div id="post_list">
          <?php include('_posts.php'); ?>
     </div>

<!--
      <?php foreach ($posts as $post): ?>
      <div class="post-item row">
         <div class="span7">
            <strong><?php echo $user->name; ?></strong>
            <p><?php echo $post->content; ?></p>
            <?php echo $post->date_created; ?>
         </div>
       
         <div class="span1">
          <?php if ($isCurrentUser) { ?>
              <a href="<?php echo $this->make_route('/post/delete/' .$post->_id . '/' . $post->_rev)?>" class="delete">(Delete)</a>
           <?php } ?>
         </div>
         
         <div class="span8"></div>
     </div>
     <?php endforeach; ?>  <!--Update our public/ css/master.css file for the profile to look nice and clean.  
    </div>
  </div>
</div>


-->





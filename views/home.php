<div class="hero-unit">
  <h1>Welcome to this Site!</h1>
  <p> bla bla bla bla.</p>
  
  
  <p><a href="<?php echo $this->make_route('/signup') ?>"  class="btn btn-primary btn-large">Signup Now</a></p>
</div>



        <!--
        We used the $is_current_user variable to determine if the user viewing the profile is equal to the currently 
        logged-in user. Next, we created a form that posts to the post route.
        
        remove the same foreach statement from views/user/profile.php, and replace it with an include call to 
        // the newly created _posts file.
        -->   
      <div class="span8">       
          <!-- display the $post_count variable at the top of our post list, see index.php, line 125 -->   
          <h2>Posts</h2>
      
          <div id="post_list">
            <?php include('posts-content/all_posts.php'); ?>
          </div>   
          
          <div id="load_more" class="row">
              <div class="span8">
                 <a id="more_posts" href="#">Load More...</a>
              </div>
          </div>
    </div>     
    
  </div>
</div>

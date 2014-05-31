
       <div class="page-header">
	       <h1><?php echo $author->full_name; ?>	
		       <?php if ($isCurrentAuthor) { ?> 
			   <code>This is you!</code>			
		       <?php } ?>
	       </h1>
       </div>

<div class="container">
    <div class="row">
  
           <div class="span4">
             <div class="well sidebar-nav">
               <ul class="nav nav-list">
                 <li><h3>Author Information</h3></li>
                 <li><b>Username:</b> <?php echo $author->name; ?></li>
                 <li><b>Email:</b> <?php echo $author->email; ?></li>
                 <li><b>Password:</b> <?php echo $author->password; ?></li>
               </ul>
              </div>
            </div>
        <!--
        We used the $is_current_user variable to determine if the user viewing the profile is equal to the currently 
        logged-in user. Next, we created a form that posts to the post route.
        
        remove the same foreach statement from views/user/profile.php, and replace it with an include call to 
        // the newly created _posts file.
        -->   
      <div class="span8">       
          <!-- display the $post_count variable at the top of our post list, see index.php, line 125 -->   
          <h2>Posts (<?php echo $post_count; ?>)</h2>
      
          <div id="post_list">
            <?php include('_posts.php'); ?>
          </div>   
          
          <div id="load_more" class="row">
              <div class="span8">
                 <a id="more_posts" href="#">Load More...</a>
              </div>
          </div>
           <!--
           Now, let's open master.js, and create a function inside the closing brackets of the $(document).ready function
           -->
    </div>  
    
    
  </div>
</div>








<!--
We took all of the code that listed the posts out of profile.php and moved it into a new
partial called _posts.php. We began the filename with an underscore for no other reason
than for us to tell that it's different than normal views when we are looking through our
source code.

We began the filename with an underscore for no other reason
than for us to tell that it's different than normal views when we are looking through our
source code. 

By partial view, I meant that it's meant to be loaded into another page, by itself,
it would probably serve no purpose
-->


<!-- findPostId method on Post class returns $post as an array, so we need to iterated over it to get the results -->
 <?php foreach ($posts as $post): ?>

      <div class="post-item row">
          <div class="span7">
             <!-- These variables were set on index.php -- get('/author/:username/:skip', function($app) -- using the 
             get_posts_by_author method of Post class -->
             <p><?php echo $author->full_name; ?></p>
             <p><?php echo $post->date_created; ?></p>
             <p><b><?php echo $post->title; ?></b></p>
             <p><?php echo $post->content; ?></p>         
          </div>
              <!-- Hide delete botton form non authorized users
              While this method is no replacement for our previous validation function, it's a nice and friendly way for us 
              to safeguard against users from accidentally trying to delete other's posts.
              -->
          <div class="span1">	   
            <?php if ($isCurrentAuthor) { ?>
              <a href="<?php echo $this->make_route('/post/delete/' .$post->_id . '/' . $post->_rev)?>" class="delete">(Delete)</a>
              <a href="<?php echo $this->make_route('/post/update/' .$post->_id . '/' . $post->_rev)?>" class="delete">(Edit)</a>
            <?php } ?>
         </div>      
         <div class="span8"></div>       
    </div>   
   <?php endforeach; ?>  <!--Update our public/ css/master.css file for the profile to look nice and clean.  -->





<!--
We took all of the code that listed the posts out of profile.php and moved it into a new
partial called _posts.php. We began the filename with an underscore for no other reason
than for us to tell that it's different than normal views when we are looking through our
source code.

-->

<?php foreach ($posts as $post): ?>
	 <div class="post-item row">
	   <div class="span7">
		<div class="span1">
		    <img src="<?php //echo $user->gravatar('50'); ?>" />
	       </div>
	       
	       <div class="span5">
  		   <strong><?php echo $user->name; ?></strong>
  		   <p><?php echo $post->content; ?></p>
  		   <?php echo $post->date_created; ?>
  	      </div>
	   </div>
	 
        <div class="span1">
          <?php if ($isCurrentUser) { ?>
              <a href="<?php echo $this->make_route('/post/delete/' .$post->_id . '/' . $post->_rev)?>" class="delete">(Delete)</a>
           <?php } ?>
       </div>
       
	<div class="span8"></div>
	
   </div>
<?php endforeach; ?>

<!--
Now, let's remove the same foreach statement from views/user/profile.php,
and replace it with an include call to the newly created _posts file. Then let's add
a span inside our list's h2 element so that we can easily access it via jQuery.
-->

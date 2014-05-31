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


<div class="container">
    <div class="row">
  






              <?php

              
              // create a new instance of Bones, so that we can query CouchDB.
         	$bones = new Bones();
         	// instantiate an array called $posts, which we'll return at the end of this function.
         	$all_posts = array();
         	
         	// query the posts_by_author view by passing $username as the key, and let's use a foreach function to iterate through 
         	// all of the results into a holding $_post variable.
                 
              // we need to make sure that the get_ posts_by_author function uses the view without using the
              // reduce function. We'll do this by adding reduce=false to the query string. This tells the view not to 
              // run the reduce function.
              foreach ($bones->couch->get('_design/myblog/_view/posts_by_id')->body->rows as $_post) {
              
                     // use the data in the $_post variable to create and populate a new instance of Post.   
                     // You'll notice that we had to use $_post->value to get the post document. Remember that this is 
                     // because our view returns a list of keys and values, one for each document, and our entire document 
                     // lives in the value field.                
         		$post = new Post();
         		$post->_id = $_post->id;
         		// make  _rev available.
         		// In order for us to take any actions on an already existing document, we'll need to get the values of _rev, 
         		// along with _id in our post_by_author (), to ensure that we are acting on the most recent document.  
         		// (see base.php )
         		$post->_rev = $_post->value->_rev;
         		$post->date_created = $_post->value->date_created;
         		$post->content = $_post->value->content;
         		$post->author = $_post->value->author; 
         		$post->title= $_post->value->title; 
         		
         		// Then, let's add $post to the $posts array.     			
         		# array_push($all_posts, $post);
         		
         	}
         	//return $all_posts; // then on the home page, we will use  $posts and loop through it to get all the posts available
              ?>
  
          

     <?php 
	 // create a new instance of Bones, so that we can query CouchDB.
$bones = new Bones();
echo $currentPost1.'<hr/>';
echo //$currentPost2.'<br/>';
echo $currentPostId.'<hr/>';
              
	        
     echo $post->_id.'<br/>';
     echo $_post->value->title;

echo '<hr/>';





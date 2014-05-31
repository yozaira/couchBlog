<?php




class PostComment extends Base {

	public $date_created;
	public $content;
	public $username;
	public $email;
	public $postid;
		
		
	// Add a construct function to define the type of the document.
	public function __construct() {
		parent::__construct('comment');
	}
	
	
	
	
	public function create_comment() {
	
	    // create a instance of Bones, so that we can use Sag.  Set the variables of the current post object.
           $bones = new Bones();
           
           // In order to save the user document, we need to set the database, and log in as the admin user that we 
	    // set with our PHP constants. Then, we will put the user to CouchDB using Sag.
           $bones->couch->setDatabase('verge2');
                   
           // set the variables of the current post object.
                        
           $this->_id = $bones->couch->generateIDs(1)->body->uuids[0]; // use Sag to grab a UUID for us to use as the ID of our post
           
           // use date('r') to output the date into a RFC 2822 format (which is what CouchDB and JavaScript like) 
           // and saved it to the post's date_created variable. 
           $this->date_created = date('r');
          
           // Then, we set the user of the post to the current user's username.
           # $this->user= User::currentUser();
           
           // Finally, to make sure we didn't run into any errors, we wrapped the put command in a try...catch statement. 
           // In the catch segment, we passed the user on to Bones' error500 function if something went wrong
           
           try {
               // put the document to CouchDB using Sag.
               $bones->couch->put($this->_id, $this->to_json());
           }
           catch(SagCouchException $e) { 
                 echo $e->getCode();
		   $bones->set('error', 'There was a problem. Please, try again.');
                 $bones->error500($e);
           }          
           // In order to actually create a post, we'll need to create a route and handle the form input
           // To finish off the post creation, create a route called post in our index.php file to handle the post route.         
      }
  
  
  
             // The get_posts_by_user function uses the get_posts_by_user view to return a list of posts into a generic class, 
             // from which we iterated through each document, created individual Post objects, and pushed them into an array.            
             // In short, this function enabled us to pass in a user's username and retrieve an array of posts created by the passed user.
             // $skip with a default value of 0 and $limit with a default value of 10. page 225   
             // page 226: Update our get call to Sag so that it passes the value of $skip and $limit into the query.  
                       
         public function get_comment_by_postId($postId, $skip = 0, $limit = 2) {
         
              // create a new instance of Bones, so that we can query CouchDB.
         	$bones = new Bones();
         	// instantiate an array called $posts, which we'll return at the end of this function.
         	$all_comments = array();
         	
         	// query the posts_by_author view by passing $username as the key, and let's use a foreach function to iterate through 
         	// all of the results into a holding $_post variable.
                 
              // we need to make sure that the get_ posts_by_author function uses the view without using the
              // reduce function. We'll do this by adding reduce=false to the query string. This tells the view not to 
              // run the reduce function.
              foreach ($bones->couch->get('_design/myblog/_view/comments_by_post_id?key="' . $postId 
                                          . '"&descending=true&reduce=false&skip=' . $skip .'&limit=' . $limit)->body->rows as $_comment) {
                     // use the data in the $_post variable to create and populate a new instance of Post.   
                     // You'll notice that we had to use $_post->value to get the post document. Remember that this is 
                     // because our view returns a list of keys and values, one for each document, and our entire document 
                     // lives in the value field.                
         		$comment = new PostComment();
         		$comment->_id = $_comment->id;
         		// make  _rev available.
         		// In order for us to take any actions on an already existing document, we'll need to get the values of _rev, 
         		// along with _id in our post_by_author (), to ensure that we are acting on the most recent document.  
         		// (see base.php )
         		$comment->_rev = $_comment->value->_rev;
         		$comment->date_created = $_comment->value->date_created;
         		$comment->content = $_comment->value->content;
         		$comment->user = $_comment->value->user; 
         		$comment->email= $_comment->value->email; 
         		 // Then, let's add $comment to the $comments array.     			
         		array_push($all_comments, $comment);
         	}
         	return $all_comments;
         }        
  	  // Now that we have updated our function to include skip and limit, let's create a new route in index.php that's similar to 
  	  // the user/:username route but takes in a route variable of skip to drive the pagination. In this route, we're just going 
  	  // to return _comments partially, instead of the whole layout:
  	
  
  	
  	  // the reduce function grouped together all of the usernames from the map function and returned a count of 
  	  // how many times each username occurred in the list.
  	  
  	  // You'll notice that our reduce function also returned a key/value pair with the key equal to the 
  	  // username and the value equal to the count of posts that they have created.
         public function get_comment_count_by_user($username) {
              $bones = new Bones();   
              // add reduce=true to the query string to run the reduce function  
              $rows = $bones->couch->get('_design/myblog/_view/comments_by_user?key="' . $username . '"&reduce=true')->body->rows;   
              if ($rows) {
              // Once we get a result from the view, traverse through the data to get the value that is located 
              // in the value of the first returned row.
  		  return $rows[0]->value;
  		} 
  		else {
  		     return 0;
  		}
          }
  

 
          // create a nice and simple delete function, so we can delete the posts.   
          public function delete() {
              $bones = new Bones();
  		try {
  		     //  _rev,  and  _id values were retrived in post_by_author (), so we can make sure that we are
  		     // acting on an already existing document.
  		    $bones->couch->delete($this->_id, $this->_rev);
  		} 
  		catch(SagCouchException $e) {
  			$bones->error500($e);
  		}		
  		
  		// add a route in our index.php file that accepts _id and _rev. With this route, we can trigger the 
  		// deletion of posts from our profile page.
  		// Then update views/user/profile.php page, so that when users click on the delete link, they hit our route, 
  		// and the necessary variables are passed.
  	  }
  	



	 public function get_all_comments() {
         
              $bones = new Bones();
         	// instantiate an array called $posts, which we'll return at the end of this function.
         	$all_posts = array();
         	
         	// query the comments_by_author view by passing $username as the key, and let's use a foreach function to iterate through 
         	// all of the results into a holding $_comment variable.
                 
              // we need to make sure that the get_ comments_by_author function uses the view without using the
              // reduce function. We'll do this by adding reduce=false to the query string. This tells the view not to 
              // run the reduce function.
              foreach ($bones->couch->get('_design/myblog/_view/comments_by_date_created')->body->rows as $_comments) {
                     // use the data in the $_comment variable to create and populate a new instance of comment.   
                     // You'll notice that we had to use $_comment->value to get the comment document. Remember that this is 
                     // because our view returns a list of keys and values, one for each document, and our entire document 
                     // lives in the value field.                
         		$comment = new comment();
         		$comment->_id = $_comment->id;
         		// make  _rev available.
         		// In order for us to take any actions on an already existing document, we'll need to get the values of _rev, 
         		// along with _id in our comment_by_author (), to ensure that we are acting on the most recent document.  
         		// (see base.php )
         		$comment->_rev = $_comment->value->_rev;
         		$comment->date_created = $_comment->value->date_created;
         		$comment->content = $_comment->value->content;
         		$comment->author = $_comment->value->author; 
         		$comment->title= $_comment->value->title; 
         		 // Then, let's add $comment to the $comments array.     			
         		array_push($all_comments, $comment);
         	}
         	return $all_comments; // then on the home page, we will use  $comments and loop through it to get all the comments available.
         } 
	

	
}



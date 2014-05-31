
     
 <?php 
        	
echo $currentPost1.'<br/>';
echo $currentPost2.'<br/>';
echo $currentTitle1.'<br/>';
echo $currentPostId.'<br/><br/>';


                    $author = new Author();	
                    echo $author->full_name;
                    print_r( $author->full_name);		
		      // We then used Sag's get call to identify the document by ID and return it as a stdClass object called $document.
		      // issue a get call through Sag that will return a user by adding org.couchdb.user: to the passed username
       	#     $document = $bones->couch->get('org.couchdb.author:' . $username)->body;
       	      //$documents = $bones->couch->get('org.couchdb.user')->body;


// create a new instance of Bones, so that we can query CouchDB.
$bones = new Bones();
                      
     	      try {
                  foreach ($bones->couch->get('_design/myblog/_view/posts_by_id')->body->rows as $post_by_id) {
                      // echo $post_by_id->key.'<br>'; // debug 
                                              
                     // if ($post_by_id->key == $currentPost1 ) {
                      
                          //$app->set('currentPost1', 'Current Post: ' .$post_by_id->key ).'<br>';
                          echo $post_by_id->value->title.'<br>'; 
                          echo $post_by_id->value->author.'<br>'; 
                          echo $post_by_id->value->date_created.'<br>'; 
                          echo $post_by_id->value->content.'<br>'; 
                          echo $post_by_id->key.'<br>';                      
                      //}                      
                      //$app->set('currentPost2', 'This is the cp '.$app->request('id')  ).'<br>';   
                    } 
             
  		} 
  		catch(SagCouchException $e) {
  		echo $e;
  		}
  		
  		
  		
  
  		
  				             
  
              echo '<pre>';
             // print_r($bones->couch->get('_design/myblog/_view/posts_by_date_created')->body->rows);
              echo '<pre>';
 
 
              echo '<pre>';
             # print_r($bones->couch->get('_design/myblog/_view/posts_by_id')->body->rows);
              echo '<pre>';

 
// to be able to access the values in the array, we must loop thorough it

foreach ($bones->couch->get('_design/myblog/_view/posts_by_id?id="' .$currentPost1. '"&limit=1')->body->rows as $post_by_id) {
         //foreach ($bones->couch->get('_design/myblog/_view/posts_by_id?id="'.$currentPost1. '"&limit=1')->body->rows);

       echo 'Array id:       '.$post_by_id->id.'<br/>';
        #echo 'Array key:      '.$post_by_id->key.'<br/>';
        #echo 'Array value id: '.$post_by_id->value->_id.'<br/>';
        //echo 'Array value id: '.$post_by_id->value->title.'<br/>';  		
}
//echo  $postID->id; // then on the home page, we will use  $posts and loop through it to get all the posts available.
         	




echo '<hr/>';
echo 'This is the current Post: ' .$bones->couch->get('_design/myblog/_view/posts_by_date_created?id='.$currentPost1);  

if ( $bones->couch->get('_design/myblog/_view/posts_by_date_created?id='.$currentPost1)->body->rows) {
                       	
//echo 'FINALLY!!....  :'.$post_by_id->id.'<br/>'; 
//echo $currentPost1.'<br/>';  
         	
} 
                          
    	              

   
   
                          
echo '<br/>';
echo 'Array id:       '.$post_by_id->id.'<br/>';
echo $currentPost1.'<br/>';
echo $currentPost2.'<br/>';
echo $currentPost3.'<br/>';

  
  

  
  ?>
  
       
   
  <?php   
//$single_document =  $bones->couch->get('_design/myblog/_view/posts_by_id?id='.$post_by_id->key )->body; 
     $single_document =  $bones->couch->get('_design/myblog/_view/posts_by_id')->body->rows;
     //foreach ($single_document as $document ) {
     for($i =0; $i <= count($single_document); $i++ ) {
     if ($single_document[$i] == $post->_id ){
         echo $post->_id ;
     
         }else {echo 'ERROR';}

       /*
       if ($document->value->_id == $currentPost2){
       echo '................33333333 ';
       echo $document->value->date_created;
       echo $document->value->author;
       echo $document->value->title;       
       }
       */
       
      }
      
	
         		                    
              echo '<pre>';
               // print_r( $single_document); 
              echo '<pre>';
              
              
              
		$bones = new Bones();

		 // In order for us to return a post object, we needed to create a new post object called $post.			
	        $post = new Post();
       	      $single_post = $bones->couch->get('_design/myblog/_view/posts_by_id?id="' .$post_by_id->key. '"&limit=1')->body->rows;
       	      
       	      foreach ($single_post as $document) {	
			   // grab the values from the document variable and pass them into the corresponding values on the $post object
			   $post->_id =    $document->value->_id;
			   $post->title =  $document->value->title;
			   $post->author = $document->value->author;
			   $post->content= $document->value->content;			
			}
			// Finally, we returned the user document to wherever the function was called from.	
		     //  return $post;			       										
	
	echo 'LLLLLLL   '.$post->title.'<br/>';	
	echo 'LLLLLLL'.$post->_id.'<br/>';	
	
	
	
		
?> 
 <?php foreach ($single_post as $post): ?>

      <div class="post-item row">
          <div class="span7">
             <!-- These variables were set on index.php -- get('/author/:username/:skip', function($app) -- using the 
             get_posts_by_author method of Post class -->
             <p><?php //echo $author->full_name; ?></p>
             <p><?php echo $post->date_created; ?></p>
             <p><b><?php echo $post->title; ?></b></p>
             <p><?php echo $post->content; ?></p>         
          </div>
   <?php endforeach; ?>  <!--Update our public/ css/master.css file for the profile to look nice and clean.  -->
    
       
       
       
       
       
       
       
       
       
       
       
       









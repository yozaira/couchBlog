<?php

include 'lib/bones.php';
#require_once 'functions/app_functions.php';

       // we locked down our _users database, so we could secure our user data, meaning that any time we deal with the _users database, 
       // we need to provide the administrator login. For this, we'll add PHP constants for the user and the password at the top of the 
       // index.php file, so that we can reference it any time we need to perform an administrator function.

	// For this, we'll add PHP constants for the user and the password at the top of the index.php file, so that we can 
	// reference it any time we need to perform an administrator function. pag 127
	define('ADMIN_USER', 'yozaira');
	define('ADMIN_PASSWORD', '123');

       // - Our two get routes are now clean, little functions, including our route and a function that will act as our callback function
       // - Once the function is executed, we are using echo to display the simple text
       // - When a route is matched and a callback is executed from Bones, the instance of Bones is returned as the variable $app, which 
       //   can be used anywhere in the  callback function

       // For the root route, we used our new function set to pass a variable with the key of 'message' and its contents being 
       // 'Welcome Back!'
	get('/', function($app) {
	       // the two parameters in set() method - $index and $value - are passed to $var array (see set() in bones.php)
	       // in render method we looped through public $var = array (in bone.php).
		$app->set('message', 'Welcome Back!'); 
		$app->render('home');  // We are then going to tell Bones to render the home view, allowing us to see the message.
	});




       // This is the home page for administration area of the site
	get('admin/', function($app) {
	       // the two parameters in set() method - $index and $value - are passed to $var array (see set() in bones.php)
	       // in render method we looped through public $var = array (in bone.php).
		$app->set('message', 'Welcome to admin area!'); 
		$app->render('admin/home');  // We are then going to tell Bones to render the home view, allowing us to see the message.
	});
	
	

	

	get('/signup', function($app) {
		$app->render('admin/signup');
	});



       // Define a post method in index.php, so the form can be submitted.
	// Set all of the values for CouchDB user documents:
	// collect the simple fields: full_name, email, and roles. The fields full_name and email will come directly from the form
	// submission, and roles we will set to an empty array because this user has no special permissions
	post('/signup', function($app) {

		$author = new Author();
		$author->full_name = $app->form('full_name');
		$author->email = $app->form('email');	
              // roles we will set to an empty array because this user has no special permissions.
		// $author->roles = array();
		// call signup method
		$author->signup($app->form('username'), $app->form('password'));	
		// pag 143
		$app->set('success', 'Thanks for Signing Up ' . $author->full_name . '!');
	
		// Finally, let's close the user signup function and render the home page.
		$app->render('home');
	});



	get('/login', function($app) {
		$app->render('admin/login');
	});


       // Define a post method in index.php, so the form can be submitted.
	post('/login', function($app) {
		$author = new Author();
		$author->name = $app->form('username');  // bones.php
		$author->login($app->form('password'));  // bones.php	
		$app->set('success', 'You are now logged in!');
		$app->render('admin/home');
	});





	get('/logout', function($app) {
	  Author::logout();	  
	  $app->redirect('/');  
	  // This function is included in bones.php. It will allow us to redirect a user to a route by using make_route.
	});






/*  This did not work

       // Open index.php, and create a function called get_user_profile that takes $app as a parameter, and 
       // place it above /user/:username route
       // Copy the code from /user/:username/:skip into this function. But, this time, instead of just 
       // passing $app->request('skip'), let's check if it exists. If it exists, let's pass it to the get_posts_by_user 
       // function. If it doesn't exist, we'll just pass it 0.

       function get_author_profile($app) {
       
            $app->set('author', Author::get_by_username($app->request('username')));
            $app->set('isCurrentAuthor', ($app->request('username') == Author::currentAuthor() ? true : false));
            $app->set('posts', Post::get_posts_by_author($app->request('username'), ($app->request('skip') ? $app->request('skip') : 0)));
            $app->set('post_count', Post::get_post_count_by_author($app->request('username')));
            $app->render('admin/_posts', false);            
       }    
          // Finally, let's clean up both of our profile functions so that both of them just 
          // call the get_user_profile function.

       get('/admin/:username', function($app) {
           get_author_profile($app);
           $app->render('admin/profile');
       });
       
       
       get('/admin/:username/:skip', function($app) {
           get_author_profile($app);
           $app->render('admin/_posts', false);
       });


*/

	   // We are going to create a route so that people can see a profile by going to a unique URL. This will be the first time 
	   // that we'll really utilize our routing system's ability to handle route variables.	
	 get('/admin/:username', function($app) {	
	
	      // use the route variable :username to tell us the username that we want to find; 
	      // we'll pass this to the findByAuthorname function we created in the Author class.	   
	    $app->set('author', Author::findByAuthorname($app->request('username')));
	    	    	  
	     // add a variable called 'isCurrentAuthor' that will determine if the profile that you are viewing is equal to the 
	     // currently logged-in user. pag 171
	     
	     // if the username passed from the route is equal to that of the currently logged-in user, then return true, 
	     // otherwise return false
	     $app->set('isCurrentAuthor', ($app->request('username') == Author::currentAuthor() ? true : false));
	          
	      // # add the code to pass the returned posts from our get_posts_by_user function to a variable for our view to access. pag 225
            $app->set('posts', Post::get_posts_by_author($app->request('username')));
	    
	     // # Add code to pass the value from the get_post_count_by_user function to a variable that our view can access. page 205
	     $app->set('post_count', Post::get_post_count_by_author($app->request('username')));
	        
	     // Lastly, render the user/profile.php view
	     $app->render('admin/profile');
	     
           // Open views/user/profile.php, and add the corresponding code right below the Create a new post text area 
	    //so that we can display a list of posts on the user profile page. pag 199	   
	 });



  	// Now that we have updated our function to include skip and limit, let's create a new route in index.php that's similar to 
  	// the user/:username route but takes in a route variable of skip to drive the pagination. In this route, we're just going 
  	// to return _posts partially, instead of the whole layout:
       get('/author/:username/:skip', function($app) {
              $app->set('author', Author::get_by_username($app->request('username')));
              $app->set('isCurrentAuthor', ($app->request('username') == Author::currentAuthor() ? true : false));
              #$app->set('posts', Post::get_posts_by_author($app->request('username'), $app->request('skip')));
              $app->set('posts', Post::get_posts_by_author($app->request('username'), ($app->request('skip') ? $app->request('skip') : 0)));
              $app->set('post_count', Post::get_post_count_by_author($app->request('username')));
              $app->render('admin/_posts', false);
       });




	   // Display add post form
       get('/add-post', function($app) {
           $app->render('admin/add-post');
	});
		

          // Create a post:
          // The action executed on add-post.php form has to point to the same route used in this post(). Both routes -- make-route 
          // on add-post.php and post() func on index.php  -- has to be the same, so the add-post form knows what is going 
          // to execute on submition. page 181
    
          // This post route accepts the value of the passed value content and uses the create function on our Post class 
          // to actually create the post. Once the post is created, we'll redirect the user back to the admin-hom or to their profile.
	
       post('/create-post', function($app) {   
            
                 // check that the user is authenticated whe creating a post. 
              if (Author::isAuthenticated()) {
                 $post = new Post();
                 $post->content = trim($app->form('content') ); // content referes to the name value of the textarea 
                 $post->title = $app->form('title'); 
                 
                 // create the post by calling the public create function.
                 $post->create(); 
                 // added by me   :)              
                 $app->set('success', 'Your post '. ucwords($post->title). ' was created succesfully !');
                 $app->render('admin-home');
                 
                 // We can also redirect the user back to his/her own profile
                 # $app->redirect('/admin/' . Author::currentAuthor());                
              } 
              else {
                    // If it turns out that the user is not authenticated, our application will forward them to the user login 
                    // page with an error message.
                    $app->set('error', 'You must be logged in to do that.');
                    $app->render('admin/login');
              }                   
       });
       

     	
         // CouchDB has a really interesting way of listing and handling documents. In order to get into that discussion, 
         // we'll need to define how to use Design Documents for views and validation. page 185

  	  // With this route, we can trigger the deletion of posts from our profile page. page 209
  	  // Then update views/user/profile.php page, and add the route, so that when users click on the delete link, they hit our route, 
  	  // and the necessary variables are passed.
  
  	  
  	  // UPDATE: (page 221)
  	  // Let's change the route to use a delete method by changing get to delete. 
        get('/post/delete/:id/:rev', function($app) {
        # delete('/post/delete/:id/:rev', function($app) {   // it does not work...  
              $post = new Post();
              $post->_id = $app->request('id');
              $post->_rev = $app->request('rev');
              $post->delete();
              // UPDATE (page 221): Then, remove the success variable and the redirection code, because we'll no longer need them
            $app->set('success', 'Your post has been deleted');
            $app->redirect('/admin/' . Author::currentAuthor() );       // is not redirecting!!!
       });      
  	// Now that we have the backend support to delete the posts, let's add a route in our index.php file that 
  	// accepts _id and _rev.

 
 
            
            
         // DISPLAY SINGLE POST
        get('/post/:id/:title', function($app) {        
             $bones = new Bones();  
             # $app->set('single_post', Post::findByPostId($app->request('id') ) );   // DONT WORK
                           
     	      echo ' <!DOCTYPE html><html lang="en">
                       <head>
                       <title>slslsls</title>
                         <link href="/verge3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
                         <link href="/verge3/css/master.css" rel="stylesheet" type="text/css" />
                         <link href="/verge3/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />   
                       </head>
                       <body>   
                        <div class="container">
                        <div class="row"> <div class="span8">'; 
                        foreach ($bones->couch->get('_design/myblog/_view/posts_by_id')->body->rows as $post_by_id) {
                           // echo $post_by_id->key.'<br>'; // debug 
                                                 
                         if ($post_by_id->key == $app->request('id') && $post_by_id->value->title == $app->request('title') ) {
                          echo '<h2>'.$post_by_id->value->title.'</h2>'; 
                          echo '<p>'.$post_by_id->value->author.'</p>'; 
                          echo '<p>'.$post_by_id->value->date_created.'</p>'; 
                          echo '<p>'.$post_by_id->value->content.'</p>'; 
                          
                          //$app->set('author', Author::findByAuthorname($app->request($post_by_id->value->author)));   
                          $app->set('currentPostId', 'Current ID: '.$app->request('id')  ).'<br>';                            
                          $app->set('currentPostid1','Current Postid: ' .$post_by_id->key ).'<br>';
                          $app->set('currentTitle1', 'Current Title: ' .$post_by_id->value->title ).'<br>';                    
                          } 
                        } 
                        echo $currentPostId; 
                 echo '<div><div>';      
	          $app->render('/posts-content/'.$post_by_id->key);
	          // $app->render('/comments');
	          // $app->render('/posts-content/post-test');  
       });




       post('/create-comment', function($app) {           
                 // check that the user is authenticated whe creating a post. 
                 $comment = new PostComment();
                 $comment->content = $app->form('content'); // content referes to the name value of the textarea 
                 $comment->username = $app->form('username'); 
                 $comment->email = $app->form('email'); 
                 $comment->postId = $currentPostId;
                
                 // create the post by calling the public create function.
                 $comment->create_comment(); 
                 // added by me   :)              
                 $app->set('success', 'Thank you for your comment !');
                // $app->render('/posts-content/'.$post_by_id->key);
	         $app->render('/comments');                                                          
       });




/*
       // This can be used to display more content and different sections for the site
	get('post/:id', function($app) {
	       // the two parameters in set() method - $index and $value - are passed to $var array (see set() in bones.php)
	       // in render method we looped through public $var = array (in bone.php).
		$app->set('message', 'Welcome to admin area!'); 	
		$app->render('posts-content/all_posts');  
		// We are then going to tell Bones to render the home view, allowing us to see the message.
	});	
*/	
	
	
	
           // This view can be used to display more content and different sections for the site
	get('posts-content/', function($app) {
	       // the two parameters in set() method - $index and $value - are passed to $var array (see set() in bones.php)
	       // in render method we looped through public $var = array (in bone.php).
		$app->set('message', 'Welcome to admin area!'); 	
		$app->render('posts-content/single_post');  
		// We are then going to tell Bones to render the home view, allowing us to see the message.
	});
	


	// This function was created outside the Bones class and it can be called outside anywhere.
	// Has to be executed at the bottom of our index.php file after all of our routes.  It serves as a "clean up" 
	// function that will be executed if no routes match up. If no routes match, resolve will display a 404 error to the visitor 
	// and terminate the current script.	
	resolve();



/*  Test

echo '<pre>'; print_r( $_SERVER['PHP_SELF']); echo '<pre>';
$path = '';
// $path = '/ ';
// $path = '/user/';
$url = explode("/", $_SERVER['PHP_SELF']);
echo '<pre>'; print_r($url); echo '<pre>';


echo 'index 1: '. $url[1].'<br/>';
echo 'index 2: '.$url[2].'<br/>';


if ($url[1] == "index.php") {
   echo $path;
} 
else {
      echo 'Path: /' . $url[1] . $path.'<br/>';
}

$route_segments = explode('/', trim($this->route, '/'));
echo '<pre>'; print_r(route_segments); echo '<pre>';

*/










	

<?php



class User extends Base  {

	// Set all of the values for CouchDB user documents:
	// collect the simple fields: full_name, email, and roles. The fields full_name and email will come directly from the form
	// submission.  In order for us to add these fields to our user documents, we just need to add a few more variables 
	// into our user.php class.
	 
	 // add the fields that we know we'll want to collect information from theusers of Verge. Keep in mind that you can always add more 
	 // fields if your application needs it. pag 124
	 
	 // we need to be able to store a unique username, so that our users will have a unique URL, such as /user/johndoe. 
	 // Luckily, this functionality is already handled by CouchDB's name field. With that in mind, there's nothing to do here. 
	 // We'll just use the existing name instead!
	  protected $name;
	  protected $email;
	  
	  // The full name of the user, so we can display the name of the user as john Doe.
	  protected $full_name;        
	 
	  // $salt and $password_sha are used to safely store passwords.
	  protected $salt;
	  protected $password_sha;
	  
	  // will be empty in this book but can be useful for you to develop a role-based system, allowing certain users to be able 
	  // to see certain parts of your application, and so on.
	  protected $roles;
	
	
	
	
	  public function __construct() {
	      parent::__construct('user'); 
	  }
	  



	        public function signup($username, $password) {
	  
		    // This code is almost identical to the code that we entered in index.php, except that instead of referencing $user, we 
		    // are referencing $this. You'll also notice that full_name and email aren't located in this function.
		    // all references to $user have been changed to $this, because all of the variables we are using are attached to the current 
	           // user object. You'll also notice that, at the beginning, we created a new Bones object so that we could use it.
		    $bones = new Bones();
		    
		    // In order to save the user document, we need to set the database to _users, and log in as the admin user that we 
		    // set with our PHP constants. Then, we will put the user to CouchDB using Sag.
		    $bones->couch->setDatabase('_users');
		    
		    // In order for us to communicate with the users, we needed to have administrator credentials. To log in to CouchDB, we will 
		    // use the ADMIN_USER and ADMIN_PASSWORD constants
		    $bones->couch->login(ADMIN_USER, ADMIN_PASSWORD);

		    // roles we will set to an empty array because this user has no special permissions.
		    $this->roles = array();
		    		    
		    // Next, we'll want to capture the username that the user submitted, but we'll want to safeguard against weird 
		    // characters  or spaces, so we'll use a regular expression to convert the posted username to a lowercase 
		    // string without any special characters.
		    $this->name = preg_replace('/[^a-z0-9-]/', '', strtolower($username));
		    
		    // The end result will serve as our name field and also as a part of the ID (Remember that user documents require 
		    // that _id starts with org.couchdb.user and ends with the name of the user).
		    $this->_id = 'org.couchdb.user:' . $this->name;
		    
		    // In order to create the random salt, we can use CouchDB's RESTful JSON API. Couch provides a resource 
		    // at http://localhost:5984/_uuids that, when called, will return a unique UUID for us to use. Each UUID is a long and 
		    // random string, which is exactly what a salt needs! Sag makes getting a UUID super easy with the help of a function 
		    // called generateIDs.
		    $this->salt = $bones->couch->generateIDs(1)->body->uuids[0];
		    $this->password_sha = sha1($password . $this->salt);
		    
		    
		    // Sag includes an exception class called SagCouchException. This class gives us the ability to see how CouchDB 
		    // responded and then we can take action accordingly.

		    try {
		         $bones->couch->put($this->_id, $this->to_json());   // use the HTTP verb PUT to create the document in CouchDB
		    } 
		    catch(SagCouchException $e) {
		          // The 409 response means that there was an update conflict, which is due to the fact that the name 
		          // we are passing to the user is the same as the one that already exists.
		        if($e->getCode() == "409") {
		        
		           // set an error variable with an error message. Then we need to re-display the user/signup form, 
		           // so that the user has an opportunity to try the sign up process again. To make sure that no more 
		           // code is executed after this error, we used the exit command so that the application stops right where it is
			    $bones->set('error', 'A user with this name already exists.');
			    $bones->render('user/signup');
			    exit;
		        }
		        else {
		             $bones->error500($e);
	               }
		    }
	      }
	  
	  
	  
	   public function login($password) {
	  
	    // Create a new bones object so that we can access Sag.
	    $bones = new Bones();
	    // set the database to _users.
	    $bones->couch->setDatabase('_users');
	    
	      // Create a try…catch statement for our login code to live in. In the catch block, we are going to catch the 
	      // error code 401. If it is triggered, we want to tell the user that their login was incorrect.
	    
	    try {  
	          // start the session, and then to pass the username and password into CouchDB through Sag. When the 
	          // user is successfully logged in, grab the current user's username from CouchDB.
	          
	          // pass the username and the plain-text password to the login method of Sag, along with the setting Sag::$AUTH_COOKIE.
	          // By using cookie authentication, we can handle authentication without having to pass the username and password each time. 
	          // Luckily, Sag handles allf or us!   pag 149
	          $bones->couch->login($this->name, $password, Sag::$AUTH_COOKIE);
	          
	          // Next, we initialized a session with the session_start function, which allows us to set  session variables that 
	          // persist as long as our session exists.
	          session_start();
	          // use Sag to grab the session information using $bones->couch->getSession() and
	          // set a session variable for the username equal to the username of the currently logged in user.
	          // grab the body of the response with ->body() and finally grabbed the current user with userCtx
	          $_SESSION['username'] = $bones->couch->getSession()->body->userCtx->name;
	          
	          // close down the session. This will increase the speed and decrease the chances of locking.
	          session_write_close();
	    } 
	    catch(sagCouchException $e) {
	         // the catch block, we are going to catch the error code 401. If it is triggered, we want to tell the user that 
	         //their login was incorrect.
	       if ($e->getCode() == "401") {
		   $bones->set('error', 'Incorrect login credentials.');
		   $bones->render('user/login');
		   exit;
	      } 
	      else {
		    $bones->error500($e);
	      }
	    }
	  }
	  
	  // Finally, we need to add the login function to our post route in index.php.
	  
	  
	  
	  
	  public static function logout() {
	      // The reason we made it public static is that it really doesn't matter to us which user is currently logged in.
	  
	      // create a $bones instantiation as usual
	      $bones = new Bones();
	      
	      // By doing this, we are making the current user an anonymous user, effectively logging them out
	      $bones->couch->login(null, null);
	      session_start();
	      session_destroy();
	      
	      // Add a route into the index.php file, and have it call the logout function, using User::logout().
	  }
	  
	  
	 
	  public static function currentUser() {   // This func allows to retrieve the current user's username from the session.
	    session_start();
	    return $_SESSION['username'];
	    session_write_close();
	  }
	  
	  
	  
	  
	  public static function isAuthenticated() {  // This func allows to too see if the user is authenticated or not.
	    if (self::currentUser()) {
	        return true;
	    } 
	    else {
	         return false;
	    }
	  }
	  
	  // Now that we have our authentication in order, let's tighten up the navigation in layout.php, so that different 
	  // navigation items are displayed depending on if the user is logged in or not.
	  
  
  
	public static function findByUsername($username = null) {
	
		$bones = new Bones();
		// connect to the _users database
		$bones->couch->login(ADMIN_USER, ADMIN_PASSWORD);
		$bones->couch->setDatabase('_users');
		
		// In order for us to return a user object, we needed to create a new user object called $user.
		$user = new User();
				
		  // We then used Sag's get call to identify the document by ID and return it as a stdClass object called $document.
		  // issue a get call through Sag that will return a user by adding org.couchdb.user: to the passed username
		try {
		      $document = $bones->couch->get('org.couchdb.user:' . $username)->body;
		
			// grab the values from the document variable and pass them into the corresponding values on the $user object
			$user->_id =   $document->_id;
			$user->name =  $document->name;
			$user->email = $document->email;
			$user->full_name = $document->full_name;
			
			// Finally, we returned the user document to wherever the function was called from.	
		       return $user;			       							
			
		} 
		catch (SagCouchException $e) {  // Now that we are catching errors, let's add in our error500 function created in bones.php
			if($e->getCode() == "404") {
			   $bones->error404();
			} 
			else {	       
		             // this function allows to pass the exception in, so that the error can be displayed in the view (500.php)
		             $bones->error500($e);
		       }
		       
		       //  add this function to public function signup and public function login, using an if...else statement to 
		       // to catch any other exception, in other words, to trigger a 500 error if something unexpected happens.
		}	
		
					
		// Once the function to handle the finding of a user by the username is created, create the route in index.php that 
        	// will pass a username to this function.			
	 }
  
  
	
}




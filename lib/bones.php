<?php

	define('ROOT', __DIR__ . '/..');

       // Let's create a little helper class to help us create an HTML markup that can play nicely with Bootstrap:
	require_once ROOT . '/lib/bootstrap.php';
	
	require_once ROOT . '/lib/sag/src/Sag.php';

	function __autoload($classname) {
	   include_once(ROOT . "/classes/" . strtolower($classname) . ".php");
	}


       // Let's expand our current get function and create three more functions, one for each of the remaining HTTP
       // methods, making sure we pass in each method's name in caps.
	
	function get($route, $callback) { 
		Bones::register($route, $callback, 'GET'); // $method was passed into register() func so a method can be called. pag 74
	}

	function post($route, $callback) {
		Bones::register($route, $callback, 'POST');
	}

	function put($route, $callback) {
		Bones::register($route, $callback, 'PUT');
	}

	function delete($route, $callback) {
		Bones::register($route, $callback, 'DELETE');
	}
	
			  
       // This functions references a static function called resolve() created in Bone class 
	// This function has to be executed at the bottom of our index.php file after all of our routes.  It serves 
	// as a "clean up" function that will be executed if no routes match up. If no routes match, resolve will 
	// display a 404 error to the visitor and terminate the current script. page 168
	
	function resolve() {
	    Bones::resolve();
	 }
	
	
	

	class Bones {
		  private static $instance;
		  public static $route_found = false;
		  public static $rendered = false;
		  public $route = '';
		  public $method = '';
		  public $content = '';  // will house the path to the view that will be loaded into our layout
		 
		  // will allow us to store variables from our routes in index.php 
		  public $vars = array();	
		  // is set each time the Bones object is created using __construct(). 		 
		  public $route_segments = array(); 
		  // will be a library of variables that were passed in through the route, and it will enable us to use the index.php file.
		  public $route_variables = array();
		  public $couch;

	   	    		                
             // We just created our Bones class, added a few private and public variables, and a strange function called 
             // get_instance(). The private static variable $instance, mixed with the function get_instance(), forms something 
             // that is called The Singleton Pattern.
              
             // The Singleton Pattern allows our Bones class to not just be a simple class, but also to be one object. This means 
             // that each time we call our Bones class,  we are accessing a single existing object. But if the object does not exist, 
             // it will create a new one for us to use. It's a bit of a complex idea; however, I hope it starts to make sense as we 
             // make use of it down the road. page 61	  
		  
	      public static function get_instance() {
		    if (!isset(self::$instance)) {
		       self::$instance = new Bones();
		    }    
		    return self::$instance;
	       }
	
	
              // In this piece of code, we added a function called __construct(), which is a function that is automatically 
              // called each time a class is created. Our __construct() function then calls another function named get_route(), 
              // which will grab the route (if there is one) from our request query string and return it to the instance's route variable.
              
              // in the index.php file, we'll want to be able to define a route for a user profile. This route might be /user/:username. 
              // In this case, :username will be a variable that we can then access. So, if you went to the URL /user/tim, you could 
              // access the username tim by using Bones to grab that section of the URL, and return its value. page 78
	    
	      public function __construct() {
		    $this->route = $this->get_route();
		    // In order for us to get the method on each request, we will need to add  get_method() function and save
	           // th value in our instances variable $method. This means that when Bones is created on each request, it will 
	           // also retrieve the method and save it to our Bones instance, so that we can use it down the road.
		    $this->method = $this->get_method();
		    
		    // is set each time the Bones object is created using __construct(). This array splits $route into usable 
		    // segments by splitting them on a slash (/). This will allow us to examine the URL that the browser sends 
		    // to Bones, and then decide if the route matches.
		    $this->route_segments = explode('/', trim($this->route, '/'));
		    
		    $this->couch = new Sag('127.0.0.1', '5984');
		    $this->couch->setDatabase('verge2');
	      }



		 
	      protected function get_route() {
		       // Parses str as if it were the query string passed via a URL and sets variables in the current scope. 
		       // If the second parameter arr is present, variables are stored in this variable as array elements instead. 
		       // http://www.php.net/manual/en/function.parse-str.php
			parse_str($_SERVER['QUERY_STRING'], $route); // check page 58, 60
			if ($route) {
		          return '/' . $route['request'];
			} 
			else {
			      return '/';
			}
	       }
	       
	       
	       	       		  
		// This simple one liner is saying that if REQUEST_METHOD is set in $_SERVER, then return it, but 
		// if REQUEST_METHOD is not set for whatever reason, just return GET for the method to be safe.
		    
		 // Now that we are retrieving the method on each request, we need to alter our register function
               // so that we can pass $method along with each of our routes in order for them to match properly.
		protected function get_method() {
		      return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
		}
		  
		  
		  

                 // In order for us to set variables from our index.php file, we'll create a simple function called set that will allow 
                 // us to pass an index and a value for a variable and save it to the current Bones instance
		public function set($index, $value) {
		    $this->vars[$index] = $value;
		}
		
 
		 
		    // The first argument is $view, which is the name (or path) of the view you want to display, and the second 
		    // is $layout, which will define which layout we use to show the view. Layout will also have a default value, 
		    // so that we can keep things simple, in order to handle the displaying of views.  Add the following code to 
		    // the lib/bones.php file, right after the set function.
		    
		    // We created the render function that will set the path of the view that we want to display in our layout. All of the 
		    // views will be saved inside the views directory that we created earlier in this chapter. The code then loops through 
		    // each of the variables set in the instance's vars array. For each variable, we use a strange syntax $$, which allows 
		    // us to set a variable using the key we defined in our array. This will allow us to reference the variables directly 
		    // in our views. Finally, we added a simple if statement that checks to see if a layout file is defined. If $layout is 
		    // not defined, we'll simply return the    of the view. If $layout is defined, we'll include the layout, which 
		    // will return our view wrapped in the defined layout. We do this so that we can avoid using layouts down the road, 
		    // if we want. For instance, in an AJAX call, we might just want to return the view without the layout.

		  public function render($view, $layout = "layout") { // This means that, by default, Bones will look for views/layout.php.
		      $this->content = ROOT. '/views/' . $view . '.php';
		      foreach ($this->vars as $key => $value) {
		         $$key = $value;
		      }
		      if (!$layout) { // If $layout is not defined, we'll simply return the content of the view.
		         include($this->content);
		      } 
		      else {  // If $layout is defined, we'll include the layout, which will return our view wrapped in the defined layout. 
			    include(ROOT. '/views/' . $layout . '.php');        
		      }
		   }
		  
	  

                 // In order for us to match up the routes of our application, we will need to push each possible
                 // route through a function called register.
                 // The register function will be one of the most important functions in the Bones class down the road, but 
                 // we'll just get started by adding the following code at the end of our lib/bones.php file.
                 
                 // This function has two parameters: $route and $callback. $route contains the route that we are attempting to
                 // match against the actual route, and $callback is the function that will be executed if the routes do match. 
                 // Notice that, at the start of the register function, we call for our Bones instance, using 
                 // the static:get_instance() function. This is the Singleton Pattern in action, returning the single instance 
                 // of the Bones object to us.
                 
                 // The register function then checks to see if the route that we visited through our browser matches the route that 
                 // was passed into the function. If there is a match, our $route_found variable will be set to true, which will 
                 // allow us to skip looking through the rest of the routes. The register function will then execute a callback 
                 // function that will do the work that was defined in our route. Our Bones instance will also be passed with the 
                 // callback function, so that we can use it to our advantage. If the route is not a match, we will return false 
                 // so that we know the route wasn't a match.
                 
                 //  $method variable will store the HTTP method that was performed on each request.
                 
		  public static function register($route, $callback, $method) { 
		  
			 // We added an if statement that checked to see if the route has already matched. If it has, we just ignore 
			 // everything else in the register function.	
		    if (!static::$route_found) {
		    
			  $bones = static::get_instance();
			  // $url_parts will split up the route that we pass into the register function, and will help us 
			  // compare this route against the actual route the browser hit.
		         $url_parts = explode('/', trim($route, '/'));
		         $matched = null;

                       // Let's start to compare $bones->route_segments, which is the route that the browser hit, against $url_parts, 
                       // which is the route that we are trying to match.
                       // check to make sure that $route_segments and $url_parts are the same length. This will make sure 
                       // that we save time by not digging deeper into the function, since we already know it doesn't match.
                       
		       if (count($bones->route_segments) == count($url_parts)) {
		          // loop each of the $url_parts, and try to match it against route_segments.
			   foreach ($url_parts as $key=>$part) {
			   
			            // check for the existence of a colon (:). This means that this segment contains a variable value
			        if (strpos($part, ":") !== false) { 
			        
				     // Contains a route variable.
				     
				     // take the value of the segment and save it into our $route_variables array, allowing us to use it later.
				     // Just because we found one matching variable, it does not mean that the whole route is a match, 
				     // so we aren't going to set $matched = true just yet.
				     
				     // Then, $bones->route_variables[substr($part, 1)] saves the value into the $route_variables 
				     // array with the index set to the $part value and then uses substr to make sure that we don't 
				     // include the colon in the key.
			            $bones->route_variables[substr($part, 1)] = $bones->route_segments[$key];
			        } 
			        else {
			             // Does not contain a route variable
			             if ($part == $bones->route_segments[$key]) {
				         if (!$matched) {
					    // Routes match
					    $matched = true;
				         }
			             } 
			             else {
					    // Routes don't match
				           $matched = false;
			             }
			         }
			     }
		      } 
		      else {
			     // Routes are different lengths
			     $matched = false;
		      }

                       // If there is no match, we return false and exit out of this function. 
		      if (!$matched || $bones->method != $method) {
			   return false;
		      } 
		      else {
		            // If there is a match, we set $route_found = true, and then perform a callback on the route, which 
		            // will execute the code inside of the route defined in the index.php file.
			     static::$route_found = true; 			  
			     echo $callback($bones); 			     
		      }
		      
		   }
		
	        }             
               // add a few routes to our index.php file that call the get function that lives in the lib/bones.php folder.


                // Now that we are saving the route variables into an array, we need to add a function called request.
                // This function accepts a variable called $key and returns the value of the object in our
                // route_variables array by returning the value with that same key.
		  public function request($key) {
		     return $this->route_variables[$key];
		  }
		  
		  
		  
		  public function form($key) {
		    return $_POST[$key];
		  }


	  
                // This function will allow our Bones instance to create clean links so that we can link to other 
                // resources in our application
		  public function make_route($path = '') {
		    $url = explode("/", $_SERVER['PHP_SELF']);
		    return '/' . $url[1] . $path; // return index 1 followed by whatever value $path has 
		  }		  
		  // Open up the file verge/views/signup.php, and add a simple form. Define a post method in index.php, so the form
		  // can be submitted.
		  


  		  // pag 143
		  // This function will be called to see if the alert variable is set. If the alert variable is set, we will 
		  // echo some HTML to 
		  // show the alert box on the layout.
		    
	  	  // NOTE: Add code to layout.php, right inside of the container div to display the Flash call the display_alert function.
		  public function display_alert($variable = 'error') {
		      if (isset($this->vars[$variable])) {
			   return "<div class='alert alert-" . $variable . "'>
			           <a class='close' data-dismiss='alert'>x</a>" . $this->vars[$variable] . "</div>";
			}
		  }
		  // Now that we've added these alert messages, let's go back to our signup POST route in index.php and add back 
		  // in a alert message that thanks the user for signing up.	  	  
		  // Add code to layout.php, right inside of the container div to display display_flash function.
		  
		  
		  
		  
	         public function redirect($path = '') {
		    header('Location: ' . $this->make_route($path));
		  }
		  
		  
		  
		  
		  // This func is called on 500 errors on 500.php file and in user.php
		  // handle 500 errors in our application. 500 errors refer to the HTTP status code 500, which is 
		  // an "Internal Server Error". Generally, this means that something happened, and we didn't handle it properly.
		  
		  // this function allow us to pass the exception in, so that we can display it in the view
		  public function error500($exception) {
		   $this->set('exception', $exception);
		   $this->render('error/500');
		   exit;
		  }
		
		
		  // this function renders error/404.php ( a view that show any time a 404 error occurs in our application) 
		  // and terminates the current script so no further actions occur.
		  public function error404() {
		   $this->render('error/404');
		   exit;
		  }
		
		
		  // function determines if a route was ever found, and it can be called at the end of our routes
		  public static function resolve() {
		    if (!static::$route_found) {
			$bones = static::get_instance();
			$bones->error404();
		    }
		  }
		
		
		
    } // end class
	
	
	
	
	
	
	
	

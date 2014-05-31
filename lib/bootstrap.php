<?php 


  
     class Bootstrap {

      public static function make_input($name, $label, $type, $value='') {
           echo '<label for="' . $name . '">' . $label . '</label>';
           // Conditional to determine what kind of element to create:
	    if ( ($type == 'text') || ($type == 'password') ) {   // Create text or password inputs.	           
	         echo '<input class="input-large" id="' . $name . '" name="' . $name . '" type="' . $type . '" value="' . $value . '">'; 
            }
            if ($type == 'textarea') { 	
              // Start creating the textarea:
              echo '<textarea class="span4" name="' . $name . '" id="' . $name . '" rows="8" cols="275" >';		
	          // Add the value to the textarea:
	          //if ($value) echo $value;
	          // Complete the textarea:
	       echo '</textarea>';		
           }
	   
      } 
   
    
  }
   
   
  
  /*
  
    class Bootstrap {

    public static function make_input($id, $label, $type, $value = '') {
      echo '<label for="' . $id . '">' . $label . '</label>
					<input class="input-large" id="' . $id . '" name="' . $id . '" type="' . $type . '" value="' . $value . '">';
    }
		
  }
  

	
		


  
  function create_form_input($name, $type, $placeholder, $errors) {	
	// Assume no value already exists:
	$value = false;
	// Check for a value in POST:
	if (isset($_POST[$name])) $value = $_POST[$name];
	
	// Strip slashes if Magic Quotes is enabled:
	if ($value && get_magic_quotes_gpc()) $value = stripslashes($value);

	// Conditional to determine what kind of element to create:
	if ( ($type == 'text') || ($type == 'password') ) { // Create text or password inputs.	
		// Start creating the input:
		echo '<input class=" span3"  type="' . $type . '" name="' . $name . '"  id= "' . $name . '"    
		       placeholder="' . $placeholder . '" ' ;

		// Add the value to the input:
		if ($value) echo ' value="' . htmlspecialchars($value) . '"';
		
		// Check for an error:
		if (array_key_exists($name, $errors)) {
		   echo 'class="error" /> <span class="error">' . $errors[$name] . '</span>';
		} 
		else { echo ' />'; }		
	} 
	elseif ($type == 'textarea') { // Create a TEXTAREA.	
		// Display the error first: 
		if (array_key_exists($name, $errors)) echo ' <span class="error">' . $errors[$name] . '</span>';
		   // Start creating the textarea:
		   echo '<textarea class="span4" name="' . $name . '" id="' . $name . '" rows="8" cols="275" ';	
		// Add the error class, if applicable:
		if (array_key_exists($name, $errors)) {
			echo ' class="error">';
		} 
		else {echo '>';	}		
		// Add the value to the textarea:
		if ($value) echo $value;
		// Complete the textarea:
		echo '</textarea>';		
	} // End of primary IF-ELSE.

} // End of the create_form_input() function.


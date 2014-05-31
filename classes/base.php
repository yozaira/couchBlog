<?php

abstract class Base {

   // We currently aren't setting _id in any of our projects, but we'll need to do that for our user documents. Let's open up 
   // classes/base.php, and add _id so that we have the option of setting _id on any document. pag 125

   // every CouchDB document requires this field. we need to be able to set and retrieve _id for our user documents.
   // We added _id to this class because we know that every CouchDB document requires this field. We have been able to live 
   // without _id because CouchDB has automatically set one for us so far. However, we'll need to be able to set and 
   // retrieve _id for our user documents.
   protected $_id; 
   
   // In order for us to take any actions on an already existing document, we'll need to pass _rev, along with _id, to ensure 
   // that we are acting on the most recent document.  
   protected $_rev;
  
   protected $type; 



  public function __construct($type) {
    $this->type = $type;
  }

  public function __get($property) {
    return $this->$property;
  }

  public function __set($property, $value) {
    $this->$property = $value;
  }


   // now anytime we call the to_json function, _rev will always be included, regardless of it being used or not. 
   // If we were to send CouchDB a null _rev, it would throw an error. page 
   
   // So, let's add some code to the to_json function below to unset our _rev variable if it has no value set.
  public function to_json() {
       if (isset($this->_rev) === false) {
           unset($this->_rev);
       }
      return json_encode(get_object_vars($this));
  }
  
  
  
  
}





<div class="hero-unit">
<!--
A 404 error refers to the HTTP status code 404, meaning "Not Found". A 404 error usually
occurs when you try to access something that doesn't exist, such as going to an incorrect
URL. In our case, we are receiving a 404 error because we are trying to find a CouchDB
document that doesn't exist.

In order for us to render this view, let's add another function called error404 into
our lib/bones.php file. This function will nicely display 404 errors for us.

Now that we have our 404 error handler, let's display it when the 404 error occurs in the
get_by_username function inside of classes/user.php.
-->
  <h1>Page Not Found</h1>

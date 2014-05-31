<div class="hero-unit">
  <h1>An Error Has Occurred</h1>
  <!--
 This function called error500 in lib/bones.php that will allow us to display 500 errors easily around our application.
 While we are in the process of debugging our application, this page will be of great use to us to track down what errors
 are occurring.
  -->
  <p><strong>Code:</strong> <?php echo $exception->getCode(); ?></p>
  <p><strong>Message:</strong> <?php echo $exception->getMessage(); ?></p>
  <p><strong>Exception:</strong> <?php echo $exception; ?></p>
</div>

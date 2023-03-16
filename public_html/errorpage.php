<?php
    include_once('header.php');
?>
<!-- <h1>404 Error Page</h1> -->
<p class="zoom-area"><b><?php echo $_SESSION['errorpage_content']; ?></b></p>
<section class="error-container">
  <span>4</span>
  <span><span class="screen-reader-text">0</span></span>
  <span>4</span>
</section>
<?php
	unset($_SESSION['errorpage_content']);
    include_once('footer.php');
?>
<?php
if($force_stop){
  die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once('metadata.php'); ?>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg bg-dark">
        <div class="container-fluid">
            <img alt="Lumienux Logo" src="img/brand_logo.png">
<?php include_once('nav.php'); ?>
        </div>
      </nav>
</header>
<main>
<?php flashMessage('info'); ?>
<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title><?= $site->title() ?></title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">

 

</head>
<style>
body{
  background: #eee;
}

</style>

<body>
   
<?php
if($_SERVER['REQUEST_URI'] == "/"){
 go("/panel");
}

?>

</body>
</html>


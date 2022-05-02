<?php
$_SESSION = array();
if (isset($_COOKIE[session_name()])) {
  setcookie(session_name(), '', time() - 42000, '/');
}
session_destroy();

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/top.css">
  <title>TOP</title>
</head>

<body>
  <main>
    <div class="admin">
      <a href="admin/admin_login.php">管理担当者</a>
    </div>
    <div class="title_wrapper">
      <img src="image/ggp_logo.png" alt="">
      <h3>G's Geek Pitch</h3>
      <a class="start_button" href="menu.php">START</a>
    </div>
  </main>
</body>

</html>
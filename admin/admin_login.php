<?php
session_start();
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
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/admin_login.css">
  <title>管理者ログイン</title>
</head>

<body>
  <main>
    <div class="sub_title">
      <h2>
        管理者
      </h2>
    </div>

    <div class="menu_contents">
      <form action="admin_menu.php" method="POST">
        <div class="pass_input">
          <p>pass</p>
          <input type="password" name="password">
        </div>
        <div class="button_container">
          <input type="submit" value="ログイン">
        </div>
      </form>
    </div>

    <div class="logo">
      <a href="../index.php"><img src="../image/G's_logo.png" alt=""></a>
    </div>

  </main>
</body>

</html>
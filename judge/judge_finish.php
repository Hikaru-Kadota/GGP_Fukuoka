<?php
session_start();
$_SESSION = array();
if (isset($_COOKIE[session_name()])) { //session_name()は、セッションID名を返す関数
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
  <link rel="stylesheet" href="../css/judge_finish.css">
  <title>審査終了</title>
</head>

<body>
  <main>
    <div class="title_wrapper">
      <h3>全ての審査が終了しました</h3>
      <a href="../menu.php">メニューへ</a>
    </div>

  </main>
</body>

</html>
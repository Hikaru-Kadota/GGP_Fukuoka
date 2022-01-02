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
  <link rel="stylesheet" href="css/menu.css">
  <title>menu</title>
</head>

<body>
  <main>
    <div class="sub_title">
      <h2>
        MENU
      </h2>
    </div>

    <div class="menu_contents">
      <div>
        <a class="content_button" href="judge/judge_form.php">審査に参加</a>
      </div>
      <div>
        <!-- <a class="content_button" href="entry/entry_form.php">登壇予約</a> -->
        <a class="content_button" href="all_ggp.php">登壇予約・GGP一覧</a>
        <a class="content_button" href="ranking.php">歴代順位を見る</a>
        <a class="content_button" href="evaluation.php">評価・コメント</a>
        <!-- <input type="submit" value="分析する" onClick="return error();"> -->
      </div>
    </div>

    <div class="logo">
      <a href="index.php"><img src="image/G's_logo.png" alt=""></a>
    </div>

  </main>

  <script type="text/javascript">
    function error() {
      alert("ただいま工事中です");
      return false;
    }
  </script>

</body>

</html>
<?php
session_start();
include("../functions.php");
$pdo = connect_to_db();

if ($_POST['password'] != NULL) {
  $password = $_POST['password'];
  $sql = 'SELECT * FROM admin_table WHERE password = :password';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':password', $password, PDO::PARAM_STR);
  $status = $stmt->execute();
  $val = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($val) {
    session_start();
    $_SESSION = array();
    $_SESSION["session_id"] = session_id();
    $_SESSION["password"] = $val['password'];
  } else {
    header("Location:admin_login.php");
    exit();
  }
} else {
  if (!$_SESSION['password']) {
    header("Location:admin_login.php");
    exit();
  } else {
    check_session_id();
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/menu.css">
  <title>admin_menu</title>
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
        <a class="content_button" href="result_announcement.php">結果発表</a>
        <a class="content_button" href="presenter_announcement.php">本日の登壇者</a>
        <a class="content_button" href="season_build.php">新規シーズン</a>
      </div>
      <div>
        <a class="content_button" href="../all_ggp.php">GGP一覧</a>
        <a class="content_button" href="../ranking.php">歴代順位を見る</a>
        <a class="content_button" href="../evaluation.php">評価・コメント</a>
        <!-- <a class="content_button" href="">分析する</a> -->
      </div>
    </div>

    <div class="logo">
      <a href="../index.php"><img src="../image/G's_logo.png" alt=""></a>
    </div>

  </main>
</body>

</html>
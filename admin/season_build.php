<?php
session_start();
include("../functions.php");
$pdo = connect_to_db();
$season_id = $_POST['season_id'];
check_session_id();

?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/season_build.css">
  <title>新規シーズン</title>
</head>

<body>
  <main>

    <div class="message">
      <h3>新規シーズンをビルド</h3>
    </div>

    <form action="season_create.php" method="POST">

      <div class="input_container">

        <div class="input">
          <p>シーズン名</p>
          <input type="text" name="season_name" placeholder="必須">
        </div>

        <div class="input">
          <p>テーマ</p>
          <input type="text" name="season_theme" placeholder="必須">
        </div>

        <div class="input">
          <p>開催日</p>
          <input type="date" name="season_date" placeholder="必須">
        </div>
      </div>

      <div class="button_container">
        <input type="submit" value="DONE" onClick="return check();">
      </div>

    </form>
    <div class="logo">
      <a href="admin_menu.php"><img src="../image/G's_logo.png" alt=""></a>
    </div>
  </main>

  <script type="text/javascript">
    function check() {
      if (entry.season_id.value == "-" ||
        entry.entry_name.value == "" ||
        entry.entry_name.value == " ") {
        alert("お名前が未入力か、受付中のシーズンがありません");
        return false;
      } else {
        return true;
      }
    }
  </script>

</body>

</html>
<?php
include("../functions.php");
$pdo = connect_to_db();

$sql = 'SELECT * FROM season_table ORDER BY season_date ASC;';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();
$event = $stmt->fetchALL(PDO::FETCH_ASSOC);

$output = "";
if ($_GET != NULL) {
  $output .= "<option value='{$_GET['season_id']}'>{$_GET['season_name']} ({$_GET['season_date']})</option>";
} else {
  $output .= "<option value='-'>選択して下さい</option>";
}

for ($i = 0; $i < count($event); $i++) {
  $sql = 'SELECT * FROM presenter_table WHERE season_id = :season_id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':season_id', $event[$i]['season_id'], PDO::PARAM_INT);
  $status = $stmt->execute();
  $presenter_count = $stmt->fetchALL(PDO::FETCH_ASSOC);
  if (count($presenter_count) < 5) {
    if ($event[$i]['season_id'] != $_GET['season_id']) {
      $output .= "
    <option value='{$event[$i]['season_id']}'>{$event[$i]['season_name']} ({$event[$i]['season_date']})</option>";
    }
  }
};

if ($output == "") {
  $output .= "<option value='-'>受付停止中</option>";
}

?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/entry_form.css">
  <title>エントリー</title>
</head>

<body>
  <main>

    <div class="message">
      <h3>GGPエントリー</h3>
      <h4>エントリー受付順が登壇する順番となります</h4>
    </div>

    <form action="entry.php" method="POST" name="entry">

      <div class="input_container">

        <div class="season_select">
          <p>エントリーするシーズン</p>
          <select name="season_id">
            <?= $output ?>
          </select>
        </div>

        <div class="class_select">
          <p>所属クラス</p>
          <select name="class">
            <option value="F_LAB_06">F_LAB_06</option>
            <option value="F_LAB_07">F_LAB_07</option>
            <option value="F_DEV_09">F_DEV_09</option>
            <option value="F_DEV_10">F_DEV_10</option>
            <option value="F_DEV_11">F_DEV_11</option>
            <option value="Y_DEV_01">Y_DEV_01</option>
            <option value="Y_DEV_02">Y_DEV_02</option>
            <option value="S_DEV_03">S_DEV_03</option>
            <option value="S_DEV_04">S_DEV_04</option>
            <option value="T_LAB_12">T_LAB_12</option>
            <option value="T_DEV_21">T_DEV_21</option>
            <option value="T_DEV_22">T_DEV_22</option>
          </select>
        </div>

        <div class="name_input">
          <p>登壇者氏名</p>
          <input type="text" name="entry_name" placeholder="必須">
        </div>
      </div>

      <div class="button_container">
        <input type="submit" value="確定" onClick="return check();">
      </div>

    </form>
    <div class="logo">
      <a href="../menu.php"><img src="../image/G's_logo.png" alt=""></a>
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
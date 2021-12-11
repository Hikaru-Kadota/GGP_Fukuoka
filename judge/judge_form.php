<?php
include("../functions.php");
$pdo = connect_to_db();
$today = date("Y-m-d");

$sql = 'SELECT * FROM season_table WHERE season_date <= :season_date ORDER BY season_date DESC;';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':season_date', $today, PDO::PARAM_STR);
$status = $stmt->execute();
$event = $stmt->fetchALL(PDO::FETCH_ASSOC);

$output = "";
for ($i = 0; $i < count($event); $i++) {
  $output .= "<option value='{$event[$i]['season_id']}'>{$event[$i]['season_name']} ({$event[$i]['season_date']})</option>";
};

?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/judge_form.css">
  <title>JUDGE</title>
</head>

<body>
  <main>

    <div class="message">
      <h3>〜 公平な審査にご協力ください 〜</h3>
      <h4>順位へ反映される得点は、登壇者全員を評価して下さった方を対象とし、<br>その他の方は、得点・コメントの記録のみとなります。</h4>
    </div>

    <form action="judge.php" method="POST" name="judge">

      <div class="input_container">
        <div class="season_select">
          <p>審査するシーズン</p>
          <select name="season_id">
            <?= $output ?>
          </select>
        </div>

        <div class="name_input">
          <p>審査員氏名</p>
          <input type="text" name="judge_name" placeholder="必須">
        </div>
      </div>

      <div class="button_container">
        <input type="submit" value="審査を開始" onClick="return check_name();">
      </div>

    </form>
    <div class="logo">
      <a href="../menu.php"><img src="../image/G's_logo.png" alt=""></a>
    </div>
  </main>

  <script type="text/javascript">
    function check_name() {
      if (judge.judge_name.value == "" ||
        judge.judge_name.value == " ") {
        alert("お名前を入力して下さい");
        return false;
      } else {
        return true;
      }
    }
  </script>

</body>

</html>
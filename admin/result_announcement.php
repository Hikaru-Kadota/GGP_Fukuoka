<?php
session_start();
include("../functions.php");
$pdo = connect_to_db();
check_session_id();
$today = date("Y-m-d");

$sql = 'SELECT * FROM season_table WHERE season_date <= :season_date ORDER BY season_date DESC;';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':season_date', $today, PDO::PARAM_STR);
$status = $stmt->execute();
$event = $stmt->fetchALL(PDO::FETCH_ASSOC);

$output = "";
for ($i = 0; $i < count($event); $i++) {
  $output .= "<option value='{$event[$i]['season_id']}'>{$event[$i]['season_name']} ({$event[$i]['season_date']})</option>";
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/result_announcement.css">
  <title>結果発表</title>
</head>

<body>
  <main>
    <form action="result_gp.php" method="POST">
      <div class="button_container">
        <button type="submit">結果発表に進む</button>
      </div>
      <div class="input_container">
        <div class="season_select">
          <p>シーズン選択</p>
          <select name="season_id">
            <?= $output ?>
          </select>
        </div>
      </div>
    </form>
    <div class="logo">
      <a href="admin_menu.php"><img src="../image/G's_logo.png" alt=""></a>
    </div>
  </main>
</body>

</html>
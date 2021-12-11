<?php
session_start();
include("../functions.php");
$pdo = connect_to_db();
check_session_id();
$today = date("Y-m-d");


$sql = 'SELECT * FROM season_table WHERE season_date = :season_date ORDER BY season_date DESC;';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':season_date', $today, PDO::PARAM_STR);
$status = $stmt->execute();
$season = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = 'SELECT * FROM presenter_table WHERE season_id = :season_id ORDER BY presenter_id ASC';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':season_id', $season['season_id'], PDO::PARAM_INT);
$status = $stmt->execute();
$todays_presenter = $stmt->fetchALL(PDO::FETCH_ASSOC);

$output = "";
for ($i = 0; $i < count($todays_presenter); $i++) {
  $rank = $i + 1;
  if ($i % 2 == 0) {
    $output .= "<div class='row'>
        <div class='row_1'>
          <p>{$rank}</p>
        </div>
        <div class='row_2'>
          <p>{$todays_presenter[$i]['class']}</p>
        </div>
        <div class='row_3'>
          <p>{$todays_presenter[$i]['presenter_name']}</p>
        </div>
      </div>
      ";
  } else {
    $output .= "<div class='row border'>
        <div class='row_1'>
          <p>{$rank}</p>
        </div>
        <div class='row_2'>
          <p>{$todays_presenter[$i]['class']}</p>
        </div>
        <div class='row_3'>
          <p>{$todays_presenter[$i]['presenter_name']}</p>
        </div>
      </div>
      ";
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
  <link rel="stylesheet" href="../css/season_ranking.css">
  <title>本日の登壇者</title>
</head>

<body>
  <main>

    <div class="main_title">
      <h3>season <?= $season['season_name'] ?></h3>
      <h3>テーマ 『<?= $season['season_theme'] ?>』</h3>
    </div>

    <div class="ranking_table">

      <div class="index">
        <div class="content_index content_index_1">
        </div>
        <div class="content_index content_index_2">
          <p>クラス</p>
        </div>
        <div class="content_index content_index_3">
          <p>登壇者</p>
        </div>
      </div>

      <?= $output ?>

    </div>

    <div class="title_wrapper">
      <a href="admin_menu.php">admin_MENU</a>
    </div>

  </main>
</body>

</html>
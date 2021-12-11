<?php
session_start();
include("../functions.php");
$pdo = connect_to_db();
check_session_id();

$season_id = $_GET['season_id'];

$sql = 'SELECT * FROM season_table WHERE season_id = :season_id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
$status = $stmt->execute();
$season = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = 'SELECT * FROM result_table WHERE season_id = :season_id ORDER BY ranking ASC';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
$status = $stmt->execute();
$season_result = $stmt->fetchALL(PDO::FETCH_ASSOC);


$output = "";
for ($i = 0; $i < count($season_result); $i++) {
  $presenter_id = $season_result[$i]['presenter_id'];
  $sql = 'SELECT * FROM presenter_table WHERE season_id = :season_id AND presenter_id = :presenter_id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
  $stmt->bindValue(':presenter_id', $presenter_id, PDO::PARAM_INT);
  $status = $stmt->execute();
  $presenter = $stmt->fetch(PDO::FETCH_ASSOC);

  $rank = $season_result[$i]['ranking'];
  $presenter_point = sprintf("%.3f", $season_result[$i]['final_point']);
  $presenter_name = $presenter['presenter_name'];
  if ($i % 2 == 0) {
    $output .= "<div class='row'>
        <div class='row_1'>
          <p>{$rank}位</p>
        </div>
        <div class='row_2'>
          <p>{$presenter_point}</p>
        </div>
        <div class='row_3'>
          <p>{$presenter_name}</p>
        </div>
      </div>
      ";
  } else {
    $output .= "<div class='row border'>
        <div class='row_1'>
          <p>{$rank}位</p>
        </div>
        <div class='row_2'>
          <p>{$presenter_point}</p>
        </div>
        <div class='row_3'>
          <p>{$presenter_name}</p>
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
  <title>シーズンランキング</title>
</head>

<body>
  <main>

    <div class="main_title">
      <h3>GGP</h3>
      <h3>season <?= $season['season_name'] ?></h3>
    </div>

    <div class="ranking_table">

      <div class="index">
        <div class="content_index content_index_1">
        </div>
        <div class="content_index content_index_2">
          <p>評価点</p>
        </div>
        <div class="content_index content_index_3">
          <p>登壇者</p>
        </div>
      </div>

      <?= $output ?>

    </div>

    <div class="title_wrapper">
      <a href="../ranking.php?season_id=<?= $season_id ?>">歴代</a>
    </div>

  </main>
</body>

</html>
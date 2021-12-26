<?php
session_start();
include("../functions.php");
$pdo = connect_to_db();
check_session_id();

$season_id = $_POST['season_id'];

//登壇者一覧を取得
$sql = 'SELECT * FROM presenter_table WHERE season_id = :season_id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
$status = $stmt->execute();
$presenters = $stmt->fetchALL(PDO::FETCH_ASSOC);

if ($presenters == NULL) {
  header("Location:result_announcement.php");
  exit();
}


$sql = 'SELECT * FROM result_table WHERE season_id = :season_id AND ranking = :ranking';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
$stmt->bindValue(':ranking', 1, PDO::PARAM_INT);
$status = $stmt->execute();
$grandprix = $stmt->fetchALL(PDO::FETCH_ASSOC);

if ($grandprix == NULL) {
  // (grandprix == NULL) = result_tableに結果がない ＝> 集計から実施する必要あり(今終わったGGP用)

  //①審査員全員のIDを配列化
  $sql = 'SELECT * FROM judge_table WHERE season_id = :season_id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
  $status = $stmt->execute();
  $all_judge = $stmt->fetchALL(PDO::FETCH_ASSOC);
  $all_judge_id = [];
  for ($i = 0; $i < count($all_judge); $i++) {
    array_push($all_judge_id, $all_judge[$i]['judge_id']);
  }

  //②relation_table上に、審査が5件未満 or スキップした審査員を除外する(5件未満 = 途中で落ちてしまった場合など)
  $sql = 'SELECT * FROM relation_table WHERE season_id = :season_id AND judge_point != 0';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
  $status = $stmt->execute();
  $relation_judges = $stmt->fetchALL(PDO::FETCH_ASSOC);

  $judges_id = [];
  for ($i = 0; $i < count($all_judge_id); $i++) {
    if (array_count_values(array_column($relation_judges, 'judge_id'))[$all_judge_id[$i]] == '5') {
      array_push($judges_id, $all_judge_id[$i]);
    }
  }
  var_dump($judges_id);
  exit();


  $result_arr = [];
  for ($i = 0; $i < count($presenters); $i++) {

    $presenter_id = $presenters[$i]['presenter_id'];
    if ($presenter_id == NULL || $judges_id[0] == NULL || $season_id == NULL) {
      header("Location:result_announcement.php");
      exit();
    }

    $presenter_point = 0;
    for ($j = 0; $j < count($judges_id); $j++) {
      $sql = 'SELECT * FROM relation_table WHERE season_id = :season_id AND judge_id = :judge_id AND presenter_id = :presenter_id';
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
      $stmt->bindValue(':judge_id', $judges_id[$j], PDO::PARAM_INT);
      $stmt->bindValue(':presenter_id', $presenter_id, PDO::PARAM_INT);
      $status = $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($result) {
        $presenter_point = $presenter_point += $result['judge_point'];
      }
    }
    $final_point = floatval($presenter_point / count($judges_id) . "n");
    $array = array(['presenter_id' => $presenter_id, 'final_point' => $final_point]);
    $result_arr = array_merge($result_arr, $array);
  }

  foreach ((array) $result_arr as $key => $value) {
    $sort[$key] = $value['final_point'];
  }
  array_multisort($sort, SORT_ASC, $result_arr);

  for ($i = 0; $i < count($result_arr); $i++) {
    $presenter_id = $result_arr[$i]['presenter_id'];
    $final_point = $result_arr[$i]['final_point'];
    if ($final_point != $result_arr[$i - 1]['final_point']) {
      $ranking = $i + 1;
    }
    $sql = 'INSERT INTO result_table (result_id, season_id, presenter_id, ranking, final_point) VALUES (NULL, :season_id, :presenter_id, :ranking, :final_point)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
    $stmt->bindValue(':presenter_id', $presenter_id, PDO::PARAM_INT);
    $stmt->bindValue(':ranking', $ranking, PDO::PARAM_INT);
    $stmt->bindValue(':final_point', $final_point, PDO::PARAM_STR);
    $status = $stmt->execute();
  }

  $sql = 'SELECT * FROM result_table WHERE season_id = :season_id AND ranking = :ranking';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
  $stmt->bindValue(':ranking', 1, PDO::PARAM_INT);
  $status = $stmt->execute();
  $grandprix = $stmt->fetchALL(PDO::FETCH_ASSOC);
}

$output = "";
for ($i = 0; $i < count($grandprix); $i++) {
  $grandprix_presenter_id = $grandprix[$i]['presenter_id'];
  $sql = 'SELECT * FROM presenter_table WHERE season_id = :season_id AND presenter_id = :presenter_id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
  $stmt->bindValue(':presenter_id', $grandprix_presenter_id, PDO::PARAM_INT);
  $status = $stmt->execute();
  $grandprix_presenter = $stmt->fetch(PDO::FETCH_ASSOC);
  $grandprix_point = $grandprix[$i]['final_point'];
  $grandprix_point = sprintf("%.3f", $grandprix_point);

  $output .= "<div class='grandprix_name'>
        <img src='../image/king.png' alt=''>
        <h1>{$grandprix_presenter['presenter_name']}</h1>
        <h2>{$grandprix_point}点</h2>
      </div>";
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/result_gp.css">
  <title>グランプリ</title>
</head>

<body>
  <main>

    <div class="main_title">
      <h1>優勝</h1>
    </div>
    <div class="grandprix">
      <?= $output ?>
    </div>

    <div class="title_wrapper">
      <a href="season_ranking.php?season_id=<?= $season_id ?>">全体</a>
    </div>

  </main>
</body>

</html>
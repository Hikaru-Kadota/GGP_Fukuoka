<?php
session_start();
include("functions.php");
$pdo = connect_to_db();

$sql = 'SELECT * FROM season_table ORDER BY season_date DESC;';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();
$event = $stmt->fetchALL(PDO::FETCH_ASSOC);

$output = "";
for ($i = 0; $i < count($event); $i++) {
  $season_id = $event[$i]['season_id'];
  $season_name = $event[$i]['season_name'];
  $season_date = $event[$i]['season_date'];
  $season_theme = $event[$i]['season_theme'];

  $sql = 'SELECT * FROM result_table WHERE season_id = :season_id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
  $status = $stmt->execute();
  $season_result = $stmt->fetchALL(PDO::FETCH_ASSOC);


  $sql = 'SELECT * FROM presenter_table WHERE season_id = :season_id ORDER BY presenter_id ASC';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':season_id', $event[$i]['season_id'], PDO::PARAM_INT);
  $status = $stmt->execute();
  $presenter = $stmt->fetchALL(PDO::FETCH_ASSOC);

  if ($season_result) {
    $output .= "<div class='contents'>
                <div class='content_0'>シーズン<br>終了</div>
                <div class='content_1'>{$season_name}</div>
                <div class='content_2'>{$season_date}</div>
                <div class='content_3'>{$season_theme}</div>
                <div class='content_4'>";
  } else {
    if (count($presenter) < 5) {
      $output .= "<div class='contents'>
                <div class='content_0'><a href='entry/entry_form.php?season_id={$season_id}&season_name={$season_name}&season_date={$season_date}'>ENTRY</a></div>
                <div class='content_1'>{$season_name}</div>
                <div class='content_2'>{$season_date}</div>
                <div class='content_3'>{$season_theme}</div>
                <div class='content_4'>";
    } else {
      $output .= "<div class='contents'>
                <div class='content_0'>受付終了</div>
                <div class='content_1'>{$season_name}</div>
                <div class='content_2'>{$season_date}</div>
                <div class='content_3'>{$season_theme}</div>
                <div class='content_4'>";
    }
  }




  for ($j = 0; $j < 5; $j++) {
    $number = $j + 1;
    if ($presenter[$j] != NULL) {
      $output .= "<div class='mini_content'>
                  <div class='mini_content_1'>{$number}</div>
                  <div class='mini_content_2'>{$presenter[$j]['class']}</div>
                  <div class='mini_content_3'>{$presenter[$j]['presenter_name']}</div>
                </div>";
    } else {
      $output .= "<div class='mini_content'>
                  <div class='mini_content_1'>{$number}</div>
                  <div class='mini_content_2 mini_content_NULL'>- - - - - - - -</div>
                  <div class='mini_content_3 mini_content_NULL'>募集中</div>
                </div>";
    }
  }
  $output .= "</div></div>";
}

$button = "";
if ($_SESSION['password'] == NULL) {
  $button .= "<a href='menu.php'>MENU</a>";
} else {
  $button .= "<a href='admin/admin_menu.php'>admin_MENU</a>";
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/all_ggp.css">
  <title>審査フォーム</title>
</head>

<body>
  <main>

    <div class="main_title">
      <h1>GGP 一覧</h1>
    </div>
    <div class="title_wrapper">
      <?= $button ?>
    </div>

    <div class="season_list">
      <div class="index">
        <p class="content_0"></p>
        <p class="content_1">シーズン</p>
        <p class="content_2">開催日</p>
        <p class="content_3">テーマ</p>
        <p class="content_4">登壇者</p>
      </div>
      <?= $output ?>
    </div>

  </main>

</body>

</html>
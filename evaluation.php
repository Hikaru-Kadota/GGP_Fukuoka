<?php
session_start();
include("functions.php");
$pdo = connect_to_db();

$sql = 'SELECT * FROM item_table';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();
$item = $stmt->fetchALL(PDO::FETCH_ASSOC);

$sql = 'SELECT * FROM judgement_table ORDER BY season_id ASC, presenter_id ASC';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();
$evaluation = $stmt->fetchALL(PDO::FETCH_ASSOC);

$output .= "";
for ($i = 0; $i < count($evaluation); $i++) {
  $season_id = $evaluation[$i]['season_id'];
  $sql = 'SELECT * FROM season_table WHERE season_id = :season_id ORDER BY season_id ASC';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
  $status = $stmt->execute();
  $season = $stmt->fetch(PDO::FETCH_ASSOC);

  $sql = 'SELECT * FROM presenter_table WHERE season_id = :season_id AND presenter_id = :presenter_id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':season_id', $season['season_id'], PDO::PARAM_INT);
  $stmt->bindValue(':presenter_id', $evaluation[$i]['presenter_id'], PDO::PARAM_INT);
  $status = $stmt->execute();
  $presenter = $stmt->fetch(PDO::FETCH_ASSOC);

  $evaluation_point = ($evaluation[$i]['item_1'] + $evaluation[$i]['item_2'] + $evaluation[$i]['item_3'] + $evaluation[$i]['item_4'] + $evaluation[$i]['item_5']) / 5;
  $evaluation_point = sprintf("%.3f", $evaluation_point);

  if ($i % 2 == 0) {
    $output .= "<div class='row border'>
        <div class='row_1'>
          <p>{$season['season_name']}</p>
        </div>
        <div class='row_2'>
          <p>{$season['season_theme']}</p>
        </div>
        <div class='row_2_a'>
          <p>{$presenter['presenter_name']}</p>
        </div>
        <div class='row_3'>
          <p>{$evaluation[$i]['item_1']}</p>
        </div>
        <div class='row_4'>
          <p>{$evaluation[$i]['item_2']}</p>
        </div>
        <div class='row_5'>
          <p>{$evaluation[$i]['item_3']}</p>
        </div>
        <div class='row_6'>
          <p>{$evaluation[$i]['item_4']}</p>
        </div>
        <div class='row_7'>
          <p>{$evaluation[$i]['item_5']}</p>
        </div>
        <div class='row_8'>
          <p>{$evaluation_point}</p>
        </div>
        <div class='row_9'>
          <p>{$evaluation[$i]['comment']}</p>
        </div>
      </div>";
  } else {
    $output .= "<div class='row'>
        <div class='row_1'>
          <p>{$season['season_name']}</p>
        </div>
        <div class='row_2'>
          <p>{$season['season_theme']}</p>
        </div>
        <div class='row_2_a'>
          <p>{$presenter['presenter_name']}</p>
        </div>
        <div class='row_3'>
          <p>{$evaluation[$i]['item_1']}</p>
        </div>
        <div class='row_4'>
          <p>{$evaluation[$i]['item_2']}</p>
        </div>
        <div class='row_5'>
          <p>{$evaluation[$i]['item_3']}</p>
        </div>
        <div class='row_6'>
          <p>{$evaluation[$i]['item_4']}</p>
        </div>
        <div class='row_7'>
          <p>{$evaluation[$i]['item_5']}</p>
        </div>
        <div class='row_8'>
          <p>{$evaluation_point}</p>
        </div>
        <div class='row_9'>
          <p>{$evaluation[$i]['comment']}</p>
        </div>
      </div>";
  }
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
  <link rel="stylesheet" href="css/evaluation.css">
  <title>評価・コメント</title>
</head>

<body>
  <div class="main_2">

    <div class="main_title">
      <h3>評価・コメント</h3>
      <h4>順位へ反映されないものを含む、全ての評価結果となります。</h4>
    </div>

    <div class="title_wrapper">
      <?= $button ?>
    </div>
    <div class="categories">
      <div class="category">
        <p>項目１</p>
        <p><?= $item[0]['item_name'] ?></p>
      </div>
      <div class="category">
        <p>項目２</p>
        <p><?= $item[1]['item_name'] ?></p>
      </div>
      <div class="category">
        <p>項目３</p>
        <p><?= $item[2]['item_name'] ?></p>
      </div>
      <div class="category">
        <p>項目４</p>
        <p><?= $item[3]['item_name'] ?></p>
      </div>
      <div class="category">
        <p>項目５</p>
        <p><?= $item[4]['item_name'] ?></p>
      </div>
    </div>



    <div class="evaluation_table">
      <div class="index">
        <div class="content_index content_index_1">
          <p>シーズン</p>
        </div>
        <div class="content_index content_index_2">
          <p>テーマ</p>
        </div>
        <div class="content_index content_index_2_a">
          <p>登壇者</p>
        </div>
        <div class="content_index content_index_3">
          <p>項目１</p>
        </div>
        <div class="content_index content_index_4">
          <p>項目２</p>
        </div>
        <div class="content_index content_index_5">
          <p>項目３</p>
        </div>
        <div class="content_index content_index_6">
          <p>項目４</p>
        </div>
        <div class="content_index content_index_7">
          <p>項目５</p>
        </div>
        <div class="content_index content_index_8">
          <p>評価点</p>
        </div>
        <div class="content_index content_index_9">
          <p>コメント</p>
        </div>
      </div>

      <?= $output ?>
    </div>

    <div id="js-pagetop">
      <span>Top</span>
    </div>

  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script>
    $(function() {
      var pagetop = $('#js-pagetop');
      pagetop.hide();
      $(window).scroll(function() {
        if ($(this).scrollTop() > 500) {
          pagetop.fadeIn();
        } else {
          pagetop.fadeOut();
        }
      });
      pagetop.click(function() {
        $('body, html').animate({
          scrollTop: 0
        }, 500);
        return false;
      });
    });
  </script>
</body>

</html>
<?php
session_start();
include("functions.php");
$pdo = connect_to_db();

if ($_GET['season_id'] != NULL) {
  $season_id = $_GET['season_id'];
} else {
  $season_id = "-";
}

// 総合ランキング生成 -------------------------------------------------------------------------------------------
$sql = 'SELECT * FROM result_table ORDER BY final_point asc';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();
$total_ranking = $stmt->fetchALL(PDO::FETCH_ASSOC);

$output = "";
for ($i = 0; $i < count($total_ranking); $i++) {
  $presenter_id = $total_ranking[$i]['presenter_id'];
  $presenter_season = $total_ranking[$i]['season_id'];

  $sql = 'SELECT * FROM presenter_table WHERE season_id = :season_id AND presenter_id = :presenter_id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':season_id', $presenter_season, PDO::PARAM_INT);
  $stmt->bindValue(':presenter_id', $presenter_id, PDO::PARAM_INT);
  $status = $stmt->execute();
  $presenter = $stmt->fetch(PDO::FETCH_ASSOC);
  $presenter_name = $presenter['presenter_name'];
  $presenter_class = $presenter['class'];
  $presenter_point = sprintf("%.3f", $total_ranking[$i]['final_point']);

  $sql = 'SELECT * FROM season_table WHERE season_id = :season_id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':season_id', $presenter_season, PDO::PARAM_INT);
  $status = $stmt->execute();
  $season = $stmt->fetch(PDO::FETCH_ASSOC);
  $season_name = $season['season_name'];
  $season_theme = $season['season_theme'];

  if ($total_ranking[$i]['final_point'] != $total_ranking[$i - 1]['final_point']) {
    $rank = $i + 1;
  }

  if ($season_id == $presenter_season) {
    if ($i % 2 == 0) {
      $total_output .= "<div class='row border color'>
        <div class='row_1'>
          <p>{$rank}位</p>
        </div>
        <div class='row_2'>
          <p>{$season_name}</p>
        </div>
        <div class='row_3'>
          <p>{$season_theme}</p>
        </div>
        <div class='row_4'>
          <p>{$presenter_point}</p>
        </div>
        <div class='row_5'>
          <p>{$presenter_name}</p>
        </div>
        <div class='row_6'>
          <p>{$presenter_class}</p>
        </div>
      </div>
      ";
    } else {
      $total_output .= "<div class='row color'>
        <div class='row_1'>
          <p>{$rank}位</p>
        </div>
        <div class='row_2'>
          <p>{$season_name}</p>
        </div>
        <div class='row_3'>
          <p>{$season_theme}</p>
        </div>
        <div class='row_4'>
          <p>{$presenter_point}</p>
        </div>
        <div class='row_5'>
          <p>{$presenter_name}</p>
        </div>
        <div class='row_6'>
          <p>{$presenter_class}</p>
        </div>
      </div>
      ";
    }
  } else {
    if ($i % 2 == 0) {
      $total_output .= "<div class='row border'>
        <div class='row_1'>
          <p>{$rank}位</p>
        </div>
        <div class='row_2'>
          <p>{$season_name}</p>
        </div>
        <div class='row_3'>
          <p>{$season_theme}</p>
        </div>
        <div class='row_4'>
          <p>{$presenter_point}</p>
        </div>
        <div class='row_5'>
          <p>{$presenter_name}</p>
        </div>
        <div class='row_6'>
          <p>{$presenter_class}</p>
        </div>
      </div>
      ";
    } else {
      $total_output .= "<div class='row'>
        <div class='row_1'>
          <p>{$rank}位</p>
        </div>
        <div class='row_2'>
          <p>{$season_name}</p>
        </div>
        <div class='row_3'>
          <p>{$season_theme}</p>
        </div>
        <div class='row_4'>
          <p>{$presenter_point}</p>
        </div>
        <div class='row_5'>
          <p>{$presenter_name}</p>
        </div>
        <div class='row_6'>
          <p>{$presenter_class}</p>
        </div>
      </div>
      ";
    }
  }
}
// -----------------------------------------------------------------------------------------------------------



// シーズンランキング生成 -------------------------------------------------------------------------------------------
$sql = 'SELECT * FROM result_table ORDER BY season_id DESC';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();
$seasons = $stmt->fetchALL(PDO::FETCH_ASSOC);



$season_output = "";
for ($i = 0; $i < count($seasons); $i++) {
  var_dump($seasons[$i]);
  exit();
  $sql = 'SELECT * FROM result_table WHERE season_id = :season_id ORDER BY ranking';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':season_id', $seasons[$i]['season_id'], PDO::PARAM_INT);
  $status = $stmt->execute();
  $season_result = $stmt->fetchALL(PDO::FETCH_ASSOC);
  if ($season_result != NULL) {
    $sql = 'SELECT * FROM season_table WHERE season_id = :season_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':season_id', $season_result[$i]['season_id'], PDO::PARAM_INT);
    $status = $stmt->execute();
    $season = $stmt->fetch(PDO::FETCH_ASSOC);
    $season_name = $season['season_name'];
    $season_theme = $season['season_theme'];
    $season_output .= "<div class='contents'>
                <div class='content_0'>{$season_name}</div>
                <div class='content_1'>{$season_theme}</div>
                <div class='content_2'>";
    for ($j = 0; $j < count($season_result); $j++) {
      $sql = 'SELECT * FROM presenter_table WHERE season_id = :season_id AND presenter_id = :presenter_id';
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':season_id', $season_result[$j]['season_id'], PDO::PARAM_INT);
      $stmt->bindValue(':presenter_id', $season_result[$j]['presenter_id'], PDO::PARAM_INT);
      $status = $stmt->execute();
      $presenter = $stmt->fetch(PDO::FETCH_ASSOC);
      $presenter_rank = $season_result[$j]['ranking'];
      $presenter_name = $presenter['presenter_name'];
      $presenter_class = $presenter['class'];
      $presenter_point = sprintf("%.3f", $season_result[$j]['final_point']);
      $season_output .= "<div class='mini_content'>
                  <div class='mini_content_1'>{$presenter_rank}位</div>
                  <div class='mini_content_2'>{$presenter_point}</div>
                  <div class='mini_content_3'>{$presenter_name}</div>
                  <div class='mini_content_4'>{$presenter_class}</div>
                </div>";
    }

    $season_output .= " </div></div>";
  }
}
// -----------------------------------------------------------------------------------------------------------




// 本日のランキング生成 -------------------------------------------------------------------------------------------
$today = date("Y-m-d");

$sql = 'SELECT * FROM season_table WHERE season_date = :season_date;';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':season_date', $today, PDO::PARAM_STR);
$status = $stmt->execute();
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if ($event) {
  $sql = 'SELECT * FROM result_table WHERE season_id = :season_id ORDER BY ranking ASC';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':season_id', $event['season_id'], PDO::PARAM_INT);
  $status = $stmt->execute();
  $season_result = $stmt->fetchALL(PDO::FETCH_ASSOC);

  if ($season_result != NULL) {
    $today_output = "<div class='content_index content_index_1'>
                   </div>
                   <div class='content_index content_index_2'>
                     <p>評価点</p>
                   </div>
                   <div class='content_index content_index_3'>
                     <p>登壇者</p>
                   </div>
                </div>
                ";
    for ($i = 0; $i < count($season_result); $i++) {
      $presenter_id = $season_result[$i]['presenter_id'];
      $season_id = $season_result[$i]['season_id'];
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
        $today_output .= "<div class='row'>
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
        $today_output .= "<div class='row border'>
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
  } else {
    $today_output .= "<div class='no_event'>
                      <div class='message'>
                        <p>結果発表をお待ちください</p>
                      </div>
                    </div>
                  ";
  }
} else {
  $today_output .= "<div class='no_event'>
                      <div class='message'>
                        <p>本日の開催はありません</p>
                      </div>
                    </div>
                  ";
}
// -----------------------------------------------------------------------------------------------------------

$button = "";
if ($_SESSION['password'] == NULL) {
  $button .= "<a href='menu.php'>MENU</a>";
} else {
  if ($season_id != "-") {
    $button .= "<a href='admin/result_fin.php?season_id={$_GET['season_id']}'>Fin</a>";
  } else {
    $button .= "<a href='admin/admin_menu.php'>admin_menu</a>";
  }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/ranking.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <title>総合ランキング</title>
</head>

<body>
  <main>

    <div class="main_title">
      <h1>評価</h1>
      <h2>開催時間外、もしくは登壇者の一部をSKIPされた審査員の評価点は反映されません。</h2>
    </div>

    <div class="select_wrapper">
      <div class="select_container">
        <button class="tab_item tab_item_1" id="total_rank">総合</button>
        <button class="tab_item tab_item_2" id="season_rank">シーズン</button>
        <button class="tab_item tab_item_3" id="today_rank">本日</button>
      </div>
      <div class="title_wrapper">
        <?= $button ?>
      </div>
    </div>

    <div class="total_rank">
      <div class="index">
        <div class="content_index content_index_1">
        </div>
        <div class="content_index content_index_2">
          <p>シーズン</p>
        </div>
        <div class="content_index content_index_3">
          <p>テーマ</p>
        </div>
        <div class="content_index content_index_4">
          <p>評価点</p>
        </div>
        <div class="content_index content_index_5">
          <p>登壇者</p>
        </div>
        <div class="content_index content_index_6">
          <p>クラス</p>
        </div>
      </div>
      <?= $total_output ?>
    </div>

    <div class="season_rank">
      <div class="index">
        <div class="content_0">シーズン</div>
        <div class="content_1">テーマ</div>
        <div class="content_2">
          <div class='mini_content'>
            <div class='mini_content_1'></div>
            <div class='mini_content_2'>評価点</div>
            <div class='mini_content_3'>登壇者</div>
            <div class='mini_content_4'>クラス</div>
          </div>
        </div>
      </div>
      <?= $season_output ?>
    </div>

    <div class="today_rank">
      <div class="index">
        <?= $today_output ?>
      </div>

  </main>


  <script>
    $('#total_rank').on('click', function() {
      $('.total_rank').css('display', 'block');
      $('.season_rank').css('display', 'none');
      $('.today_rank').css('display', 'none');
      $('.tab_item_1').css('background-color', '#00A7EA');
      $('.tab_item_1').css('color', '#fff');
      $('.tab_item_2').css('background-color', '#d9d9d9');
      $('.tab_item_2').css('color', '#565656');
      $('.tab_item_3').css('background-color', '#d9d9d9');
      $('.tab_item_3').css('color', '#565656');
    });
    $('#season_rank').on('click', function() {
      $('.total_rank').css('display', 'none');
      $('.season_rank').css('display', 'block');
      $('.today_rank').css('display', 'none');
      $('.tab_item_2').css('background-color', '#00A7EA');
      $('.tab_item_2').css('color', '#fff');
      $('.tab_item_1').css('background-color', '#d9d9d9');
      $('.tab_item_1').css('color', '#565656');
      $('.tab_item_3').css('background-color', '#d9d9d9');
      $('.tab_item_3').css('color', '#565656');
    });
    $('#today_rank').on('click', function() {
      $('.total_rank').css('display', 'none');
      $('.season_rank').css('display', 'none');
      $('.today_rank').css('display', 'block');
      $('.tab_item_3').css('background-color', '#00A7EA');
      $('.tab_item_3').css('color', '#fff');
      $('.tab_item_2').css('background-color', '#d9d9d9');
      $('.tab_item_2').css('color', '#565656');
      $('.tab_item_1').css('background-color', '#d9d9d9');
      $('.tab_item_1').css('color', '#565656');
    });
  </script>

</body>

</html>
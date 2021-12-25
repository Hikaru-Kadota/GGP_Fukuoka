<?php
session_start();
include("functions.php");
$pdo = connect_to_db();

$sql = 'SELECT season_id FROM result_table;';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();
$finished_season = $stmt->fetchALL(PDO::FETCH_ASSOC);

$finished_season_id = [];
for ($i = 0; $i < count($finished_season); $i++) {
  array_push($finished_season_id, $finished_season[$i]['season_id']);
}
$finished_season_id = array_unique($finished_season_id);

$sql = 'SELECT * FROM season_table ORDER BY season_date ASC;';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();
$seasons = $stmt->fetchALL(PDO::FETCH_ASSOC);

$finished_season_detail = [];
for ($i = 0; $i < count($seasons); $i++) {
  if (in_array($seasons[$i]['season_id'], $finished_season_id)) {
    array_push($finished_season_detail, $seasons[$i]);
  }
}

$season_output = "<option value='-'>選択してください</option>";
for ($i = 0; $i < count($finished_season_detail); $i++) {
  $season_output .= "<option value='{$finished_season_detail[$i]['season_id']}'>{$finished_season_detail[$i]['season_name']} ({$finished_season_detail[$i]['season_date']})</option>";
}

$sql = 'SELECT * FROM item_table';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();
$item = $stmt->fetchALL(PDO::FETCH_ASSOC);

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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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

    <div class="select_wrapper">
      <h4>シーズンを選択</h4>
      <select name="season_id" id="season_search">
        <?= $season_output ?>
      </select>
    </div>

    <div id="presenter" class="select_wrapper">
      <h4>登壇者を選択</h4>
      <select name="presenter" id="presenter_search">
      </select>
    </div>

    <div class="season_theme" id="season_detail">
      <h4>テーマ</h4>
      <div id="season_name">
      </div>
    </div>

    <div class="evaluation_table" id="index">
      <div class="index">
        <div class="content_index content_index_3">
          <p>声の大きさ<br>トーン</p>
        </div>
        <div class="content_index content_index_4">
          <p>表情</p>
        </div>
        <div class="content_index content_index_5">
          <p>情熱・熱量</p>
        </div>
        <div class="content_index content_index_6">
          <p>スライド構成<br>デザイン</p>
        </div>
        <div class="content_index content_index_7">
          <p>PC操作<br>立ち回り</p>
        </div>
        <div class="content_index content_index_8">
          <p>評価点</p>
        </div>
        <div class="content_index content_index_9">
          <p>コメント</p>
        </div>
      </div>

      <div id='evaluation'>
      </div>

    </div>
  </div>


  <script>
    $('#season_search').change('keyup', function(e) {
      const season_id = e.target.value;
      if (season_id != "-") {
        $('#presenter').css('display', 'flex');
        $('#index').css('display', 'none');
        $('#evaluation').css('display', 'none');
        $('#season_detail').css('display', 'flex');
      } else {
        $('#presenter').css('display', 'none');
        $('#index').css('display', 'none');
        $('#evaluation').css('display', 'none');
        $('#season_detail').css('display', 'none');
      }

      const requestUrl_1 = 'season_get.php';
      axios.get(`${requestUrl_1}?season_id=${season_id}`).then(function(response) {
        const presenter_arr = [`<option value='-'>選択して下さい</option>`];
        for (let i = 0; i < response.data.length; ++i) {
          presenter_arr.push(`<option value='${response.data[i]['presenter_id']}'>${response.data[i]['presenter_name']}</option>`)
        }
        $('#presenter_search').html(presenter_arr);
      });

      const requestUrl_2 = 'season_detail_get.php';
      axios.get(`${requestUrl_2}?season_id=${season_id}`).then(function(response_2) {
        const season_theme = [`『 ${response_2.data['season_theme']} 』`];
        $('#season_name').html(season_theme);
      });


      $('#presenter_search').change('keyup', function(e) {
        const presenter_id = e.target.value;
        if (presenter_id != "-") {
          $('#index').css('display', 'block');
          $('#evaluation').css('display', 'block');
        } else {
          $('#index').css('display', 'none');
          $('#evaluation').css('display', 'none');
        }

        const requestUrl_3 = 'presenter_get.php';
        axios.get(`${requestUrl_3}?presenter_id=${presenter_id}`).then(function(response_3) {
          const evaluation_arr = [];
          for (let i = 0; i < response_3.data.length; ++i) {
            let judge_point = (Number(response_3.data[i]['item_1']) + Number(response_3.data[i]['item_2']) + Number(response_3.data[i]['item_3']) + Number(response_3.data[i]['item_4']) + Number(response_3.data[i]['item_5'])) / 5;
            if (i % 2 == 0) {
              evaluation_arr.push(`<div id='evaluation'>
                                  <div class='row border'>
                                    <div class='row_3'>
                                      <p>${response_3.data[i]['item_1']}</p>
                                    </div>
                                    <div class='row_4'>
                                      <p>${response_3.data[i]['item_2']}</p>
                                    </div>
                                    <div class='row_5'>
                                      <p>${response_3.data[i]['item_3']}</p>
                                    </div>
                                    <div class='row_6'>
                                      <p>${response_3.data[i]['item_4']}</p>
                                    </div>
                                    <div class='row_7'>
                                      <p>${response_3.data[i]['item_5']}</p>
                                    </div>
                                    <div class='row_8'>
                                      <p>${judge_point.toFixed(3)}</p>
                                    </div>
                                    <div class='row_9'>
                                      <p>${response_3.data[i]['comment']}</p>
                                    </div>
                                  </div>
                                </div>`);
            } else {
              evaluation_arr.push(`<div id='evaluation'>
                                  <div class='row'>
                                    <div class='row_3'>
                                      <p>${response_3.data[i]['item_1']}</p>
                                    </div>
                                    <div class='row_4'>
                                      <p>${response_3.data[i]['item_2']}</p>
                                    </div>
                                    <div class='row_5'>
                                      <p>${response_3.data[i]['item_3']}</p>
                                    </div>
                                    <div class='row_6'>
                                      <p>${response_3.data[i]['item_4']}</p>
                                    </div>
                                    <div class='row_7'>
                                      <p>${response_3.data[i]['item_5']}</p>
                                    </div>
                                    <div class='row_8'>
                                      <p>${judge_point.toFixed(3)}</p>
                                    </div>
                                    <div class='row_9'>
                                      <p>${response_3.data[i]['comment']}</p>
                                    </div>
                                  </div>
                                </div>`);
            };
          };
          $('#evaluation').html(evaluation_arr);
        });
      });

    });
  </script>
</body>

</html>
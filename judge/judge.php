<?php
session_start();
include("../functions.php");
$pdo = connect_to_db();

if (!$_SESSION['judge_id']) {
  $season_id = $_POST['season_id'];
  $judge_name = $_POST['judge_name'];

  $sql = 'INSERT INTO judge_table (judge_id, season_id, judge_name, judge_time, created_at) VALUES(NULL, :season_id, :judge_name, 0, sysdate())';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
  $stmt->bindValue(':judge_name', $judge_name, PDO::PARAM_STR);
  $status = $stmt->execute();

  if ($status) {
    $sql = 'SELECT * FROM judge_table WHERE judge_name = :judge_name ORDER BY created_at DESC LIMIT 1';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':judge_name', $judge_name, PDO::PARAM_STR);
    $status = $stmt->execute();

    if ($status) {
      $own = $stmt->fetch(PDO::FETCH_ASSOC);
      $_SESSION = array();
      $_SESSION["session_id"] = session_id();
      $_SESSION["judge_id"] = $own["judge_id"];
      $_SESSION["season_id"] = $own["season_id"];
      $_SESSION["judge_name"] = $own["judge_name"];
    } else {
      header("Location:index.html");
    }
  } else {
    header("Location:index.html");
  }
}

check_session_id();
$judge_id = $_SESSION['judge_id'];
$season_id = $_SESSION['season_id'];

$sql = 'SELECT * FROM item_table';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();
$items = $stmt->fetchALL(PDO::FETCH_ASSOC);

$sql = 'SELECT * FROM relation_table WHERE season_id = :season_id && judge_id = :judge_id ORDER BY presenter_id ASC';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
$stmt->bindValue(':judge_id', $judge_id, PDO::PARAM_INT);
$status = $stmt->execute();
$judged_presenter = $stmt->fetchALL(PDO::FETCH_ASSOC);


$sql = 'SELECT * FROM presenter_table WHERE season_id = :season_id ORDER BY presenter_id ASC';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
$status = $stmt->execute();
$presenters = $stmt->fetchALL(PDO::FETCH_ASSOC);


$unfinished_presenter = [];
for ($i = 0; $i < count($presenters); $i++) {
  $presenter_id = $presenters[$i]['presenter_id'];
  $sql = 'SELECT * FROM relation_table WHERE season_id = :season_id AND presenter_id = :presenter_id AND judge_id = :judge_id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
  $stmt->bindValue(':presenter_id', $presenter_id, PDO::PARAM_INT);
  $stmt->bindValue(':judge_id', $judge_id, PDO::PARAM_INT);
  $status = $stmt->execute();
  $judged_presenter = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($judged_presenter == false) {
    array_push($unfinished_presenter, $presenter_id);
  }
}

$sql = 'SELECT * FROM presenter_table WHERE season_id = :season_id AND presenter_id = :presenter_id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
$stmt->bindValue(':presenter_id', $unfinished_presenter[0], PDO::PARAM_INT);
$status = $stmt->execute();
$presenter = $stmt->fetch(PDO::FETCH_ASSOC);

if ($presenter == false) {
  header("Location:judge_finish.php");
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/judge.css">
  <title>審査フォーム</title>
</head>

<body>
  <main>

    <div class="presenter_name">
      <h3>登壇者<span></span><?= $presenter['presenter_name'] ?></h3>
    </div>

    <form action="judge_create.php" method="POST" name="judge_form">
      <div class="judge_table">
        <div class="cols">
          <div class="content_index">
            <h3>評価</h3>
            <h4>/得点</h4>
          </div>
          <div class="content">
            <h3>S</h3>
            <h4>/1</h4>
          </div>
          <div class="content">
            <h3>A</h3>
            <h4>/2</h4>
          </div>
          <div class="content">
            <h3>B</h3>
            <h4>/3</h4>
          </div>
          <div class="content">
            <h3>C</h3>
            <h4>/4</h4>
          </div>
          <div class="content">
            <h3>D</h3>
            <h4>/5</h4>
          </div>
        </div>

        <div class="rows border">
          <div class="content_index">
            <p><?= $items[0]['item_name'] ?></p>
          </div>
          <div class="content">
            <input type="radio" name="item_1" value="1" style="transform:scale(1.5);">
          </div>
          <div class="content">
            <input type="radio" name="item_1" value="2" style="transform:scale(1.5);">
          </div>
          <div class="content">
            <input type="radio" name="item_1" value="3" style="transform:scale(1.5);">
          </div>
          <div class="content">
            <input type="radio" name="item_1" value="4" style="transform:scale(1.5);">
          </div>
          <div class="content">
            <input type="radio" name="item_1" value="5" style="transform:scale(1.5);">
          </div>
        </div>

        <div class="rows">
          <div class="content_index">
            <p><?= $items[1]['item_name'] ?></p>
          </div>
          <div class="content">
            <input type="radio" name="item_2" value="1" style="transform:scale(1.5);">
          </div>
          <div class="content">
            <input type="radio" name="item_2" value="2" style="transform:scale(1.5);">
          </div>
          <div class="content">
            <input type="radio" name="item_2" value="3" style="transform:scale(1.5);">
          </div>
          <div class="content">
            <input type="radio" name="item_2" value="4" style="transform:scale(1.5);">
          </div>
          <div class="content">
            <input type="radio" name="item_2" value="5" style="transform:scale(1.5);">
          </div>
        </div>

        <div class="rows border">
          <div class="content_index">
            <p><?= $items[2]['item_name'] ?></p>
          </div>
          <div class="content">
            <input type="radio" name="item_3" value="1" style="transform:scale(1.5);">
          </div>
          <div class="content">
            <input type="radio" name="item_3" value="2" style="transform:scale(1.5);">
          </div>
          <div class="content">
            <input type="radio" name="item_3" value="3" style="transform:scale(1.5);">
          </div>
          <div class="content">
            <input type="radio" name="item_3" value="4" style="transform:scale(1.5);">
          </div>
          <div class="content">
            <input type="radio" name="item_3" value="5" style="transform:scale(1.5);">
          </div>
        </div>

        <div class="rows">
          <div class="content_index">
            <p><?= $items[3]['item_name'] ?></p>
          </div>
          <div class="content">
            <input type="radio" name="item_4" value="1" style="transform:scale(1.5);">
          </div>
          <div class="content">
            <input type="radio" name="item_4" value="2" style="transform:scale(1.5);">
          </div>
          <div class="content">
            <input type="radio" name="item_4" value="3" style="transform:scale(1.5);">
          </div>
          <div class="content">
            <input type="radio" name="item_4" value="4" style="transform:scale(1.5);">
          </div>
          <div class="content">
            <input type="radio" name="item_4" value="5" style="transform:scale(1.5);">
          </div>
        </div>

        <div class="rows border">
          <div class="content_index">
            <p><?= $items[4]['item_name'] ?></p>
          </div>
          <div class="content">
            <input type="radio" name="item_5" value="1" style="transform:scale(1.5);">
          </div>
          <div class="content">
            <input type="radio" name="item_5" value="2" style="transform:scale(1.5);">
          </div>
          <div class="content">
            <input type="radio" name="item_5" value="3" style="transform:scale(1.5);">
          </div>
          <div class="content">
            <input type="radio" name="item_5" value="4" style="transform:scale(1.5);">
          </div>
          <div class="content">
            <input type="radio" name="item_5" value="5" style="transform:scale(1.5);">
          </div>
        </div>
        <input type="hidden" name="presenter_id" value="<?= $presenter['presenter_id'] ?>">

      </div>

      <div class="comment">
        <p>登壇者へコメント</p>
        <textarea name="comment" id="" cols="100" rows="3" maxlength="500" placeholder="２００文字以内" onkeyup="ShowLength(value);"></textarea>
        <p id="inputlength"></p>
      </div>

      <div class="button_container">
        <input class="skip" type="submit" value="SKIP" name="judge_point" onClick="return check_SKIP();">
        <input type="submit" value="DONE" name="judge_point" onClick="return check_DONE();">
      </div>
    </form>
  </main>


  <script type="text/javascript">
    function check_DONE() {
      if (judge_form.item_1.value == "" ||
        judge_form.item_2.value == "" ||
        judge_form.item_3.value == "" ||
        judge_form.item_4.value == "" ||
        judge_form.item_5.value == "") {
        alert("コメント以外は必須です");
        return false;
      } else {
        return true;
      }
    }

    function ShowLength(str) {
      document.getElementById("inputlength").innerHTML = "残り " + (500 - str.length) + "文字";
    }
  </script>
</body>

</html>
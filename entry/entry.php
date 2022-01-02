<?php
session_start();
include("../functions.php");
$pdo = connect_to_db();

$season_id = $_POST['season_id'];
$class = $_POST['class'];
$entry_name = $_POST['entry_name'];

$sql = 'SELECT * FROM presenter_table WHERE season_id = :season_id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':season_id', $event[$i]['season_id'], PDO::PARAM_INT);
$status = $stmt->execute();
$presenter_count = $stmt->fetchALL(PDO::FETCH_ASSOC);
if (count($presenter_count) < 5) {
  $sql = 'INSERT INTO presenter_table (presenter_id, season_id, presenter_name, class) VALUES (NULL, :season_id, :presenter_name, :class)';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
  $stmt->bindValue(':presenter_name', $entry_name, PDO::PARAM_STR);
  $stmt->bindValue(':class', $class, PDO::PARAM_STR);
  $status = $stmt->execute();
  if ($status == false) {
    header("Location:../menu.php");
  }
} else {
  header("Location:../menu.php");
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/judge_finish.css">
  <title>受付完了</title>
</head>

<body>
  <main>
    <div class="title_wrapper">
      <h3>エントリーを受け付けました</h1>
        <h4>楽しみにお待ちしております</h4>
        <a href="../all_ggp.php">OK</a>
    </div>

  </main>
</body>

</html>
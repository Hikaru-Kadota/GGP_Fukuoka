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
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/top.css">
  <title>TOP</title>
</head>

<body>
  <main>
    <div class="result_fin_wrapper">
      <h2>お疲れ様でした！</h2>
      <h4>GGP season <?= $season['season_name'] ?></h4>
      <a class="fin_button" href="admin_menu.php">バイバイ</a>
    </div>
  </main>
</body>

</html>
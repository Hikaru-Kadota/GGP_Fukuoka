<?php
include("functions.php");
$pdo = connect_to_db();

$season_id = $_GET['season_id'];
$sql = 'SELECT * FROM season_table WHERE season_id = :season_id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
$status = $stmt->execute();
$season = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($season);
exit();

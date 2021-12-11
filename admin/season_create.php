<?php
session_start();
include("../functions.php");
$pdo = connect_to_db();
check_session_id();

$season_name = $_POST['season_name'];
$season_theme = $_POST['season_theme'];
$season_date = $_POST['season_date'];


$sql = 'INSERT INTO season_table (season_id, season_name, season_theme, season_date) VALUES (NULL, :season_name, :season_theme, :season_date)';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':season_name', $season_name, PDO::PARAM_STR);
$stmt->bindValue(':season_theme', $season_theme, PDO::PARAM_STR);
$stmt->bindValue(':season_date', $season_date, PDO::PARAM_STR);
$status = $stmt->execute();
if ($status == true) {
  header("Location:../all_ggp.php");
} else {
  header("Location:admin_menu.php");
}

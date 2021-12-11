<?php
session_start();
include("../functions.php");
$pdo = connect_to_db();
check_session_id();

$season_id = $_SESSION['season_id'];
$judge_id = $_SESSION['judge_id'];
$judge_point = $_POST['judge_point'];

$presenter_id = $_POST['presenter_id'];
$item_1 = $_POST['item_1'];
$item_2 = $_POST['item_2'];
$item_3 = $_POST['item_3'];
$item_4 = $_POST['item_4'];
$item_5 = $_POST['item_5'];
$comment = $_POST['comment'];



if ($judge_point == "DONE") {
  $sql = 'INSERT INTO judgement_table (judgement_id, season_id, presenter_id, judge_id, item_1, item_2, item_3, item_4, item_5, comment) VALUES (NULL, :season_id, :presenter_id, :judge_id, :item_1, :item_2, :item_3, :item_4, :item_5, :comment)';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
  $stmt->bindValue(':presenter_id', $presenter_id, PDO::PARAM_INT);
  $stmt->bindValue(':judge_id', $judge_id, PDO::PARAM_INT);
  $stmt->bindValue(':item_1', $item_1, PDO::PARAM_INT);
  $stmt->bindValue(':item_2', $item_2, PDO::PARAM_INT);
  $stmt->bindValue(':item_3', $item_3, PDO::PARAM_INT);
  $stmt->bindValue(':item_4', $item_4, PDO::PARAM_INT);
  $stmt->bindValue(':item_5', $item_5, PDO::PARAM_INT);
  $stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
  $status = $stmt->execute();
  if ($status == false) {
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
  } else {
    $judge_point = floatval(($item_1 + $item_2 + $item_3 + $item_4 + $item_5) / 5 . "n");
    $sql = 'INSERT INTO relation_table (relation_id, season_id, presenter_id, judge_id, judge_point) VALUES (NULL, :season_id, :presenter_id, :judge_id, :judge_point)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
    $stmt->bindValue(':presenter_id', $presenter_id, PDO::PARAM_INT);
    $stmt->bindValue(':judge_id', $judge_id, PDO::PARAM_INT);
    $stmt->bindValue(':judge_point', $judge_point, PDO::PARAM_STR);
    $status = $stmt->execute();
    if ($status == false) {
      $error = $stmt->errorInfo();
      echo json_encode(["error_msg" => "{$error[2]}"]);
      exit();
    } else {
      header("Location:judge.php");
    }
  }
} else {
  $judge_point = 0;
  $sql = 'INSERT INTO relation_table (relation_id, season_id, presenter_id, judge_id, judge_point) VALUES (NULL, :season_id, :presenter_id, :judge_id, :judge_point)';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':season_id', $season_id, PDO::PARAM_INT);
  $stmt->bindValue(':presenter_id', $presenter_id, PDO::PARAM_INT);
  $stmt->bindValue(':judge_id', $judge_id, PDO::PARAM_INT);
  $stmt->bindValue(':judge_point', $judge_point, PDO::PARAM_STR);
  $status = $stmt->execute();
  if ($status == false) {
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
  } else {
    header("Location:judge.php");
  }
}

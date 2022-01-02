<?php
include("../functions.php");
$pdo = connect_to_db();

$presenter_id = $_GET['presenter_id'];



$sql = 'DELETE FROM presenter_table WHERE presenter_id = :presenter_id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':presenter_id', $presenter_id, PDO::PARAM_INT);
$status = $stmt->execute();

header("Location:../all_ggp.php");

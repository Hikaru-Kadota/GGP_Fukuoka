<?php
include("functions.php");
$pdo = connect_to_db();

$presenter_id = $_GET['presenter_id'];
$sql = 'SELECT * FROM judgement_table WHERE presenter_id = :presenter_id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':presenter_id', $presenter_id, PDO::PARAM_INT);
$status = $stmt->execute();
$evaluation = $stmt->fetchALL(PDO::FETCH_ASSOC);
echo json_encode($evaluation);
exit();

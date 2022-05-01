<?php

function connect_to_db()
{
  $dbn = 'mysql:dbname=heroku_f85df5dc0c3e7ac;charset=utf8mb4;port=3306;host=us-cdbr-east-05.cleardb.net';
  $user = 'b4451c9755181a';
  $pwd = '7f644c43';


  // $dbn = 'mysql:dbname=GGP;charset=utf8;port=3306;host=localhost';
  // $user = 'root';
  // $pwd = '';

  try {
    return new PDO($dbn, $user, $pwd);
  } catch (PDOException $e) {
    echo json_encode(["db error" => "{$e->getMessage()}"]);
    exit();
  }
}

function check_session_id()
{
  if (
    !isset($_SESSION["session_id"]) ||
    $_SESSION["session_id"] != session_id()
  ) {
    header("Location:index.html");
  } else {
    session_regenerate_id(true);
    $_SESSION["session_id"] = session_id();
  }
}

<?php // all functions go here
// $test = "functions.php is patching correctly";

// required on any page database is queried
function connectToDB() {
  $host = "devkinsta_db";
  $database_name = "PHP_Project";
  $database_user = "root";
  $database_password = "in4VkcqsYWTIdopj";

  $database = new PDO (
    "mysql:host=$host;dbname=$database_name",
    $database_user,
    $database_password
  );
  return $database;
}
   
// redirect
function redirect($page) {
  header("Location: " . $page);
  exit;
}
// error message
function setError($message, $page) {
  $_SESSION["error"] = $message;
  // redirect
  redirect($page);
}
// success message
function setSuccess($message, $page) {
  $_SESSION["success"] = $message;
  // redirect
  redirect($page);
}

// RBAC
// check if logged in
function isLoggedIn() {
  return isset($_SESSION["user"]);
}
// check if admin 
function isAdmin() {
  return isLoggedIn() && $_SESSION["user"]["role"] === "admin";
}
// check if moderator
function isModerator() {
  return isLoggedIn() && $_SESSION["user"]["role"] === "moderator";
}
// check if user
function isUser() {
  return isLoggedIn() && $_SESSION["user"]["role"] === "user";
}

// word limit
function limitCharCount($text, $charLimit) {
  if (strlen($text) > $charLimit) {
    echo substr($text, 0 , $charLimit) . "...";
  } else {
    echo $text;
  }
}
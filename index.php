<?php
// var_dump($_SERVER["DOCUMENT_ROOT"]); // /www/kinsta/public/php-project

require "actions/functions.php";
// echo $test; // sanity check
require "actions/class_auth.php";
require "actions/class_user.php";
require "actions/class_post.php";
require "actions/class_comment.php";
require "actions/class_bookmark.php";

$auth = new Authentication();
$user = new User();
$post = new Post();
$comment = new Comment();
$bookmark = new Bookmark();

// required on any page $_SESSION global variable is used
session_start();
// // current login credentials
// if (isLoggedIn()) {
//   print_r($_SESSION["user"]);
// }

// Uniform Resource Identifier requested by prior page i.e. header("Location: X");
$path = $_SERVER["REQUEST_URI"];
// var_dump($path); // current route

// remove GET query from URL for routing purposes
$path = parse_url($path, PHP_URL_PATH);

// remove starting "/" for $path values below
$path = trim($path, "/");

switch ($path) {
  // actions
  case "signup_action":
    $auth -> signup();
    break;
  case "login_action":
    $auth -> login();
    break;
  case "logout_action":
    $auth -> logout();
    break;
  case "adduser_action":
    $user -> add();
    break;
  case "edituser_action":
    $user -> edit();
    break;
  case "changepassword_action":
    $user -> password();
    break;
  case "deleteuser_action":
    $user -> delete();
    break;
  case "settings_action":
    $user -> settings_edit();
    break;
  case "settings_password_action":
    $user -> settings_password();
    break;
  case "addpost_action":
    $post -> add();
    break;
  case "editpost_action": 
    $post -> edit();
    break;
  case "deletepost_action":
    $post -> delete();
    break;
  case "addcomment_action":
    $comment -> add();
    break;
  case "editcomment_action":
    $comment -> edit();
    break;
  case "deletecomment_action":
    $comment -> delete();
    break;
  case "postbookmark_action":
    $bookmark -> post();
    break;
  case "deletebookmark_action":
    $bookmark -> delete();
    break;

  // pages
  case "signup":
    $subpage_title = "Sign Up";
    require "pages/auth/signup.php";
    break;
  case "login":
    $subpage_title = "Login";
    require "pages/auth/login.php";
    break;
  case "dashboard":
    $subpage_title = "Dashboard";
    require "pages/dashboard.php";
    break;
  case "manageusers":
    $subpage_title = "Manage Users";
    require "pages/user/manage.php";
    break;
  case "adduser":
    $subpage_title = "Add User";
    require "pages/user/add.php";
    break;
  case "edituser":
    $subpage_title = "Edit User";
    require "pages/user/edit.php";
    break;
  case "changepassword":
    $subpage_title = "Change User Password";
    require "pages/user/password.php";
    break;
  case "settings":
    $subpage_title = "User Settings";
    require "pages/user/settings_edit.php";
    break;
  case "settings_password":
    $subpage_title = "Change Password";
    require "pages/user/settings_password.php";
    break;
  case "manageposts":
    $subpage_title = "Manage Posts";
    require "pages/post/manage.php";
    break;
  case "createpost":
    $subpage_title = "Create Post";
    require "pages/post/add.php";
    break;  
  case "editpost":
    $subpage_title = "Edit Post";
    require "pages/post/edit.php";
    break;
  case "managecomments":
    $subpage_title = "Manage Comments";
    require "pages/comment/manage.php";
    break;
  case "editcomment":
    $subpage_title = "Edit Comment";
    require "pages/comment/edit.php";
    break;
  case "bookmarks":
    $subpage_title = "Bookmarks";
    require "pages/bookmarks.php";
    break;
  case "post":
    // $subpage_title = "Post";
    require "pages/post.php";
    break;
  default:
    $subpage_title = "Home";
    require "pages/home.php";
    break;
}
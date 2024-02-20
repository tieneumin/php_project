<?php
// by convention, classes start uppercased
class Authentication
{
    function signup()
    {
        isLoggedIn()? redirect("/"): "";

        // connect to database
        $database = connectToDB();

        // capture data from POST
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];
        
        // error-handling
        // ensure all fields filled
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            setError("All fields are required.", "/signup");
        }
        // ensure password >=8 characters 
        if (strlen($password ) < 8) {
            setError("Password must be at least 8 characters.", "/signup");
        }
        // ensure passwords match
        if ($password !== $confirm_password) {
            setError("Passwords do not match.", "/signup");
        } 
        // ensure username not in use
        $sql = "SELECT * FROM users WHERE username = :username";
        $query = $database -> prepare($sql);
        $query -> execute(["username" => $username]);
        $user = $query -> fetch();
        if (!empty($user)) {
            setError("Username is already in use.", "/signup");
        }
        // ensure email not in use
        $sql = "SELECT * FROM users WHERE email = :email";
        $query = $database -> prepare($sql);
        $query -> execute(["email" => $email]);
        $user = $query -> fetch();
        if (!empty($user)) {
            setError("Email is already in use.", "/signup");
        }
        
        // create account
        $sql = "INSERT INTO users (`username`,`email`,`password`) VALUES (:username, :email, :password)";
        $query = $database -> prepare($sql);
        $query -> execute([
            "username" => $username,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ]);
        // confirm account creation and redirect
        setSuccess("Account successfully created.", "/login");
    }

    function login()
    {
        isLoggedIn()? redirect("/"): "";

        // connect to database
        $database = connectToDB();

        // capture data from POST
        $email = $_POST["email"];
        $password = $_POST["password"];

        // error-checking
        // ensure all fields filled
        if (empty($email) || empty($password)) {
            setError("All fields are required.", "/login");
        }
        // ensure user exists
        // retrieve user with email via placeholder query (prevents SQL injection)
        $sql = "SELECT * FROM users WHERE email = :email";
        $query = $database -> prepare($sql);
        $query -> execute (["email" => $email]);
        $user = $query -> fetch(); // just 1 row
        // var_dump($user); // false if not found i.e. "" hence empty() and not !isset()
        if (empty($user)) {
            setError("Account does not exist.", "/login");
        }
        // ensure correct password
        if (!password_verify($password, $user["password"])) {
            setError("Incorrect password.", "/login");
        }
        
        // log user in and redirect
        $_SESSION["user"] = $user;
        redirect("/");
    }

    function logout()
    {
        // log user out and redirect
        unset($_SESSION["user"]);
        redirect("/login");
    }
}
<?php
// by convention, classes start uppercased
class User
{
    function add()
    {
        !isAdmin()? redirect("/login"): "";

        // connect to database
        $database = connectToDB();

        // capture data from POST
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];
        $role = $_POST["role"];

        // error-handling
        // ensure all fields filled
        if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
            setError("All fields are required.", "/adduser");
        }
        // ensure username not in use
        $sql = "SELECT * FROM users WHERE username = :username";
        $query = $database -> prepare($sql);
        $query -> execute(["username" => $username]);
        $user = $query -> fetch();
        if (!empty($user)) {
            setError("Username is already in use.", "/adduser");
        }
        // ensure email not in use
        $sql = "SELECT * FROM users WHERE email = :email";
        $query = $database -> prepare($sql);
        $query -> execute(["email" => $email]);
        $user = $query -> fetch();
        if (!empty($user)) {
            setError("Email is already in use.", "/adduser");
        }
        // ensure password >= 8 characters 
        if (strlen($password) < 8) {
            setError("Password must be at least 8 characters.", "/adduser");
        }
        // ensure passwords match
        if ($password !== $confirm_password) {
            setError("Passwords do not match.", "/adduser");
        }
 
        // add user
        $sql = "INSERT INTO users (`username`,`email`,`password`,`role`) VALUES (:username, :email, :password, :role)";
        $query = $database -> prepare($sql);
        $query -> execute([
            "username" => $username,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "role" => $role
        ]);

        // confirm and redirect
        setSuccess("User added.", "/manageusers");
    }

    function edit()
    {
        !isAdmin()? redirect("/login"): "";

        // connect to database
        $database = connectToDB();

        // capture data from POST
        $username = $_POST["username"];
        $email = $_POST["email"];
        $role = $_POST["role"];
        $id = $_POST["id"];

        // error-handling
        // ensure all fields filled
        if (empty($username) || empty($email)) {
            setError("All fields are required.", "/edituser?id=".$id);
        }
        // ensure username not in use w/o checking current user; prevents "username in use" error if username not edited
        $sql = "SELECT * FROM users WHERE username = :username AND id != :id";
        $query = $database -> prepare($sql);
        $query -> execute([
            "username" => $username,
            "id" => $id
        ]);
        $user = $query -> fetch();
        if (!empty($user)) {
            setError("Username is already in use.", "/edituser?id=".$id);
        }
        // ensure email not in use w/o checking current user; prevents "email in use" error if email not edited
        $sql = "SELECT * FROM users WHERE email = :email AND id != :id";
        $query = $database -> prepare($sql);
        $query -> execute([
            "email" => $email,
            "id" => $id
        ]);
        $user = $query -> fetch();
        if (!empty($user)) {
            setError("Email is already in use.", "/edituser?id=".$id);
        }

        // update user
        $sql = "UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id";
        $query = $database -> prepare($sql);
        $query -> execute([
            "username" => $username,
            "email" => $email,
            "role" => $role,
            "id" => $id
        ]);

        // confirm and redirect
        setSuccess("User updated.", "/manageusers");
    }

    function password()
    {
        !isAdmin()? redirect("/login"): "";

        // connect to database
        $database = connectToDB();

        // capture data from POST
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];
        $id = $_POST["id"];

        // error-handling
        // ensure all fields filled
        if (empty($password) || empty($confirm_password)) {
            setError("All fields are required.", "/changepassword?id=".$id);
        }
        // ensure password >= 8 characters 
        if (strlen($password) < 8) {
            setError("Password must be at least 8 characters.", "/changepassword?id=".$id);
        }
        // ensure passwords match
        if ($password !== $confirm_password) {
            setError("Passwords do not match.", "/changepassword?id=".$id);
        }
        // ensure same password not used
        $sql = "SELECT * FROM users WHERE id = :id"; 
        $query = $database -> prepare($sql);
        $query -> execute(["id" => $id]);
        $user = $query -> fetch();
        if (password_verify($password, $user["password"])) {
            setError("New password should not be the same as user's current password.", "/changepassword?id=".$id);
        }

        // update password
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $query = $database -> prepare($sql);
        $query -> execute([
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "id" => $id
        ]);

        // confirm and redirect
        setSuccess("Password changed.", "/manageusers");
    }

    function delete() 
    {
        !isAdmin()? redirect("/login"): "";

        // connect to database
        $database = connectToDB();

        // capture id from POST
        $id = $_POST["id"];

        // delete user
        $sql = "DELETE FROM users where id = :id";
        $query = $database -> prepare($sql);
        $query -> execute(["id" => $id]);
        // confirm deletion and redirect
        setSuccess("User deleted.", "/manageusers");
    }

    function settings_edit()
    {
        !isLoggedIn()? redirect("/login"): "";

        // connect to database
        $database = connectToDB();

        // capture data from POST
        $username = $_POST["username"];
        $email = $_POST["email"];
        $id = $_POST["id"];

        // error-handling
        // ensure all fields filled
        if (empty($username) || empty($email)) {
            setError("All fields are required.", "/settings");
        }
        // ensure username not in use w/o checking current user; prevents "username in use" error if username not edited
        $sql = "SELECT * FROM users WHERE username = :username AND id != :id";
        $query = $database -> prepare($sql);
        $query -> execute([
            "username" => $username,
            "id" => $id
        ]);
        $user = $query -> fetch();
        if (!empty($user)) {
            setError("Username is already in use.", "/settings");
        }
        // ensure email not in use w/o checking current user; prevents "email in use" error if email not edited
        $sql = "SELECT * FROM users WHERE email = :email AND id != :id";
        $query = $database -> prepare($sql);
        $query -> execute([
            "email" => $email,
            "id" => $id
        ]);
        $user = $query -> fetch();
        if (!empty($user)) {
            setError("Email is already in use.", "/settings");
        }

        // update user
        $sql = "UPDATE users SET username = :username, email = :email WHERE id = :id";
        $query = $database -> prepare($sql);
        $query -> execute([
            "username" => $username,
            "email" => $email,
            "id" => $id
        ]);

        // update current session details
        $_SESSION["user"]["username"] = $username;
        $_SESSION["user"]["email"] = $email;

        // confirm user updated and redirect
        setSuccess("User details updated.", "/settings");
    }
    
    function settings_password()
    {
        !isLoggedIn()? redirect("/login"): "";

        // connect to database
        $database = connectToDB();

        // capture data from POST
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];
        $current_password = $_POST["current_password"];
        $id = $_POST["id"];

        // error-handling
        // ensure all fields filled
        if (empty($new_password) || empty($confirm_password) || empty($current_password)) {
            setError("All fields are required.", "/settings_password");
        }
        // ensure current password correct
        $sql = "SELECT * FROM users WHERE id = :id"; 
        $query = $database -> prepare($sql);
        $query -> execute(["id" => $id]);
        $user = $query -> fetch();
        if (!password_verify($current_password, $user["password"])) {
            setError("Current password is incorrect.", "/settings_password");
        }
        // ensure current password not used
        if (password_verify($new_password, $user["password"])) {
            setError("New password should not be the same as current password.", "/settings_password");
        }
        // ensure password >= 8 characters 
        if (strlen($new_password) < 8) {
            setError("Password must be at least 8 characters.", "/settings_password");
        }
        // ensure passwords match
        if ($new_password !== $confirm_password) {
            setError("Password does not match confirmation.", "/settings_password");
        }

        // update password
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $query = $database -> prepare($sql);
        $query -> execute([
            "password" => password_hash($new_password, PASSWORD_DEFAULT),
            "id" => $id
        ]);

        // update current session details
        $_SESSION["user"]["password"] = password_hash($new_password, PASSWORD_DEFAULT);
        // confirm password change and redirect
        setSuccess("Password updated.", "/settings_password");
    }
}
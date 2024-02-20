How to spit multiple errors in 1 go

functions
<?php
  function setErrors( $error_message ) {
    $_SESSION["error"][] = $error_message;
  }
?>

message_error
<?php if ( isset( $_SESSION["error"] ) ) : ?>
    <div class="alert alert-danger" role="alert">
    <?php 
        // echo $_SESSION["error"]; 
    ?>
    You have the following errors:
    <ul class="m-0">
    <?php foreach( $_SESSION["error"] as $error ) : ?>
        <li><?= $error; ?></li>
    <?php endforeach; ?>
    </ul>
    <?php 
        // remove error after it's shown
        unset( $_SESSION["error"] ); 
    ?>
    </div>
<?php endif; ?>

signup
<?php

    // Step 2: connect to the database
    $database = connectToDB();

    // Step 3: get all the data from the form using $_POST
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Step 4: error checking
    // 4.1 make sure all the fields are not empty
    if ( empty( $name ) ) {
        setErrors( "Name field is required." );
    } 

    if ( empty( $email )  ) {
        setErrors( "Email field is required." );
    } 

    if ( empty( $password ) ) {
        setErrors( "Password field is required." );
    } 

    if (  empty( $confirm_password ) ) {
        setErrors( "Confirm password field is required." );
    } 
    
    if ( $password !== $confirm_password ) {
        // 4.2 - make sure password is match
        setErrors( "The password is not match" );
    } 
    
    if ( strlen( $password ) < 8 ) {
        // 4.3 - make sure the password length is at least 8 chars
        setErrors( "Your password must be at least 8 characters" );
    } 

    if ( !empty( $_SESSION["error"] ) ) {
        header("Location: /signup");
        exit;
    }

    // step 5: make sure the email entered does not exists yet
    $sql = "SELECT * FROM users where email = :email";
    $query = $database->prepare( $sql );
    $query->execute([
        'email' => $email
    ]);
    $user = $query->fetch(); // get only one row of data

    if ( empty( $user ) ) {
        // step 6: create the user account
            // 6.1 - sql command (recipe)
            $sql = "INSERT INTO users (`name`,`email`,`password`) VALUES (:name, :email, :password)";
            // 6.2 - prepare (put everything into th bowl)
            $query = $database->prepare( $sql );
            // 6.3 - execute (cook it)
            $query->execute([
                'name' => $name,
                'email' => $email,
                'password' => password_hash( $password, PASSWORD_DEFAULT )
            ]);

        // Step 7: redirect back to login
        // set success message
        $_SESSION["success"] = "Account has been created successfully. Please login with your email & password.";
        header("Location: /login");
        exit;
    } else {
        setErrors("The email provided has already been used.");
    }

    if ( !empty( $_SESSION["error"] ) ) {
        header("Location: /signup");
        exit;
    }
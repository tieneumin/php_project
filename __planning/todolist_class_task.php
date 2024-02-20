<?php
// class naming convention: starts uppercased
class Task
{
    function add()
    {
        $database = connectToDB();

        // capture data from POST
        $todo_label = $_POST["todo_label"];
        
        // error-checking
        // ensure field filled
        if (empty($todo_label)) {
            setError("Field cannot be empty.", "/");
        }
        
        // add label to database under logged-in user
        // SQL command
        $sql = 'INSERT INTO todos (`label`,`user_id`) VALUES (:label, :user_id)';
        // prepare query
        $query = $database -> prepare($sql);
        // execute query with placeholder (security)
        $query -> execute([
            "label" => $todo_label,
            "user_id" => $_SESSION["user"]['id']
        ]);
        
        // redirect to home
        header("Location: /");
        exit;      
    }

    function complete()
    {
        $database = connectToDB();

        // capture data from POST
        $todo_completed = $_POST["todo_completed"];
        $todo_id = $_POST["todo_id"];
        
        // check state and update accordingly
        if ($todo_completed == 0) {
            // SQL command
            $sql = "UPDATE todos SET completed = 1 WHERE id = :id";
        } else if ($todo_completed == 1) {
            // SQL command
            $sql = "UPDATE todos SET completed = 0 WHERE id = :id";
        }
        // prepare query
        $query = $database -> prepare($sql);
        // execute query with placeholder (security)
        $query -> execute(["id" => $todo_id]);
        
        // redirect to home
        header("Location: /");
        exit;
    }

    function delete()
    {
        $database = connectToDB();

        // capture data from POST
        $todo_id = $_POST["todo_id"];

        // delete label via filtering by id
        // SQL command
        $sql = "DELETE FROM todos where id = :id";
        // prepare SQL query
        $query = $database -> prepare($sql);
        // execute query with placeholder 
        $query -> execute(["id" => $todo_id]);

        // redirect to home
        header("Location: /");
        exit;
    }
}
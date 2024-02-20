<?php
// by convention, classes start uppercased
class Comment
{
    function add() 
    {
        !isLoggedIn()? redirect("/login"): "";

        // connect to database
        $database = connectToDB();

        // capture data from POST
        $content = $_POST["content"];
        $post_id = $_POST["post_id"];
        $user_id = $_POST["user_id"];

        // error-handling
        if (empty($content)) {
            setError("Field cannot be blank.", "/post?id=".$post_id);
        }

        // add comment and redirect
        $sql = "INSERT INTO comments (`content`,`post_id`,`user_id`) VALUES (:content, :post_id, :user_id)";
        $query = $database -> prepare($sql);
        $query -> execute([
            "content" => $content,
            "post_id" => $post_id,
            "user_id" => $user_id
        ]);
        redirect("/post?id=".$post_id);
    }

    function edit()
    {
        !isLoggedIn()? redirect("/login"): "";
        
        // connect to database
        $database = connectToDB();
        
        // capture data from POST
        $content = $_POST["content"];
        $removed = $_POST["removed"];
        $id = $_POST["id"];
        
        // error-handling
        // ensure all fields filled
        if (empty($content)) {
          setError("Content is required.", "/editcomment?id=".$id);
        }

        // update post
        $sql = "UPDATE comments SET content = :content, removed = :removed WHERE id = :id";
        $query = $database -> prepare($sql);
        $query -> execute([
            "content" => $content,
            "removed" => $removed,
            "id" => $id
        ]);
        
        // confirm and redirect
        setSuccess("Comment updated.", "/managecomments");
    }

    function delete() 
    {
        !isLoggedIn()? redirect("/login"): "";

        // connect to database
        $database = connectToDB();

        // capture id from POST
        $id = $_POST["id"];

        // delete post
        $sql = "DELETE FROM comments where id = :id";
        $query = $database -> prepare($sql);
        $query -> execute(["id" => $id]);
        // confirm deletion and redirect
        setSuccess("Comment deleted.", "/managecomments");
    }
}
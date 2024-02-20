<?php
// by convention, classes start uppercased
class Post
{
    function add() 
    {
        !isLoggedIn()? redirect("/login"): "";

        // connect to database
        $database = connectToDB();

        // capture data from POST
        $title = $_POST["title"];
        $content = $_POST["content"];
        $flair = $_POST["flair"];
        $image = $_POST["image"];
        $user_id = $_POST["user_id"];

        // error-handling
        // ensure required fields filled
        if (empty($title) || empty($content) || empty($flair)) {
            setError("Title, content and flair are required.", "/createpost");
        }

        // add post
        $sql = "INSERT INTO posts (`title`,`content`,`flair`,`image`,`user_id`) VALUES (:title, :content, :flair, :image, :user_id)";
        $query = $database -> prepare($sql);
        $query -> execute([
            "title" => $title,
            "content" => $content,
            "flair" => $flair,
            "image" => $image,
            "user_id" => $user_id
        ]);

        // confirm and redirect
        setSuccess("Post created.", "/manageposts");
    }

    function edit()
    {
        !isLoggedIn()? redirect("/login"): "";
        
        // connect to database
        $database = connectToDB();
        
        // capture data from POST
        $title = $_POST["title"];
        $content = $_POST["content"];
        $flair = $_POST["flair"];
        $image = $_POST["image"];
        $status = $_POST["status"];
        $id = $_POST["id"];
        
        // error-handling
        // ensure all fields filled
        if (empty($title) || empty($content) || empty($flair)) {
          setError("Title, content and flair are required.", "/editpost?id=".$id);
        }

        // update post
        $sql = "UPDATE posts SET title = :title, content = :content, flair = :flair, image = :image, status = :status WHERE id = :id";
        $query = $database -> prepare($sql);
        $query -> execute([
            "title" => $title,
            "content" => $content,
            "flair" => $flair,
            "image" => $image,
            "status" => $status,
            "id" => $id
        ]);
        
        // confirm and redirect
        setSuccess("Post updated.", "/manageposts");
    }

    function delete() 
    {
        !isLoggedIn()? redirect("/login"): "";

        // connect to database
        $database = connectToDB();

        // capture id from POST
        $id = $_POST["id"];

        // delete post
        $sql = "DELETE FROM posts where id = :id";
        $query = $database -> prepare($sql);
        $query -> execute(["id" => $id]);
        
        // confirm and redirect
        setSuccess("Post deleted.", "/manageposts");
    }
}
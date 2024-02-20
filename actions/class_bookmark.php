<?php
// by convention, classes start uppercased
class Bookmark
{
    function post() 
    {
        !isLoggedIn()? redirect("login"): "";

        // connect to database
        $database = connectToDB();

        // capture id from GET
        $post_id = $_GET["post_id"];
        
        // query for post bookmark
        $sql = "SELECT * FROM bookmarks
            WHERE post_id = :post_id AND user_id = :user_id AND comment_id IS NULL";
        $query = $database -> prepare($sql);
        $query -> execute([
            "post_id" => $post_id,
            "user_id" => $_SESSION["user"]["id"]
        ]);
        $bookmark = $query -> fetch();
        
        // add if not discovered
        if (empty($bookmark)) {
            $sql = "INSERT INTO bookmarks (`post_id`,`user_id`) VALUES (:post_id, :user_id)";
            $query = $database -> prepare($sql);
            $query -> execute([
                "post_id" => $post_id,
                "user_id" => $_SESSION["user"]["id"]
            ]);
            redirect("/post?id=".$post_id);
        } else {
            $sql = "DELETE FROM bookmarks WHERE id = :id";
            $query = $database -> prepare($sql);
            $query -> execute(["id" => $bookmark["id"]]);
            redirect("/post?id=".$post_id);
        }
    }

    function delete() 
    {
        !isLoggedIn()? redirect("/login"): "";

        // connect to database
        $database = connectToDB();

        // capture id from POST
        $id = $_POST["bookmark_id"];

        // delete post
        $sql = "DELETE FROM bookmarks WHERE id = :id";
        $query = $database -> prepare($sql);
        $query -> execute(["id" => $id]);
        
        // confirm and redirect
        redirect("/bookmarks");
    }
}

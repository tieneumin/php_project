php.ini
file_uploads = on

add.php
<form
    method="POST" 
    action="/post/edit"
    enctype="multipart/form-data" 
>
<!-- enctype required for $FILES to work -->
<div class="mb-3">
    <input type="file" name="image" />
</div>
<!-- if type doesn't work, use type="text" and enter online URL --> 

add_action.php
<?php
// capture image file alongside title/post data
$image = $_FILES["image"];

// ensure only upload if image available
if ( !empty( $image['name'] ) ) {
    $target_dir = "uploads/";
    // add image name to uploads folder path; time() creates unique name to prevent overwriting  
    $target_path = $target_dir . time() . '_' . basename( $image['name'] ); // uploads/892938829293_image.jpg
    // move file to uploads folder
    move_uploaded_file( $image["tmp_name"], $target_path );
}

// add image data to database
$sql = "INSERT INTO posts (`title`,`content`,`image`,`user_id`) VALUES (:title,:content,:image,:user_id)";
$query = $database->prepare( $sql );
$query->execute([
    'title' => $title,
    'content' => $content,
    // // for offline upload:
    // 'image' => !empty( $image['name'] ) ? $target_path : '',
    // // for online use:
    // 'image' => $image,
    'user_id' => $_SESSION['user']['id']
]);
?>

edit.php
<div>
    <input type="file" name="image" />
    <?php if ( !empty( $post["image"] ) ) : ?>
        <div>
            <img src="/<?= $post["image"]; ?>" width="150px" class="mt-1" />
            <input type="hidden" name="original_image" value="<?= $post["image"]; ?>" />
        </div>
    <?php endif; ?>
</div>

edit_action.php
<?php
// capture the image file
$image = $_FILES["image"];

// make sure that you only upload if image is available
if ( !empty( $image['name'] ) ) {
    $target_dir = "uploads/";
    // add the image name to the uploads folder path
    $target_path = $target_dir . time() . '_' . basename( $image['name'] ); // uploads/892938829293_image.jpg
    // move the file to the uploads folder
    move_uploaded_file( $image["tmp_name"], $target_path );
}

// update user data
$sql = "UPDATE posts 
    SET title = :title , status = :status , content = :content, image = :image
    WHERE id = :id";
$query = $database->prepare( $sql );
$query->execute([
    'title' => $title,
    'content' => $content,
    'status' => $status,
    // // for offline upload:
    // 'image' => !empty( $image['name'] ) ? $target_path : $_POST['original_image'],
    // // for online use:
    // 'image' => $image,
    'id' => $id
]);
?>

post.php
<?php if ( !empty( $post["image"] ) ) : ?>
    <!-- for file upload: -->
    <img src="/<?= $post["image"]; ?>" class="img-fluid" />
    <!-- from other website: -->
    <img src="<?= $post["image"]; ?>" class="img-fluid" />
<?php endif; ?>


upload.php <!-- https://www.w3schools.com/php/php_file_upload.asp -->
var_dump($image); // spits array
$target_dir => where to store the file
move_uploaded_file => move file from temp to code base 
add time string to uploaded doc to prevent image overwrite
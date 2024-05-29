<?php
require_once "functions.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Crud</title>
    <link rel="stylesheet" href="./simply.css">
</head>
<body>
    <div class="container">

    <?php
    if(isset($_GET["action"]) && $_GET["action"] == "edit"){
    // Update Data from DB
    $id = $_GET["id"];
    $selectdata = "select * from `crud` where id = '$id'";
    $selecteddata = mysqli_query($conn, $selectdata);
    $dbdata = mysqli_fetch_assoc($selecteddata);
    $image = $dbdata["image"];
        ?>
    <div class="md-grid">
    <p class="font-large"><a href="./index.php"><i class="chevron-left-icon"> </i>Go Back</a></p>
    <form action="./index.php?action=update" method="post" enctype="multipart/form-data" class="mt-s">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" value="<?=$dbdata['title'];?>" placeholder="Title" name="title" id="name">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" placeholder="Description"><?=$dbdata['description'];?></textarea>
        </div>
        <input type="hidden" value="<?=$dbdata['id']?>" name="id">
        <img src="<?=$dbdata['image'];?>" class="height-150px">
        <input type="file" name="image" class="my-1">
        <input type="submit" value="Update" class="btn btn-success block">
    </form>
    </div>
    <?php } else{
    ?>

        <div class="md-grid md-items-2 md-gap-2 my-2">

        <div class="left-column">
        <h1><a href="./index.php">PHP Simple Crud</a></h1>
    <form action="./index.php?action=add" method="post" enctype="multipart/form-data" class="mt-1">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" placeholder="Title" name="title" id="name">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" placeholder="Description"></textarea>
        </div>
        <input type="file" name="image" class="mb-1">
        <input type="submit" value="Add" class="btn btn-main block">
    </form>
    </div>

    <div class="right-column">
        <h2>Output</h2>
        <?php show_data($conn); ?>
    </div>

    </div>
    <?php } ?>
    </div>
</body>
</html>

<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Add Data in the DB
    if(isset($_GET["action"]) && $_GET["action"] == "add"){
        function add_data($conn){
        $title = check_input($_POST["title"]);
        $description = check_input($_POST["description"]);
        $image = "";
        if(!empty($_FILES["image"]) && !empty($_FILES["image"]["name"])){
            $image_name = $_FILES["image"]["name"];
            $image_tmp = $_FILES["image"]["tmp_name"];
            $image_type = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
            $image_size = $_FILES["image"]["size"];
            $allowedExtentions = array("jpg", "jpeg", "png");
            $folder = "./uploads/";
            if(validate_file($folder, $image_type, $image_size, $allowedExtentions)){
                $image = $folder.time().$image_name;
                move_uploaded_file($image_tmp, $image);
            }
        }
        $insert = "insert into `crud`(title, description, image) values('$title', '$description', '$image')";
        $inserted = mysqli_query($conn, $insert);
        if($inserted){
            // header("Location: ./index.php");
            echo "<script>location.assign('./index.php');</script>";
        }
    }
    add_data($conn);
}

}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Update Data in the DB
    if(isset($_GET["action"]) && $_GET["action"] == "update"){
        function update_data($conn){
        $id = check_input($_POST["id"]);
        $select = "select * from `crud` where id = $id";
        $selected = mysqli_query($conn, $select);
        $selecteddb = mysqli_fetch_assoc($selected);
        $title = check_input($_POST["title"]);
        $description = check_input($_POST["description"]);
        $image = $selecteddb["image"];
        if(!empty($_FILES["image"]) && !empty($_FILES["image"]["name"])){
            $image_name = $_FILES["image"]["name"];
            $image_tmp = $_FILES["image"]["tmp_name"];
            $image_type = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
            $image_size = $_FILES["image"]["size"];
            $allowedExtentions = array("jpg", "jpeg", "png");
            $folder = "./uploads/";
            if(validate_file($folder, $image_type, $image_size, $allowedExtentions)){
                $image = $folder.time().$image_name;
                move_uploaded_file($image_tmp, $image);
            }
        }
        $update = "update `crud` set `title` = '$title', `description` = '$description', `image` = '$image' where `id` = $id";
        $updated = mysqli_query($conn, $update);
        if($updated){
            // header("Location: ./index.php");
            echo "<script>location.assign('./index.php');</script>";
        }
    }
    update_data($conn);
}

}

// Delete Data from DB
if(isset($_GET["action"]) && $_GET["action"] == "delete"){
    $id = $_GET["id"];
    $delete = "delete from `crud` where id = '$id'";
    $deleted = mysqli_query($conn, $delete);
    if($deleted){
        // header("Location: index.php");
        echo "<script>location.assign('./index.php');</script>";
    }
}

// Show Data from DB
function show_data($conn){
    $select = "select * from `crud`";
    $selected = mysqli_query($conn, $select);
    if(mysqli_num_rows($selected) > 0){
        // $data = mysqli_fetch_all($selected, MYSQLI_ASSOC);
        ?>
        <table class="table-light-grey mt-1">
            <thead>
                <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        <?php
        while($data = mysqli_fetch_assoc($selected)){ ?>
                <tr>
                <td><?=$data["title"]?></td>
                <td><?=$data["description"]?></td>
                <td><image src='<?=$data["image"]?>' class="width-60px height-50px"></td>
                <td class="text-center">
                    <a href="./index.php?action=edit&id=<?=$data['id'];?>" class="edit mr-s"><i class="pencil-icon"></i></a>
                    <a href="./index.php?action=delete&id=<?=$data['id'];?>" class="edit" onclick="return confirm('You really wanna to delete this?');"><i class="delete-icon"></i></a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
        </table>
        <?php
    }
}
?>
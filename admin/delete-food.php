<?php
// Include Constants Page
include('../config/constants.php');

// Check if ID and image name are set
if (isset($_GET['id']) && isset($_GET['image_name'])) {
    // Process to delete
    // 1. Get ID and Image Name
    $id = $_GET['id'];
    $image_name = $_GET['image_name'];

    // 2. Remove the Image If Available
    if ($image_name != "") {
        // Get the Image Path
        $path = "../images/food/" . $image_name;

        // Remove Image File from Folder
        if (!unlink($path)) {
            // Failed to Remove image
            $_SESSION['upload'] = "<div class='error'>Failed to Remove Image File.</div>";
            header('location:' . SITEURL . '../food-order/admin/manage-food.php');
            exit(); // Stop the process
        }
    }

    // 3. Delete Food from Database
    $sql = "DELETE FROM tbl_food WHERE id=$id";
    
    // Execute the Query
    $res = mysqli_query($conn, $sql);

    // 4. Redirect to Manage Food with Session Message
    if ($res) {
        $_SESSION['delete'] = "<div class='success'>Food Deleted Successfully.</div>";
    } else {
        $_SESSION['delete'] = "<div class='error'>Failed to Delete Food.</div>";
    }
    
    header('location:' . SITEURL . 'admin/manage-food.php'); 
    exit();

} else {
    // Redirect to Manage Food Page
    $_SESSION['delete'] = "<div class='error'>Unauthorized Access.</div>";
    header('location:' . SITEURL . 'admin/manage-food.php'); 
    exit();
}
?>
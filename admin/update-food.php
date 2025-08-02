<?php include('partials/menu.php'); ?>

<?php 
// Check whether the food ID is set in the URL
if (isset($_GET['id'])) {
    // Get the food ID from the URL
    $id = $_GET['id'];

    // SQL Query to get the selected food details
    $sql2 = "SELECT * FROM tbl_food WHERE id=$id";
    // Execute the query
    $res2 = mysqli_query($conn, $sql2);

    // Check if the query was successful
    if ($res2) {
        // Fetch the food details
        $row = mysqli_fetch_assoc($res2);

        // Get individual values of the selected food
        $title = $row['title'];
        $description = $row['description'];
        $price = $row['price'];
        $current_image = $row['image_name'];
        $current_category = $row['category_id'];
        $featured = $row['featured'];
        $active = $row['active'];
    } else {
        // If no food found, redirect to Manage Food page
        header('location:'.SITEURL.'admin/manage-food.php');
        exit();
    }
} else {
    // If ID is not set, redirect to Manage Food page
    header('location:'.SITEURL.'admin/manage-food.php');
    exit();
}
?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Food</h1>
        <br><br>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
                    </td>
                </tr>

                <tr>
                    <td>Description: </td>
                    <td>
                        <textarea name="description" cols="30" rows="5" required><?php echo htmlspecialchars($description); ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Price: </td>
                    <td>
                        <input type="number" name="price" value="<?php echo htmlspecialchars($price); ?>" required>
                    </td>
                </tr>

                <tr>
                    <td>Current Image: </td>
                    <td>
                        <?php
                        if ($current_image == "") {
                            echo "<div class='error'>Image Not Available.</div>"; 
                        } else {
                            echo "<img src='".SITEURL."images/food/$current_image' width='170px'>";
                        }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>Select New Image: </td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Category: </td>
                    <td>
                        <select name="category" required>
                            <?php 
                            // Query to get active categories
                            $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                            $res = mysqli_query($conn, $sql);
                            
                            // Check whether categories are available
                            if (mysqli_num_rows($res) > 0) {
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $category_title = $row['title'];
                                    $category_id = $row['id'];
                                    ?>
                                    <option <?php if ($current_category == $category_id) { echo "selected"; } ?> value="<?php echo $category_id; ?>"><?php echo htmlspecialchars($category_title); ?></option>
                                    <?php
                                }
                            } else {
                                echo "<option value='0'>Category Not Available.</option>"; 
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input <?php if ($featured == "Yes") { echo "checked"; } ?> type="radio" name="featured" value="Yes"> Yes
                        <input <?php if ($featured == "No") { echo "checked"; } ?> type="radio" name="featured" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input <?php if ($active == "Yes") { echo "checked"; } ?> type="radio" name="active" value="Yes"> Yes
                        <input <?php if ($active == "No") { echo "checked"; } ?> type="radio" name="active" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                        <input type="submit" name="submit" value="Update Food" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>

        <?php  
        // Check if the form is submitted
        if (isset($_POST['submit'])) {
            // Get all the details from the form
            $id = $_POST['id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $current_image = $_POST['current_image'];
            $category = $_POST['category'];
            $featured = $_POST['featured'];
            $active = $_POST['active'];

            // Check if a new image is uploaded
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                // New image is uploaded
                $image_name = $_FILES['image']['name']; // New image name
                // Rename the image
                $image_parts = explode('.', $image_name); // Split the image name
                $ext = end($image_parts); // Get the image extension
                $image_name = "Food_Name_" . rand(000, 999) . "." . $ext; // Rename the image

                // Get source and destination paths
                $src_path = $_FILES['image']['tmp_name']; // Source path
                $dest_path = "../images/food/" . $image_name; // Destination path

                // Upload the image
                $upload = move_uploaded_file($src_path, $dest_path);

                // Check if the image is uploaded successfully
                if ($upload == false) {
                    // Failed to upload
                    $_SESSION['upload'] = "<div class='error'>Failed to Upload New Image.</div>";
                    header('location:' . SITEURL . 'admin/manage-food.php');
                    exit();
                }

                // Remove the current image if a new one is uploaded
                if ($current_image != "") {
                    $remove_path = "../images/food/" . $current_image; // Corrected path
                    if (file_exists($remove_path)) {
                        $remove = unlink($remove_path); // Remove the old image
                        // Check if the removal was successful
                        if ($remove == false) {
                            $_SESSION['remove-failed'] = "<div class='error'>Failed to remove current image.</div>";
                            header('location:' . SITEURL . 'admin/manage-food.php');
                            exit();
                        }
                    } else {
                        // Image does not exist
                        $_SESSION['remove-failed'] = "<div class='error'>Current image not found.</div>";
                    }
                }
            } else {
                // If no new image is uploaded, use the current image
                $image_name = $current_image; 
            }

            // Update the food details in the database
            $sql3 = "UPDATE tbl_food SET 
                title = '$title',
                description = '$description',
                price = $price,
                image_name = '$image_name',
                category_id = '$category',
                featured = '$featured',
                active = '$active'
                WHERE id = $id
            ";

            // Execute the SQL query
            $res3 = mysqli_query($conn, $sql3);

            // Check if the update was successful
            if ($res3) {
                $_SESSION['update'] = "<div class='success'>Food Updated Successfully.</div>";
                header('location:' . SITEURL . 'admin/manage-food.php'); 
            } else {
                $_SESSION['update'] = "<div class='error'>Failed to Update Food.</div>";
                header('location:' . SITEURL . 'admin/manage-food.php'); 
            }
        }
        ?>

    </div>
</div>

<?php include('partials/footer.php'); ?>
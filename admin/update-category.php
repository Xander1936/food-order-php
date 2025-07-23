<?php include('partials/menu.php'); ?> 

<div class="main-content">
    <div class="wrapper">
        <h1>Update Category</h1>
        <br><br>

        <?php  
            // Check whether the id is set or not
            if(isset($_GET['id'])){
                // Get the ID and all other details
                $id = $_GET['id'];
                // Create SQL Query to get all other details
                $sql = "SELECT * FROM tbl_category WHERE id=$id";

                // Execute the Query
                $res = mysqli_query($conn, $sql);

                // Count the rows to check whether the id is valid or not
                $count = mysqli_num_rows($res);

                if($count == 1) {
                    // Get all the data
                    $row = mysqli_fetch_assoc($res);
                    $title = $row['title'];
                    $current_image = $row['image_name'];
                    $featured = $row['featured'];
                    $active = $row['active'];
                } else {
                    // Redirect to Manage Category Page with Session Message
                    $_SESSION['no-category-found'] = "<div class='error'>Category not found.</div>";
                    header('location:'.SITEURL.'admin/manage-category.php');
                    exit();
                }
            } else {
                // Redirect to Manage Category
                header('location:'.SITEURL.'admin/manage-category.php');
                exit();
            }
        ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>">
                    </td>
                </tr>

                <tr>
                    <td>Current Image: </td>
                    <td>
                        <?php 
                            if($current_image != "") {
                                echo "<img src='".SITEURL."images/category/$current_image' width='170px'>";
                            } else {
                                echo "<div class='error'>Image Not Added.</div>"; 
                            }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>New Image: </td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input <?php if($featured == "Yes"){echo "checked";} ?> type="radio" name="featured" value="Yes"> Yes
                        <input <?php if($featured == "No"){echo "checked";} ?> type="radio" name="featured" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input <?php if($active == "Yes"){echo "checked";} ?> type="radio" name="active" value="Yes"> Yes
                        <input <?php if($active == "No"){echo "checked";} ?> type="radio" name="active" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td>
                        <!-- Used htmlspecialchars() to escape output variables for better security. -->
                        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($current_image); ?>">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                        <input type="submit" name="submit" value="Update Category" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>
        
        <?php 
            if(isset($_POST['submit'])) {
                // 1. Get all the values from our Form
                $id = $_POST['id'];
                $title = $_POST['title'];
                $current_image = $_POST['current_image'];
                $featured = $_POST['featured'];
                $active = $_POST['active'];

                // 2. Updating New Image if Selected
                if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                    // Get the Image Details
                    $image_name = $_FILES['image']['name'];

                    // Auto Rename our Image 
                    $ext = end(explode(".", $image_name));
                    $image_name = "Food_Category_".rand(000, 999).".".$ext;

                    $source_path = $_FILES['image']['tmp_name'];
                    $destination_path = "../images/category/".$image_name;

                    $upload = move_uploaded_file($source_path, $destination_path);

                    // Check whether the image is uploaded or not
                    if(!$upload) {
                        $_SESSION['upload'] = "<div class='error'>Failed to Upload image.</div>";
                        header('location:'.SITEURL.'admin/manage-category.php');
                        exit(); 
                    }

                    // Remove the current image if available
                    if($current_image != "") {
                        $remove_path = "../images/category/".$current_image;
                        if(!unlink($remove_path)){
                            $_SESSION['failed-remove'] = "<div class='error'>Failed to remove current image.</div>";
                            header('location:'.SITEURL.'admin/manage-category.php');
                            exit(); 
                        }
                    }
                } else {
                    $image_name = $current_image;
                }

                // 3. Update the Database
                // $sql2 in the query execution for updating the category
                $sql2 = "UPDATE tbl_category SET
                    title = '$title',
                    image_name = '$image_name',
                    featured = '$featured',
                    active = '$active'
                    WHERE id=$id
                ";

                // Execute the Query
                $res2 = mysqli_query($conn, $sql2);

                // 4. Redirect to Manage Category Page with Message
                if($res2) {
                    $_SESSION['update'] = "<div class='success'>Category Updated Successfully.</div>";
                } else {
                    $_SESSION['update'] = "<div class='error'>Failed to Update Category.</div>";
                }
                header('location:'.SITEURL.'admin/manage-category.php'); 
                // exit(); after each header() call to prevent further script execution
                exit();
            }
        ?>

    </div>
</div>

<?php include('partials/footer.php'); ?>
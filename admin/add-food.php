<?php
// Start output buffering to avoid "headers already sent"
ob_start();

// Include menu / header (assumes this sets up DB $conn and session)
include('partials/menu.php');
?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Food</h1>

        <br><br>

        <?php
            // Display upload message if set
            if (isset($_SESSION['upload'])) {
                echo $_SESSION['upload'];
                unset($_SESSION['upload']);
            }

            // Display add-message if set
            if (isset($_SESSION['add'])) {
                echo $_SESSION['add'];
                unset($_SESSION['add']);
            }
        ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="tbl-30">

                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" placeholder="Title of the Food" required>
                    </td>
                </tr>

                <tr>
                    <td>Description: </td>
                    <td>
                        <textarea name="description" cols="30" rows="5" placeholder="Description of the Food." required></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Price: </td>
                    <td>
                        <input type="number" name="price" step="0.01" required>
                    </td>
                </tr>

                <tr>
                    <td>Select Image: </td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Category: </td>
                    <td>
                        <select name="category">
                            <?php
                                // Get active categories from DB
                                $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                                $res = mysqli_query($conn, $sql);
                                if ($res && mysqli_num_rows($res) > 0) {
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        $cat_id = $row['id'];
                                        $cat_title = htmlspecialchars($row['title']);
                                        echo "<option value=\"{$cat_id}\">{$cat_title}</option>";
                                    }
                                } else {
                                    echo '<option value="0">No Category Found</option>';
                                }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input type="radio" name="featured" value="Yes" required> Yes
                        <input type="radio" name="featured" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input type="radio" name="active" value="Yes" required> Yes
                        <input type="radio" name="active" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Food" class="btn-secondary">
                    </td>
                </tr>

            </table>
        </form>

        <?php
        // Process form submission
        if (isset($_POST['submit'])) {
            // Sanitize inputs
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            // Price: allow decimals
            $price = floatval($_POST['price']);
            $category = intval($_POST['category']);
            $featured = isset($_POST['featured']) ? mysqli_real_escape_string($conn, $_POST['featured']) : 'No';
            $active = isset($_POST['active']) ? mysqli_real_escape_string($conn, $_POST['active']) : 'No';

            // Handle image upload
            $image_name = "";
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                $original_name = $_FILES['image']['name'];

                // Get extension safely
                $ext = pathinfo($original_name, PATHINFO_EXTENSION);
                $ext = strtolower($ext);

                // Create new image name
                $image_name = "Food-Name-" . rand(1000, 9999) . "." . $ext;

                $src = $_FILES['image']['tmp_name'];
                $dst = "../images/food/" . $image_name;

                if (!move_uploaded_file($src, $dst)) {
                    $_SESSION['upload'] = "<div class='error'>Failed to Upload Image.</div>";
                    header('Location: ' . SITEURL . 'admin/add-food.php');
                    exit();
                }
            }

            // Insert into database
            $sql2 = "INSERT INTO tbl_food SET
                title = '$title',
                description = '$description',
                price = $price,
                image_name = '$image_name',
                category_id = $category,
                featured = '$featured',
                active = '$active'
            ";

            $res2 = mysqli_query($conn, $sql2);

            if ($res2) {
                $_SESSION['add'] = "<div class='success'>Food Added Successfully.</div>";
                header('Location: ' . SITEURL . 'admin/manage-food.php');
                exit();
            } else {
                $_SESSION['add'] = "<div class='error'>Failed to Add Food.</div>";
                header('Location: ' . SITEURL . 'admin/manage-food.php');
                exit();
            }
        }
        ?>

    </div>
</div>

<?php
include('partials/footer.php');
// Flush output buffer
ob_end_flush();
?>
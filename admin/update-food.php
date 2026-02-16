<?php
// Start output buffering to avoid "headers already sent"
ob_start();

// Include menu / header (assumes this sets up DB $conn and session)
include('partials/menu.php');
?>

<?php
// Validate and fetch ID from URL
if (!isset($_GET['id'])) {
    header('Location: ' . SITEURL . 'admin/manage-food.php');
    exit();
}

$id = intval($_GET['id']);

// Fetch existing food record
$sql2 = "SELECT * FROM tbl_food WHERE id = $id";
$res2 = mysqli_query($conn, $sql2);

if (!$res2 || mysqli_num_rows($res2) != 1) {
    // No such food
    header('Location: ' . SITEURL . 'admin/manage-food.php');
    exit();
}

$row = mysqli_fetch_assoc($res2);

$title = $row['title'];
$description = $row['description'];
$price = $row['price'];
$current_image = $row['image_name'];
$current_category = $row['category_id'];
$featured = $row['featured'];
$active = $row['active'];
?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Food</h1>
        <br><br>

        <?php
            // Show any session messages
            if (isset($_SESSION['upload'])) { echo $_SESSION['upload']; unset($_SESSION['upload']); }
            if (isset($_SESSION['remove-failed'])) { echo $_SESSION['remove-failed']; unset($_SESSION['remove-failed']); }
            if (isset($_SESSION['update'])) { echo $_SESSION['update']; unset($_SESSION['update']); }
        ?>

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
                        <input type="number" name="price" value="<?php echo htmlspecialchars($price); ?>" step="0.01" required>
                    </td>
                </tr>

                <tr>
                    <td>Current Image: </td>
                    <td>
                        <?php
                        if ($current_image == "") {
                            echo "<div class='error'>Image Not Available.</div>";
                        } else {
                            echo "<img src='" . SITEURL . "images/food/" . htmlspecialchars($current_image) . "' width='170px'>";
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
                                $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                                $res = mysqli_query($conn, $sql);
                                if ($res && mysqli_num_rows($res) > 0) {
                                    while ($rowCat = mysqli_fetch_assoc($res)) {
                                        $cat_title = htmlspecialchars($rowCat['title']);
                                        $cat_id = $rowCat['id'];
                                        $selected = ($current_category == $cat_id) ? 'selected' : '';
                                        echo "<option value=\"{$cat_id}\" {$selected}>{$cat_title}</option>";
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
                        <input <?php if ($featured == "Yes") echo "checked"; ?> type="radio" name="featured" value="Yes"> Yes
                        <input <?php if ($featured == "No") echo "checked"; ?> type="radio" name="featured" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input <?php if ($active == "Yes") echo "checked"; ?> type="radio" name="active" value="Yes"> Yes
                        <input <?php if ($active == "No") echo "checked"; ?> type="radio" name="active" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="hidden" name="id" value="<?php echo intval($id); ?>">
                        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($current_image); ?>">
                        <input type="submit" name="submit" value="Update Food" class="btn-secondary">
                    </td>
                </tr>

            </table>
        </form>

        <?php
        // Handle update submission
        if (isset($_POST['submit'])) {
            $id = intval($_POST['id']);
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $price = floatval($_POST['price']);
            $current_image = mysqli_real_escape_string($conn, $_POST['current_image']);
            $category = intval($_POST['category']);
            $featured = mysqli_real_escape_string($conn, $_POST['featured']);
            $active = mysqli_real_escape_string($conn, $_POST['active']);

            // Handle new image upload if provided
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                $original_name = $_FILES['image']['name'];

                // Get extension safely
                $ext = pathinfo($original_name, PATHINFO_EXTENSION);
                $ext = strtolower($ext);

                // New file name
                $image_name = "Food_Name_" . rand(100, 999) . "." . $ext;

                $src_path = $_FILES['image']['tmp_name'];
                $dest_path = "../images/food/" . $image_name;

                if (!move_uploaded_file($src_path, $dest_path)) {
                    $_SESSION['upload'] = "<div class='error'>Failed to Upload New Image.</div>";
                    header('Location: ' . SITEURL . 'admin/manage-food.php');
                    exit();
                }

                // Remove old image if it exists
                if ($current_image != "") {
                    $remove_path = "../images/food/" . $current_image;
                    if (file_exists($remove_path)) {
                        if (!unlink($remove_path)) {
                            $_SESSION['remove-failed'] = "<div class='error'>Failed to remove current image.</div>";
                            header('Location: ' . SITEURL . 'admin/manage-food.php');
                            exit();
                        }
                    } else {
                        // Optionally set a message but continue
                        $_SESSION['remove-failed'] = "<div class='error'>Current image not found (can't remove).</div>";
                    }
                }
            } else {
                // No new image uploaded; keep current
                $image_name = $current_image;
            }

            // Update DB
            $sql3 = "UPDATE tbl_food SET
                title = '$title',
                description = '$description',
                price = $price,
                image_name = '$image_name',
                category_id = $category,
                featured = '$featured',
                active = '$active'
                WHERE id = $id
            ";

            $res3 = mysqli_query($conn, $sql3);

            if ($res3) {
                $_SESSION['update'] = "<div class='success'>Food Updated Successfully.</div>";
                header('Location: ' . SITEURL . 'admin/manage-food.php');
                exit();
            } else {
                $_SESSION['update'] = "<div class='error'>Failed to Update Food.</div>";
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
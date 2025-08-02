<?php include("../admin/partials/menu.php"); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Manage Food</h1>

        <br><br>
        <!-- Button to Add Food -->
        <a href="<?php echo SITEURL; ?>admin/add-food.php" class="btn-primary">Add Food</a>
        <br><br><br>

        <?php
        // Display session messages
        if (isset($_SESSION['add'])) {
            echo $_SESSION['add'];
            unset($_SESSION['add']);
        }

        if (isset($_SESSION['delete'])) {
            echo $_SESSION['delete'];
            unset($_SESSION['delete']);
        }

        if (isset($_SESSION['upload'])) {
            echo $_SESSION['upload'];
            unset($_SESSION['upload']);
        }

        if (isset($_SESSION['unauthorized'])) {
            echo $_SESSION['unauthorized'];
            unset($_SESSION['unauthorized']);
        }
        if (isset($_SESSION['update'])) {
            echo $_SESSION['update'];
            unset($_SESSION['update']);
        }
        if (isset($_SESSION['remove'])) {
            echo $_SESSION['remove'];
            unset($_SESSION['remove']);
        }
        ?>

        <table class="tbl-full">
            <tr>
                <th>S.N.</th>
                <th>Title</th>
                <th>Price</th>
                <th>Image</th>
                <th>Featured</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>

            <?php 
            // Create a SQL Query to Get all the Food
            $sql = "SELECT * FROM tbl_food";
            
            // Execute the Query
            $res = mysqli_query($conn, $sql);

            // Count Rows to check whether we have foods or not
            $count = mysqli_num_rows($res);

            // Create Serial Number Variable and Set Default Value as 1
            $sn = 1;

            if ($count > 0) {
                // We have food in Database
                // Get the Foods from Database and Display
                while ($row = mysqli_fetch_assoc($res)) {
                    // Get the values from individual columns
                    $id = $row['id'];
                    $title = $row['title'];
                    $price = $row['price'];
                    $image_name = $row['image_name'];
                    $featured = $row['featured'];
                    $active = $row['active'];
                    ?>

                    <tr>
                        <td><?php echo $sn++; ?></td>
                        <td><?php echo htmlspecialchars($title); ?></td>
                        <td>$<?php echo htmlspecialchars($price); ?></td>
                        <td>
                            <?php 
                            // Check whether we have image or not
                            if ($image_name != "") {
                                // We have Image, Display Image
                                ?>
                                <img src="<?php echo SITEURL; ?>images/food/<?php echo htmlspecialchars($image_name); ?>" width="120px">
                                <?php
                            } else {
                                // We do not have Image, Display the Error Message
                                echo "<div class='error'>Image Not Added.</div>"; 
                            }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($featured); ?></td>
                        <td><?php echo htmlspecialchars($active); ?></td>
                        <td>
                            <a href="<?php echo SITEURL; ?>admin/update-food.php?id=<?php echo $id; ?>&image_name=<?php echo htmlspecialchars($image_name); ?>" class="btn-secondary">Update Food</a>
                            <a href="<?php echo SITEURL; ?>admin/delete-food.php?id=<?php echo $id; ?>&image_name=<?php echo htmlspecialchars($image_name); ?>" class="btn-danger">Delete Food</a>
                        </td>
                    </tr>

                    <?php
                }
            } else {
                // No food added in Database
                ?>
                <tr>
                    <td colspan='7'>
                        <div class='error'>Food not Added Yet.</div>
                    </td>
                </tr>
                <?php 
            }
            ?>        
        </table>
    </div>
</div>

<?php include("../admin/partials/footer.php"); ?>
<?php include('partials/menu.php') ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Category</h1>

        <br><br>

        <?php  
            if(isset($_SESSION['add'])) {
                echo $_SESSION['add'];
                unset($_SESSION['add']);
            }
        ?>

        <!-- Add Category Form Starts -->
        <form action="" method="POST">

            <table class="tbl-30">
                <tr>
                    <td>Title: </td>
                    <td>
                        <input type="text" name="title" placeholder="Category Title">
                    </td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input type="radio" name="featured" value="Yes"> Yes
                        <input type="radio" name="featured" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td>Active: </td>
                    <td>
                        <input type="radio" name="active" value="Yes"> Yes
                        <input type="radio" name="active" value="No"> No
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Category" class="btn-secondary">
                    </td>
                </tr>
            </table>    

        </form>
        <!-- Add Category Form Ends -->

        <?php 

            // Check whether the Submit Button is Clicked or Not
            if(isset($_POST['submit'])) {
                // echo "Clicked";
                
                // 1. Get the value from Category Form
                $title  = $_POST['title'];

                // For radio input, we need to check whether the button is selected or not
                if(isset($_POST['featured'])) {
                    // Get the value from Form
                    $featured = $_POST['featured'];
                }else {
                    // Set the Default Value
                    $featured = "No";
                }

                if(isset($_POST['active'])) {
                    $active = $_POST['active'];
                }else {
                    // Set the Default Value
                    $active = "No";
                }
                
                // 2. Create SQL Query to insert Category into Database
                $sql = "INSERT INTO tbl_category SET
                    title='$title',
                    featured='$featured',
                    active='$active'
                ";

                // 3. Execute the Query and Save in the Database
                $res = mysqli_query($conn, $sql);

                // 4. Check whether the Query executed or not and add data or not
                if($res == true) {
                    // Query executed and Category added
                    $_SESSION['add'] = "<div class='success'>Category Added Successfully.</div>";
                    // Redirect to Manage Category Page
                    header('location:'.SITEURL.'admin/manage-category.php');  
                }else {
                    // Failed to Add Category
                    $_SESSION['add'] = "<div class='error'>Failed to Add Category</div>";
                    // Redirect to Add Category Page
                    header('location:'.SITEURL.'admin/add-category.php');  
                }
                
            }

                
                    
            

        ?>

    </div>
</div>

<?php include('partials/footer.php') ?>
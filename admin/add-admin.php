<?php include("../admin/partials/menu.php"); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Admin</h1>
        
        <br><br>

        <?php 
            if(isset($_SESSION['add'])) // Checking whether the session is set or not
            {
                echo $_SESSION['add']; // Display the session message if set.
                unset $_SESSION['add']; // Removing Session message. 
            }
        ?>

        <form action="" method="POST">
            
            <table class="tbl-30">
                <tr>
                    <td>Full Name: </td>
                    <td>
                        <input type="text" name="full_name" placeholder="Enter Your Name.">
                    </td>
                </tr>

                <tr>
                    <td>Username: </td>
                    <td>
                        <input type="text" name="username" placeholder="Your Username">
                    </td>
                </tr>

                <tr>
                    <td>Password: </td>
                    <td>
                        <input type="password" name="password" placeholder="Your Password">
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Admin" class="btn-secondary">
                    </td>
                </tr>
            </table>

        </form>
    </div>
</div>

<?php include("../admin/partials/footer.php"); ?>

<?php 
    // Process the value from Form and Save it in the Database

    // Check whether the submit button is clicked or not
    if(isset($_POST['submit'])) {
        // Button Clicked
        //1. Get the Data from form
        $full_name = $_POST['full_name'];
        $username = $_POST['username'];
        // Password Encryption with MD5
        $password = md5($_POST['password']);

        //2. SQL Query to Save the data into database
        $sql = "INSERT INTO tbl_admin SET
            full_name = '$full_name',
            username = '$username',
            password = '$password'
        ";

        // echo $sql;

        // 3. Executing Query and Saving Data into Database
        $res = mysqli_query($conn, $sql) or die(mysqli_error());

        // 4. Check whether the (Query is Executed) data is inserted or not and display appropriate message.
        if($res == TRUE) {
            // Data Inserted
            // echo "Data inserted";
            // Create a Session Variable to Display Message
            $_SESSION['add'] = "Admin Added Successfully.";
            // Redirect Page to Manage Admin
            header("location:".SITEURL.'admin/manage-admin.php');

        } else {
            // Failed to insert Data.
            // echo "Fail to insert Data";
            // Create a Session Variable to Display Message
            $_SESSION['add'] = "Failed to Add Admin.";
            // Redirect Page to Manage Admin
            header("location:".SITEURL.'admin/add-admin.php');
        }


    }
?>
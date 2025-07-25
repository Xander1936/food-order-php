<?php include("../admin/partials/menu.php"); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Manage Food</h1>

        <br><br>
                <!-- Button to Add Admin -->
                 <a href="<?php echo SITEURL; ?>admin/add-food.php" class="btn-primary">Add Food</a>
                 <br><br><br>
                 <table class="tbl-full">
                     <tr>
                         <th>S.N.</th>
                         <th>Full Name</th>
                         <th>Username</th>
                         <th>Actions</th>
                        </tr>
                        
                        <tr>
                            <td>1.</td>
                            <td>Vijay Thapa</td>
                            <td>vijaythapa</td>
                            <td>
                            <a href="#" class="btn-secondary">Update Food</a>
                            <a href="#" class="btn-danger">Delete Food</a>
                        </td>
                    </tr>

                    <tr>
                        <td>2.</td>
                        <td>Alexandre Massoda</td>
                        <td>alex_xander</td>
                        <td>
                            <a href="#" class="btn-secondary">Update Food</a>
                            <a href="#" class="btn-danger">Delete Food</a>
                        </td>
                    </tr>

                    <tr>
                        <td>3.</td>
                        <td>Lionel Messi</td>
                        <td>m10</td>
                        <td>
                            <a href="#" class="btn-secondary">Update Food</a>
                            <a href="#" class="btn-danger">Delete Food</a>
                        </td>
                    </tr>

                </table>
    </div>
</div>

<?php include("../admin/partials/footer.php"); ?>
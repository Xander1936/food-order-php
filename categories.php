<?php include("../food-order/partials-front/menu.php"); ?>


    

    <!-- CAtegories Section Starts Here -->
    <section class="categories">
        <div class="container">
            <h2 class="text-center">Explore Foods</h2>

            <?php 
                // Display all the Categories that are active
                // SQL Query
                $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                // Execute the Query
                $res = mysqli_query($conn, $sql);
                // Count rows to check whether the category is available or not
                $count = mysqli_num_rows($res);
                // Check whether the category is available or not
                if($count > 0) {
                    // Categories Available
                    while($row = mysqli_fetch_assoc($res)) {
                        // Get the values like id, title, image_name
                        $id = $row['id'];
                        $title = $row['title'];
                        $image_name = $row['image_name'];
                        ?>

                        <a href="<?php echo SITEURL; ?>category-foods.php?category_id=<?php echo $id; ?>">
                            <div class="box-3 float-container">
                                <?php
                                    // Check whether Image is Available or Not 
                                    if($image_name == "") {
                                        // Image not Available Display Message
                                        echo "<div class='error'>Image Not Available.</div>"; 
                                    }else {
                                        // Image Available
                                        ?>
                                        <img src="<?php echo SITEURL; ?>images/category/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" class="img-responsive img-curve" width="300px">
                                        <?php
                                    }
                                ?>

                                <h3 class="float-text text-white"><?php echo $title; ?></h3>
                            </div>
                        </a>

                        <?php
                    }
                }else {

                }
            ?>





            <div class="clearfix"></div>
        </div>
    </section>
    <!-- Categories Section Ends Here -->


<?php include("../food-order/partials-front/footer.php"); ?>
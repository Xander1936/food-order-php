<?php include("../food-order/partials-front/menu.php"); ?>

    <?php
        // Check whether food id is set or not
        if(isset($_GET['food_id'])) {
            // Get the Food id and details of the selected food
            $food_id = $_GET['food_id'];

            // Get the details of the Selected Food
            $sql = "SELECT * FROM tbl_food WHERE id=$food_id";

            // Execute the Query
            $res = mysqli_query($conn, $sql);
            
            // Count the Rows
            $count = mysqli_num_rows($res);

            // Check whether the data is available or not
            if($count == 1) {
                // We have data
                // Get the Data from Database
                $row = mysqli_fetch_assoc($res);

                $title = $row['title'];
                $price = $row['price'];
                $image_name = $row['image_name'];

            }else {
                // Redirect to the Homepage
                header('location:'.SITEURL);
            }

        }else {
            // Redirect to homepage
            header('location:'.SITEURL);
        }
    ?>

    <!-- fOOD sEARCH Section Starts Here -->
    <section class="food-search">
        <div class="container">
            
            <h2 class="text-center text-white">Fill this form to confirm your order.</h2>

            <form action="#" class="order">
                <fieldset>
                    <legend>Selected Food</legend>

                    <div class="food-menu-img">
                        <?php 
                            // Check whether the i;age is available or not
                            if($image_name == "") {
                                // Image Not Available; just display the message
                                echo "<div class='error'>Image Not Available.</div>"; 
                            }else {
                                // Image is Available
                                ?>
                                <img src="<?php echo SITEURL; ?>/images/food/<?php echo $image_name; ?>" alt="" class="img-responsive img-curve" height="110px" >
                                <?php
                            }

                        ?>
                    </div>
    
                    <div class="food-menu-desc">
                        <h3><?php echo $title; ?></h3>
                        <p class="food-price">$<?php echo $price; ?></p>

                        <div class="order-label">Quantity</div>
                        <input type="number" name="qty" class="input-responsive" value="1" required>
                        
                    </div>

                </fieldset>
                
                <fieldset>
                    <legend>Delivery Details</legend>
                    <div class="order-label">Full Name</div>
                    <input type="text" name="full-name" placeholder="E.g. Vijay Thapa" class="input-responsive" required>

                    <div class="order-label">Phone Number</div>
                    <input type="tel" name="contact" placeholder="E.g. 9843xxxxxx" class="input-responsive" required>

                    <div class="order-label">Email</div>
                    <input type="email" name="email" placeholder="E.g. hi@vijaythapa.com" class="input-responsive" required>

                    <div class="order-label">Address</div>
                    <textarea name="address" rows="10" placeholder="E.g. Street, City, Country" class="input-responsive" required></textarea>

                    <input type="submit" name="submit" value="Confirm Order" class="btn btn-primary">
                </fieldset>

            </form>

        </div>
    </section>
    <!-- fOOD sEARCH Section Ends Here -->

<?php include("../food-order/partials-front/footer.php"); ?>
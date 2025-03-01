<?php

include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
}

$user_id = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- Header -->
<?php include 'components/user_header.php'; ?>

<div class="heading">
   <h3>Orders</h3>
   <p><a href="home.php">Home</a> <span> / Orders</span></p>
</div>

<section class="orders">

   <h1 class="title">Your Orders</h1>

   <div class="box-container">

   <?php
      if (!$user_id) {
         echo '<p class="empty">Please login to see your orders.</p>';
      } else {
         // Check database connection
         if (!isset($conn)) {
            die("<p class='empty'>Database connection error.</p>");
         }

         // Debugging: Check if user_id exists
         // echo "User ID: " . $user_id; 

         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);

         if ($select_orders->rowCount() > 0) {
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
               // Debugging: Check order data
               // var_dump($fetch_orders);
   ?>
   <div class="box">
      <p>Placed on: <span><?= htmlspecialchars($fetch_orders['placed_on']); ?></span></p>
      <p>Name: <span><?= htmlspecialchars($fetch_orders['name']); ?></span></p>
      <p>Email: <span><?= htmlspecialchars($fetch_orders['email']); ?></span></p>
      <p>Number: <span><?= htmlspecialchars($fetch_orders['number']); ?></span></p>
      <p>Address: <span><?= htmlspecialchars($fetch_orders['address']); ?></span></p>
      <p>Payment Method: <span><?= htmlspecialchars($fetch_orders['method']); ?></span></p>
      <p>Your Orders: <span><?= htmlspecialchars($fetch_orders['total_products']); ?></span></p>
      <p>Total Price: <span>$<?= htmlspecialchars($fetch_orders['total_price']); ?>/-</span></p>
      <p>Payment Status: 
         <span style="color: <?= ($fetch_orders['payment_status'] == 'pending') ? 'red' : 'green'; ?>">
            <?= htmlspecialchars($fetch_orders['payment_status']); ?>
         </span> 
      </p>
   </div>
   <?php
            }
         } else {
            echo '<p class="empty">No orders placed yet!</p>';
         }
      }
   ?>

   </div>

</section>

<!-- Footer -->
<?php include 'components/footer.php'; ?>

<!-- JavaScript -->
<script src="js/script.js"></script>

</body> 
</html>

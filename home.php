<?php

   include 'config.php';

   session_start();

   $admin_id = $_SESSION['admin_id']; //tạo session admin

   if(!isset($admin_id)){// session không tồn tại => quay lại trang đăng nhập
      header('location:login.php');
   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Trang quản trị</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="dashboard">

   <h1 class="title">Bảng thông tin</h1>

   <div class="box-container">

      <div class="box">
         <?php 
            $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
            $number_of_orders = mysqli_num_rows($select_orders);
         ?>
         <h3><?php echo $number_of_orders; ?></h3>
         <p>Đơn hàng</p>
      </div>

      <div class="box">
         <?php 
            $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
            $number_of_products = mysqli_num_rows($select_products);
         ?>
         <h3><?php echo $number_of_products; ?></h3>
         <p>Sản phẩm</p>
      </div>
      
      <div class="box">
         <?php 
            $select_cate = mysqli_query($conn, "SELECT * FROM `categorys`") or die('query failed');
            $number_of_cate = mysqli_num_rows($select_cate);
         ?>
         <h3><?php echo $number_of_cate; ?></h3>
         <p>Danh mục</p>
      </div>
      
      <div class="box">
         <?php 
            $select_suppliers = mysqli_query($conn, "SELECT * FROM `suppliers`") or die('query failed');
            $number_of_sup = mysqli_num_rows($select_suppliers);
         ?>
         <h3><?php echo $number_of_sup; ?></h3>
         <p>Nhà cung cấp</p>
      </div>

      <div class="box">
         <?php 
            $select_users = mysqli_query($conn, "SELECT * FROM `customers`") or die('query failed');
            $number_of_users = mysqli_num_rows($select_users);
         ?>
         <h3><?php echo $number_of_users; ?></h3>
         <p>Khách hàng</p>
      </div>

   </div>

</section>
<?php include 'footer.php'; ?>
<script src="js/admin_script.js"></script>

</body>
</html>
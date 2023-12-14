<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="uploaded_img/logo2.png">
   
   <title>Document</title>
   <style>
      .header .navbar .header_btn:hover{
         background-color: #005490;
         color: white;
         border-radius: 7px;
      }
   </style>
</head>
<body>
   <header class="header">

   <div style="max-width: 1400px; height: 70px; padding: 1rem 1rem;" class="flex">
      <a href=""><img  style="width: 38px; height:38px; position: relative; top: -2px; left: 32px;" src="uploaded_img/logo2.png" alt=""></a>
      <a style="text-decoration: none;" href="home.php" class="logo">Quản lý nhà kho</a>

      <nav style="min-height: unset; margin-bottom: 0px; width: 1050;" class="navbar">
         <a class="header_btn" style="margin: 0; padding: 6px; text-decoration: none;" href="home.php">Trang chủ</a>
         <a class="header_btn" style="margin: 0;padding: 6px;text-decoration: none;" href="admin_products.php">Sản phẩm</a>
         <a class="header_btn" style="margin: 0;padding: 6px;text-decoration: none;" href="admin_category.php">Danh mục</a>
         <a class="header_btn" style="margin: 0;padding: 6px;text-decoration: none;" href="admin_supplier.php">Nhà cung cấp</a>
         <a class="header_btn" style="margin: 0;padding: 6px;text-decoration: none;" href="admin_customers.php">Khách hàng</a>
         <a class="header_btn" style="margin: 0;padding: 6px;text-decoration: none;" href="admin_orders.php">Đơn hàng</a>
         <a class="header_btn" style="margin: 0;padding: 6px;text-decoration: none;" href="admin_shippingorders.php">Đơn vận chuyển</a>
         <a class="header_btn" style="margin: 0;padding: 6px;text-decoration: none;" href="admin_statistical.php">Thống kê</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="account-box">
         <p style="color: #005490;">Tên người dùng : <span style="color: #005490;"><?php echo $_SESSION['admin_name']; ?></span></p>
         <p style="color: #005490;">Email : <span style="color: #005490;"><?php echo $_SESSION['admin_email']; ?></span></p>
         <a style="background-color: #005490;"href="logout.php" class="delete-btn">Đăng xuất</a>
         <div><a style="color: #005490;" href="login.php">Đăng nhập</a> | <a style="color: #005490;" href="register.php">Đăng ký</a></div>
      </div>

   </div>

</header>
</body>
</html>

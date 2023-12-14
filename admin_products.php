<?php

include 'config.php';

class ProductManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
        session_start();
    }

    public function checkAdminSession() {
        $admin_id = $_SESSION['admin_id'];
        if (!isset($admin_id)) {
            header('location:login.php');
            exit();
        }
    }

    public function addProduct() {
        if (isset($_POST['add_product'])) {
            $name = mysqli_real_escape_string($this->conn, $_POST['name']);
            $trademark = mysqli_real_escape_string($this->conn, $_POST['trademark']);
            $supplier_id = $_POST['supplier'];
            $cate_id = $_POST['category'];
            $price = $_POST['price'];
            $discount = $_POST['discount'];
            $newprice = $price * (100 - $discount) / 100;
            $quantity = $_POST['quantity'];
            $initial_quantity = $_POST['quantity'];
            $describe = $_POST['describe'];
            $image = $_FILES['image']['name'];
            $image_size = $_FILES['image']['size'];
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_folder = 'uploaded_img/' . $image;

            $select_product_name = mysqli_query($this->conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

            if (mysqli_num_rows($select_product_name) > 0) {
                $message[] = 'Sản phẩm đã tồn tại.';
            } else {
                $add_product_query = mysqli_query($this->conn, "INSERT INTO `products`(name, trademark, cate_id, supplier_id, price, discount, newprice,quantity,initial_quantity, describes, image) VALUES('$name', '$trademark', '$cate_id', '$supplier_id', '$price', '$discount', '$newprice', '$quantity', '$initial_quantity', '$describe', '$image')") or die('query failed');

               //  if ($add_product_query) {
               //      if ($image_size > 2000000) {
               //          $message[] = 'Kích tước ảnh quá lớn, hãy cập nhật lại ảnh!';
               //      } else {
               //          move_uploaded_file($image_tmp_name, $image_folder);
               //          $message[] = 'Thêm sản phẩm thành công!';
               //      }
               //  } else {
               //      $message[] = 'Thêm sản phẩm không thành công!';
               //  }
            }
        }
    }

    public function deleteProduct() {
        if (isset($_GET['delete'])) {
            $delete_id = $_GET['delete'];
            try {
                $delete_image_query = mysqli_query($this->conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
               //  $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
                mysqli_query($this->conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
               //  unlink('uploaded_img/' . $fetch_delete_image['image']);
               //  $message[] = "Xóa sản phẩm thành công!";
            } catch (Exception $e) {
                echo "<script>
                        alert('Xóa sản phẩm không thành công do đã thêm vào đơn hàng');
                     </script>";
            // return 'Xóa nhà cung cấp không thành công';

                     
            }
        }
      
    }

    public function updateProduct() {
        if (isset($_POST['update_product'])) {
            $update_p_id = $_POST['update_p_id'];
            $update_name = $_POST['update_name'];
            $update_trademark = $_POST['update_trademark'];
            $update_category = $_POST['update_category'];
            $update_price = $_POST['update_price'];
            $update_discount = $_POST['update_discount'];
            $update_newprice = $update_price * (100 - $update_discount) / 100;
            $update_quantity = $_POST['update_quantity'];
            $update_describe = $_POST['update_describe'];

            mysqli_query($this->conn, "UPDATE `products` SET name = '$update_name', trademark = '$update_trademark', cate_id='$update_category', price = '$update_price', newprice='$update_newprice', discount='$update_discount', quantity='$update_quantity', describes='$update_describe' WHERE id = '$update_p_id'") or die('query failed');

            $update_image = $_FILES['update_image']['name'];
            $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
            $update_image_size = $_FILES['update_image']['size'];
            $update_folder = 'uploaded_img/' . $update_image;
            $update_old_image = $_POST['update_old_image'];

            if (!empty($update_image)) {
                if ($update_image_size > 2000000) {
                    $message[] = 'image file size is too large';
                } else {
                    mysqli_query($this->conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
                    move_uploaded_file($update_image_tmp_name, $update_folder);
                    unlink('uploaded_img/' . $update_old_image);
                }
            }

            header('location:admin_products.php');
        }
    }
}

$productManager = new ProductManager($conn);
$productManager->checkAdminSession();
$productManager->addProduct();
$productManager->deleteProduct();
$productManager->updateProduct();

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sản phẩm</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">
   <link rel="stylesheet" href="css/add.css">
   <link rel="icon" href="uploaded_img/logo2.png">

   <style>
      .search {
         display: flex;
         justify-content: center;
         align-items: center;
         margin-bottom: 12px;
      }
      .search input {
         padding: 10px 25px;
         width: 425px;
         margin-right: 10px;
         font-size: 18px;
         border-radius: 4px;
      }
      .btn {
         margin-top:  0px !important;
      }
   </style>
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="add-products">

<span style="color: #005490; font-weight: bold; display: flex; justify-content: center; font-size: 40px;">SẢN PHẨM</span>


   <form class="add_pr" action="" method="post" enctype="multipart/form-data">
      <h3>Thêm sản phẩm</h3>
      <input type="text" name="name" class="box" placeholder="Tên sản phẩm" required>
      <input type="text" name="trademark" class="box" placeholder="Thương hiệu" required>
      <label style="font-size: 16px;" for="">Chọn danh mục sản phẩm</label>
      <select name="category" class="box">
         <?php
            $select_category= mysqli_query($conn, "SELECT * FROM `categorys`") or die('Query failed');
            if(mysqli_num_rows($select_category)>0){
               while($fetch_category=mysqli_fetch_assoc($select_category)){
                  echo "<option value='" . $fetch_category['id'] . "'>".$fetch_category['name']."</option>";
               }
            }
            else{
               echo "<option>Không có thể loại nào.</option>";
            }
         ?>
      </select>
      <label style="font-size: 16px;" for="">Chọn nhà cung cấp</label>

      <select name="supplier" class="box">
         <?php
            $select_supplier= mysqli_query($conn, "SELECT * FROM `suppliers`") or die('Query failed');
            if(mysqli_num_rows($select_supplier)>0){
               while($fetch_supplier=mysqli_fetch_assoc($select_supplier)){
                  echo "<option value='" . $fetch_supplier['id'] . "'>".$fetch_supplier['name']."</option>";
               }
            }
            else{
               echo "<option>Không có nhà cung cấp nào.</option>";
            }
         ?>
      <input type="number" min="0" name="price" class="box" placeholder="Giá sản phẩm" required>
      <input type="number" min="0" name="discount" class="box" placeholder="% giảm giá" required>
      <input type="number" min="1" name="quantity" class="box" placeholder="Số lượng" required>
      <input type="text" name="describe" class="box" placeholder="Mô tả" required>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <input style="background-color: #005490;" onclick="added_pr()" type="submit" value="Thêm" name="add_product" class="btn added_pr">
      <!-- <input onclick="cancel_added_pr()" type="submit" value="Hủy" name="" class="btn cancel_add"> -->
   </form>

</section>
<form class="search" method="GET">
        <input type="text" name="search" placeholder="Nhập tên sản phẩm cần tìm..." value="<?php if(isset($_GET['search'])) echo $_GET['search'] ?>">
        <button style="background-color: #005490;" type="submit" class="btn">Tìm kiếm</button>
</form>
<!-- <button onclick="active_sup()" id="btn-sup" style="margin-bottom: 10px; margin-left: 120px; padding: 8px; font-size: 16px;" class="btn btn-info" >Thêm mới</button> -->

<button onclick="addActive()" class="btn btn-info" style="margin-bottom: 10px; margin-left: 56px; padding: 8px; font-size: 16px; background-color: #005490;">Thêm mới</button>
<section class="show-products">

   <div class="box-container" style="display: grid;
    grid-template-columns: repeat(4, 30rem);
    justify-content: center;
    gap: 1.5rem;
    max-width: 1200px;
    margin: 0 auto;
    align-items: flex-start;">
   <?php if(isset($_GET['search'])) {  ?>
      <?php
         $search = isset($_GET['search']) ? $_GET['search'] : '';
         $sql = mysqli_query($conn, "SELECT * FROM products WHERE name LIKE '%$search%'");
            if(mysqli_num_rows($sql) > 0){
               while ($row = mysqli_fetch_array($sql)) {
         ?>
            <div style="height: -webkit-fill-available;" class="box">
                  <img style="border-radius: 4px;" src="uploaded_img/<?php echo $row['image']; ?>" alt="">
                  <div class="name"><?php echo $row['name']; ?></div>
                  <div class="sub-name">Thương hiệu: <?php echo $row['trademark']; ?></div>
                  <?php
                  $cate_id =  $row['cate_id'];
                      $result= mysqli_query($conn, "SELECT * FROM `categorys` WHERE id = $cate_id") or die('Query failed');
                      $cate_name = mysqli_fetch_assoc($result)
                   ?>
                  <div class="sub-name">Danh mục: <?php echo $cate_name['name']; ?></div>
                  <?php
                  $supplier_id =  $row['supplier_id'];
                      $result= mysqli_query($conn, "SELECT * FROM `suppliers` WHERE id = $supplier_id") or die('Query failed');
                      $sup_name = mysqli_fetch_assoc($result)
                   ?>
                  <div class="sub-name">Nhà cung cấp: <?php echo $sup_name['name']; ?></div>
                  <div class="sub-name">Mô tả: <?php echo $row['describes']; ?></div>
                  <div class="price"><span style="text-decoration-line: line-through"><?php echo number_format($row['price'],0,',','.'  ); ?></span> VND (Giảm giá: <?php echo $row['discount']; ?>%)</div>
                  <div class="price"><?php echo number_format($row['newprice'],0,',','.' );; ?> VND (SL: <?php echo $row['quantity']; ?>)</div>
                  <a href="admin_products.php?update=<?php echo $row['id']; ?>" class="option-btn">Cập nhật</a>
                  <a href="admin_products.php?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Xóa sản phẩm này?');">Xóa</a>
               </div>
         <?php
               }
         } else {
            echo '<p style="font-size: 25px;">Không có sản phẩm phù hợp với yêu cầu tìm kiếm của bạn</p>';
         }
         ?>
   <?php  } else { ?>
      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
               <div style="height: -webkit-fill-available;" class="box">
                  <img style="border-radius: 4px;" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                  <div class="name"><?php echo $fetch_products['name']; ?></div>
                  <div class="sub-name">Thương hiệu: <?php echo $fetch_products['trademark']; ?></div>
                  <?php
                  $cate_id =  $fetch_products['cate_id'];
                      $result= mysqli_query($conn, "SELECT * FROM `categorys` WHERE id = $cate_id") or die('Query failed');
                      $cate_name = mysqli_fetch_assoc($result)
                   ?>
                  <div class="sub-name">Danh mục: <?php echo $cate_name['name']; ?></div>
                  <?php
                  $supplier_id =  $fetch_products['supplier_id'];
                      $result= mysqli_query($conn, "SELECT * FROM `suppliers` WHERE id = $supplier_id") or die('Query failed');
                      $sup_name = mysqli_fetch_assoc($result)
                   ?>
                  <div class="sub-name">Nhà cung cấp: <?php echo $sup_name['name']; ?></div>
                  <div class="sub-name">Mô tả: <?php echo $fetch_products['describes']; ?></div>
                  <div class="price"><span style="text-decoration-line: line-through"><?php echo number_format($fetch_products['price'],0,',','.'  ); ?></span> VND (Giảm giá: <?php echo $fetch_products['discount']; ?>%)</div>
                  <div class="price"><?php echo number_format($fetch_products['newprice'],0,',','.' );; ?> VND (SL: <?php echo $fetch_products['quantity']; ?>)</div>
                  <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">Cập nhật</a>
                  <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Xóa sản phẩm này?');">Xóa</a>
               </div>
            <?php
            }
            }else{
               echo '<p class="empty">Không có sản phẩm nào được thêm!</p>';
            }
            ?>
   <?php } ?>
   </div>

</section>

<section class="edit-product-form" style="height:650px">

   <?php
      if(isset($_GET['update'])){//hiện form update từ onclick <a></a> href='update'
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
               <form action="" method="post" enctype="multipart/form-data">
                  <input style="margin: 4px 0;" style="margin: 4px 0;" type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
                  <input style="margin: 4px 0;" type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
                  <input style="margin: 4px 0;" type="hidden" name="update_trademark" value="<?php echo $fetch_update['trademark']; ?>">
                  <input style="margin: 4px 0;" type="hidden" name="update_supplier" value="<?php echo $fetch_update['supplier_id']; ?>">
                  <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
                  <input style="margin: 4px 0;" type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="Tên sản phẩm">
                  <select style="margin: 4px 0;" name="update_category" class="box">
                  <?php
                  $cate_id =  $fetch_update['cate_id'];
                      $result= mysqli_query($conn, "SELECT * FROM `categorys` WHERE id = $cate_id") or die('Query failed');
                      $cate_name = mysqli_fetch_assoc($result)
                   ?>
                     <option value="<?php echo $cate_name['id']?>"><?=$cate_name['name']?></option>
                     <?php
                        $select_category= mysqli_query($conn, "SELECT * FROM `categorys`") or die('Truy vấn lỗi');
                        while($fetch_category=mysqli_fetch_assoc($select_category)){
                           if($fetch_category['name']!=$fetch_update['category']){
                              echo"<option  value='" . $fetch_category['id'] . "'>".$fetch_category['name']."</option>";
                           }
                        }
                     ?>
                  </select>
                  <input style="margin: 4px 0;" type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="Giá sản phẩm">
                  <input style="margin: 4px 0;" type="number" name="update_quantity" value="<?php echo $fetch_update['quantity']; ?>" min="0" class="box" required placeholder="Số lượng sản phẩm">
                  <input style="margin: 4px 0;" type="text" name="update_describe" value="<?php echo $fetch_update['describes']; ?>" class="box" required placeholder="Mô tả">
                  <input style="margin: 4px 0; background-color: #005490;" type="submit" value="update" name="update_product" class="btn">
                  <input style="margin: 4px 0; background-color: #005490" type="reset" value="cancel" id="close-update" class="option-btn">
               </form>
   <?php
            }
         }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>

</section>
<?php include 'footer.php'; ?>

<script src="js/admin_script.js"></script>
<script src="js/add.js" ></script>

</body>
</html>
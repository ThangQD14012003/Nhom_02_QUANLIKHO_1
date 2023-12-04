<?php

   include 'config.php';

   session_start();

   $admin_id = $_SESSION['admin_id']; //tạo session admin

   if(!isset($admin_id)){// session không tồn tại => quay lại trang đăng nhập
      header('location:login.php');
   }

   if(isset($_POST['add_category'])){//Thêm loại sách vào danh mục từ submit có name='add_category'

      $name = mysqli_real_escape_string($conn, $_POST['name']);
      $describes= mysqli_real_escape_string($conn, $_POST['describes']);

      $select_category_name = mysqli_query($conn, "SELECT name FROM categorys WHERE name = '$name'") or die('query failed');//truy vấn để kiểm tra loại sách đã tồn tại chưa

      if(mysqli_num_rows($select_category_name) > 0){// tồn tại rồi thì thông báo
         $message[] = 'Danh mục đã tồn tại.';
      }else{//chưa tồn tại thì thêm mới
         $add_category_query = mysqli_query($conn, "INSERT INTO `categorys`(name, describes) VALUES('$name', '$describes')") or die('query failed');

         if($add_category_query){
         $message[] = 'Thêm danh mục thành công!';
         }else{
            $message[] = 'Không thể thêm danh mục này!';
         }
      }
   }

   if(isset($_GET['delete'])){//Xóa loại sách từ onclick <a></a> có href='delete'
      $delete_id = $_GET['delete'];
      try {
         mysqli_query($conn, "DELETE FROM categorys WHERE id = '$delete_id'") or die('query failed');
         $message[] = "Xóa danh mục thành công";
      } catch (Exception) {
         $message[] = "Xóa danh mục không thành công";

      }
      
   }
   if(isset($_POST['update_category'])){//Cập nhật loại sách vào danh mục từ submit có name='update_category'

      $update_p_id = $_POST['update_p_id'];
      $update_name = $_POST['update_name'];
      $update_describes = $_POST['update_describes'];

      mysqli_query($conn, "UPDATE categorys SET name = '$update_name', describes = '$update_describes' WHERE id = '$update_p_id'") or die('query failed');

      header('location:admin_category.php');

   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Danh mục</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
   <link rel="stylesheet" href="css/admin_style.css">
   <style>
      table {
         font-size: 15px;
      }
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

   <h1 class="title">Danh mục sản phẩm</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Thêm danh mục</h3>
      <input type="text" name="name" class="box" placeholder="Danh mục" required>
      <input type="text" name="describes" class="box" placeholder="Mô tả" required>
      <input type="submit" value="Thêm danh mục " name="add_category" class="btn">
   </form>

</section>



<section class="show-products">

   <div class="container">
   <?php if(isset($_GET['search'])) {  ?>
      <table class="table table-striped">
         <thead>
            <tr>
               <th scope="col">ID</th>
               <th scope="col">Tên danh mục</th>
               <th scope="col">Mô tả</th>
               <th scope="col">Thao tác</th>
            </tr>
         </thead>
         <tbody>
         
      <table class="table table-striped">
         <thead>
            <tr>
               <th scope="col">ID</th>
               <th scope="col">Tên danh mục</th>
               <th scope="col">Mô tả</th>
               <th scope="col">Thao tác</th>
            </tr>
         </thead>
         <tbody>
         <?php
            $select_categories = mysqli_query($conn, "SELECT * FROM categorys") or die('query failed');
            while($fetch_cate = mysqli_fetch_assoc($select_categories)){
         ?>
            <tr>
               <th scope="row"><?php echo $fetch_cate['id']; ?></th>
               <td><?php echo $fetch_cate['name']; ?></td>
               <td><?php echo $fetch_cate['describes']; ?></td>
               <td>
                  <a href="admin_category.php?update=<?php echo $fetch_cate['id']; ?>" class="">Sửa</a> | 
                  <a href="admin_category.php?delete=<?php echo $fetch_cate['id']; ?>" class="" onclick="return confirm('Xóa danh mục này?');">Xóa</a>
               </td>
            </tr>
         <?php
            }
         ?>
         </tbody>
      </table>
   <?php } ?>
   </div>

</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){//Hiện form cập nhật thông tin loại sách từ <a></a> có href='update'
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM categorys WHERE id = '$update_id'") or die('query failed');//lấy ra thông tin loại sách cần cập nhật
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
               <form action="" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
                  <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="Tên">
                  <input type="text" name="update_describes" value="<?php echo $fetch_update['describes']; ?>" class="box" required placeholder="Mô tả">
                  <input type="submit" value="Cập nhật" name="update_category" class="btn"> <!-- submit form cập nhật -->
                  <input type="reset" value="Hủy"  onclick="window.location.href = 'admin_category.php'" class="btn">
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

</body>
</html>
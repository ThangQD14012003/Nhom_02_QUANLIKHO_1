<?php

   include 'config.php';

   session_start();

   $admin_id = $_SESSION['admin_id']; //tạo session admin

   if(!isset($admin_id)){// session không tồn tại => quay lại trang đăng nhập
      header('location:login.php');
   }

   if(isset($_POST['add_supplier'])){//thêm sách mới từ submit form name='add_product'

      $name = mysqli_real_escape_string($conn, $_POST['name']);
      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $address = mysqli_real_escape_string($conn, $_POST['address']);
      $phone = $_POST['phone'];

      $add_supplier_query = mysqli_query($conn, "INSERT INTO `suppliers`(name, email, address, phone) VALUES('$name', '$email', '$address', '$phone')") or die('query failed');

      if($add_supplier_query){
            $message[] = 'Thêm nhà cung cấp thành công!';
      }else{
         $message[] = 'Thêm nhà cung cấp không thành công!';
      }
   }

   if(isset($_GET['delete'])){//xóa nhà cung cấp từ onclick href='delete'
      $delete_id = $_GET['delete'];
      try {
         mysqli_query($conn, "DELETE FROM suppliers WHERE id = '$delete_id'") or die('query failed');

         $message[] = 'Xóa nhà cung cấp thành công';
      } catch ( Exception) {
         $message[] = 'Xóa nhà cung cấp không thành công';
      }
   }

   if(isset($_POST['update_supplier'])){//cập nhật nhà cung cấp từ form submit name='update_supplier'

      $update_s_id = $_POST['update_s_id'];
      $update_name = $_POST['update_name'];
      $update_email = $_POST['update_email'];
      $update_phone = $_POST['update_phone'];
      $update_address = $_POST['update_address'];

      mysqli_query($conn, "UPDATE suppliers SET name = '$update_name', email = '$update_email', phone = '$update_phone', address = '$update_address' WHERE id = '$update_s_id'") or die('query failed');
      header('location:admin_supplier.php');

   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Nhà cung cấp</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
   <link rel="stylesheet" href="./css/style_admin.css">
   <style>
      table {
         font-size: 15px;
      }
      .title {
         margin-top: 5px;
      }
      .box-item {
         margin:1rem 0;
         padding:1.2rem 1.4rem;
         border:var(--border);
         border-radius: .5rem;
         background-color: var(--light-bg);
         font-size: 1.8rem;
         color:var(--black);
         width: 100%;
      }
    .edit-supplier-form{
        min-height: 100vh;
        background-color: rgba(0,0,0,.7);
        display: flex;
        align-items: center;
        justify-content: center;
        padding:2rem;
        overflow-y: scroll;
        position: fixed;
        top:0; left:0; 
        z-index: 1200;
        width: 100%;
    }

    .edit-supplier-form form{
        width: 50rem;
        padding:2rem;
        text-align: center;
        border-radius: .5rem;
        background-color: var(--white);
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
<h1 class="title"> Danh sách nhà cung cấp </h1>

<section class="add-products">
   <form action="" method="post" enctype="multipart/form-data">
        <h3>Thêm nhà cung cấp</h3>
        <input type="text" name="name" class="box-item" placeholder="Tên nhà cung cấp" required>
        <input type="text" name="email" class="box-item" placeholder="Email" required>
        <input type="number" name="phone" class="box-item" placeholder="Số điện thoại" required>
        <input type="text" name="address" class="box-item" placeholder="Địa chỉ" required>
        <input type="submit" value="Thêm" name="add_supplier" class="btn">
   </form>
</section>

<section class="users">

   <div class="container">
   
      <table class="table table-striped">
         <thead>
            <tr>
               <th scope="col">ID</th>
               <th scope="col">Tên</th>
               <th scope="col">Email</th>
               <th scope="col">Địa chỉ</th>
               <th scope="col">Số điện thoại</th>
               <th scope="col">Thao tác</th>
            </tr>
         </thead>
         <tbody>
         <?php
            $select_suppliers = mysqli_query($conn, "SELECT * FROM suppliers") or die('query failed');
            while($fetch_suppliers = mysqli_fetch_assoc($select_suppliers)){
         ?>
            <tr>
               <th scope="row"><?php echo $fetch_suppliers['id']; ?></th>
               <td><?php echo $fetch_suppliers['name']; ?></td>
               <td><?php echo $fetch_suppliers['email']; ?></td>
               <td><?php echo $fetch_suppliers['address']; ?></td>
               <td><?php echo $fetch_suppliers['phone']; ?></td>
               <td>
                  <a href="admin_supplier.php?update=<?php echo $fetch_suppliers['id']; ?>" class="">Sửa</a> | 
                  <a href="admin_supplier.php?delete=<?php echo $fetch_suppliers['id']; ?>" class="" onclick="return confirm('Xóa nhà cung cấp này?');">Xóa</a>
               </td>
            </tr>
         <?php
            }
         ?>
         </tbody>
      </table>
    <?php  ?>
   </div>

</section>
<section class="edit-supplier-form">

   <?php
      if(isset($_GET['update'])){//hiện form update từ onclick <a></a> href='update'
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM suppliers WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
               <form action="" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="update_s_id" value="<?php echo $fetch_update['id']; ?>">
                  <input type="text" name="update_name" class="box-item" value="<?php echo $fetch_update['name'] ?>" placeholder="Tên nhà cung cấp" required>
                  <input type="text" name="update_email" class="box-item" value="<?php echo $fetch_update['email']?>" placeholder="Email" required>
                  <input type="number" name="update_phone" class="box" value="<?php echo $fetch_update['phone']?>" placeholder="Số điện thoại" required>
                  <input type="text" name="update_address" class="box" value="<?php echo $fetch_update['address']?>" placeholder="Địa chỉ" required>
                  <input type="submit" value="update" name="update_supplier" class="btn btn-primary">
                  <input type="reset" value="cancel" id="close-update-supplier" class="btn btn-warning">
               </form>
   <?php
            }
         }
      }else{
         echo '<script>document.querySelector(".edit-supplier-form").style.display = "none";</script>';
      }
   ?>

</section>

<?php include 'footer.php'; ?>

<script>
   document.querySelector('#close-update-supplier').onclick = () =>{
      document.querySelector('.edit-supplier-form').style.display = 'none';
      window.location.href = 'admin_supplier.php';
}
</script>


<script src="js/admin_script.js"></script>

</body>
</html>
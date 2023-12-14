<?php

include 'config.php';

class Supplier
{
    private $id;
    private $name;
    private $email;
    private $address;
    private $phone;

    public function __construct($id, $name, $email, $address, $phone)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->address = $address;
        $this->phone = $phone;
    }
}

class SupplierController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
        session_start();
        $this->checkAdminSession();
    }

    private function checkAdminSession()
    {
        $admin_id = $_SESSION['admin_id'];
        if (!isset($admin_id)) {
            header('location:login.php');
            exit;
        }
    }

    public function addSupplier($name, $email, $address, $phone)
    {
        $name = mysqli_real_escape_string($this->conn, $name);
        $email = mysqli_real_escape_string($this->conn, $email);
        $address = mysqli_real_escape_string($this->conn, $address);

        $query = mysqli_prepare($this->conn, "INSERT INTO `suppliers` (name, email, address, phone) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($query, "ssss", $name, $email, $address, $phone);
        $add_supplier_query = mysqli_stmt_execute($query);

        return $add_supplier_query ? 'Thêm nhà cung cấp thành công!' : 'Thêm nhà cung cấp không thành công!';
    }

    public function deleteSupplier($delete_id)
    {
        $delete_id = mysqli_real_escape_string($this->conn, $delete_id);

        try {
            $query = mysqli_prepare($this->conn, "DELETE FROM `suppliers` WHERE id = ?");
            mysqli_stmt_bind_param($query, "i", $delete_id);
            mysqli_stmt_execute($query);
        } catch (Exception $e) {
            // return 'Xóa nhà cung cấp không thành công';
            echo 
            "<script>
            alert('Xóa nhà cung cấp không thành công');
            </script>";
        }
    }

    public function updateSupplier($update_s_id, $update_name, $update_email, $update_phone, $update_address)
    {
        $update_name = mysqli_real_escape_string($this->conn, $update_name);
        $update_email = mysqli_real_escape_string($this->conn, $update_email);
        $update_address = mysqli_real_escape_string($this->conn, $update_address);

        $query = mysqli_prepare($this->conn, "UPDATE `suppliers` SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
        mysqli_stmt_bind_param($query, "ssssi", $update_name, $update_email, $update_phone, $update_address, $update_s_id);
        mysqli_stmt_execute($query);

        header('location:admin_supplier.php');
    }

}

$supplierController = new SupplierController($conn);

if (isset($_POST['add_supplier'])) {
    $message[] = $supplierController->addSupplier($_POST['name'], $_POST['email'], $_POST['address'], $_POST['phone']);
}

if (isset($_GET['delete'])) {
    $message[] = $supplierController->deleteSupplier($_GET['delete']);
}

if (isset($_POST['update_supplier'])) {
    $supplierController->updateSupplier($_POST['update_s_id'], $_POST['update_name'], $_POST['update_email'], $_POST['update_phone'], $_POST['update_address']);
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
   <link rel="stylesheet" href="css/add.css">
   <link rel="icon" href="uploaded_img/logo2.png">

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
    .fixx {
      background-color: #f39c12;
      padding: 5px;
      border-radius: 6px;
      color: white;
      text-decoration: none;
    }
    .fixxx {
      background-color: #c0392b;
      padding: 5px;
      border-radius: 6px;
      color: white;
      text-decoration: none;
    }
   </style>
</head>
<body>
   
<?php include 'admin_header.php'; ?>
<span style="color: #005490;padding-top: 24px; font-weight: bold; display: flex; justify-content: center; font-size: 40px;">NHÀ CUNG CẤP</span>


<section class="add-products" style="padding: 1rem 2rem;">
   <form class="add_sup" action="" method="post" enctype="multipart/form-data">
        <h3>Thêm nhà cung cấp</h3>
        <input type="text" name="name" class="box-item" placeholder="Tên nhà cung cấp" required>
        <input type="text" name="email" class="box-item" placeholder="Email" required>
        <input type="number" name="phone" class="box-item" placeholder="Số điện thoại" required>
        <input type="text" name="address" class="box-item" placeholder="Địa chỉ" required>
        <input style="background-color: #005490;" onclick="added_pr()" type="submit" value="Thêm" name="add_supplier" class="btn added_pr">
   </form>
</section>
<form class="search" method="GET">
        <input type="text" name="search" placeholder="Nhập tên nhà cung cấp cần tìm..." value="<?php if(isset($_GET['search'])) echo $_GET['search'] ?>">
        <button style="background-color: #005490;" type="submit" class="btn">Tìm kiếm</button>
</form>
<button onclick="active_sup()" id="btn-sup" style="margin-bottom: 10px;
    margin-left: 120px;
    padding: 5px;
    font-size: 16px;
    background-color: #005490;" class="btn btn-info" >Thêm mới</button>
<section class="users" style="padding: 1rem 0rem 3rem">

   <div class="container" >
   <?php if(isset($_GET['search'])) {  ?>
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
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $sql = mysqli_query($conn, "SELECT * FROM suppliers WHERE name LIKE '%$search%'");
               if(mysqli_num_rows($sql) > 0){
                  while ($row = mysqli_fetch_array($sql)) {
             ?>
            <tr>
               <th scope="row"><?php echo $row['id']; ?></th>
               <td><?php echo $row['name']; ?></td>
               <td><?php echo $row['email']; ?></td>
               <td><?php echo $row['address']; ?></td>
               <td><?php echo $row['phone']; ?></td>
               <td>
                  <a style="text-decoration: none;" href="admin_supplier.php?update=<?php echo $row['id']; ?>" class="fixx">Sửa</a> |
                  <a style="text-decoration: none;" href="admin_supplier.php?delete=<?php echo $row['id']; ?>" class="fixxx" onclick="return confirm('Xóa khách hàng này?');">Xóa</a>
               </td>
            </tr>
         <?php
                  }
            } else {
               echo "<tr>"; echo "<td colspan=6 align=center>"; echo '<p style="font-size: 25px;">Không có nhà cung cấp phù hợp với yêu cầu tìm kiếm của bạn</p>'; echo "</td>"; echo "</tr>";
            }
         ?>
         </tbody>
      </table>
    <?php  } else { ?>
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
            $select_suppliers = mysqli_query($conn, "SELECT * FROM `suppliers`") or die('query failed');
            while($fetch_suppliers = mysqli_fetch_assoc($select_suppliers)){
         ?>
            <tr>
               <th scope="row"><?php echo $fetch_suppliers['id']; ?></th>
               <td><?php echo $fetch_suppliers['name']; ?></td>
               <td><?php echo $fetch_suppliers['email']; ?></td>
               <td><?php echo $fetch_suppliers['address']; ?></td>
               <td><?php echo $fetch_suppliers['phone']; ?></td>
               <td>
                  <a href="admin_supplier.php?update=<?php echo $fetch_suppliers['id']; ?>" class="fixx">Sửa</a> |
                  <a href="admin_supplier.php?delete=<?php echo $fetch_suppliers['id']; ?>" class="fixxx" onclick="return confirm('Xóa nhà cung cấp này?');">Xóa</a>
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
<section class="edit-supplier-form">

   <?php
      if(isset($_GET['update'])){//hiện form update từ onclick <a></a> href='update'
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `suppliers` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
               <form action="" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="update_s_id" value="<?php echo $fetch_update['id']; ?>">
                  <input type="text" name="update_name" class="box-item" value="<?php echo $fetch_update['name'] ?>" placeholder="Tên nhà cung cấp" required>
                  <input type="text" name="update_email" class="box-item" value="<?php echo $fetch_update['email']?>" placeholder="Email" required>
                  <input type="number" name="update_phone" class="box-item" value="<?php echo $fetch_update['phone']?>" placeholder="Số điện thoại" required>
                  <input type="text" name="update_address" class="box-item" value="<?php echo $fetch_update['address']?>" placeholder="Địa chỉ" required>
                  <input style="background-color: #005490;" type="submit" value="update" name="update_supplier" class="btn btn-primary">
                  <input style="background-color: #005490;" type="reset" value="cancel" id="close-update-supplier" class="btn btn-warning">
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
<script src="js/add.js" ></script>
</body>
</html>

<?php

include 'config.php';

class ShippingOrderManager
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
        session_start();
    }

    public function checkAdminSession()
    {
        $admin_id = $_SESSION['admin_id'];
        if (!isset($admin_id)) {
            header('location:login.php');
            exit();
        }
    }

    public function addShippingOrder()
    {
        if (isset($_POST['add_shippingorder'])) {
            $orders_id = $_POST['orders_id'];
            $name = mysqli_real_escape_string($this->conn, $_POST['name']);
            $phone = mysqli_real_escape_string($this->conn, $_POST['phone']);
            $address = mysqli_real_escape_string($this->conn, $_POST['address']);
            $select_order = mysqli_query($this->conn, "SELECT * FROM `orders` WHERE id = $orders_id") or die('Query failed');
            $fetch_order = mysqli_fetch_assoc($select_order);
            $total_price = $fetch_order['total_price'];
            $so_status = "Đang vận chuyển";

            $add_shippingorder_query = mysqli_query($this->conn, "INSERT INTO `shippingorders`(orders_id, total_price, delivery_address, delivery_person, delivery_phone, so_status) VALUES('$orders_id', '$total_price', '$address', '$name', '$phone', '$so_status')") or die('query failed');

            // if ($add_shippingorder_query) {
            //     $message[] = 'Thêm đơn vận chuyển thành công!';
            // } else {
            //     $message[] = 'Thêm đơn vận chuyển không thành công !';
            // }
        }
    }

    public function updateShippingOrder()
    {
        if (isset($_POST['update_shippingorder'])) {
            $shippingorder_update_id = $_POST['update_s_id'];
            $update_order_id = $_POST['update_order_id'];
            $select_order = mysqli_query($this->conn, "SELECT * FROM `orders` WHERE id = $update_order_id") or die('Query failed');
            $fetch_order = mysqli_fetch_assoc($select_order);
            $update_total_price = $fetch_order['total_price'];
            $update_name = mysqli_real_escape_string($this->conn, $_POST['update_name']);
            $update_phone = mysqli_real_escape_string($this->conn, $_POST['update_phone']);
            $update_address = mysqli_real_escape_string($this->conn, $_POST['update_address']);
            $update_status = $_POST['update_status'];
            mysqli_query($this->conn, "UPDATE `shippingorders` SET orders_id = '$update_order_id', total_price = '$update_total_price', delivery_person = '$update_name', delivery_address = '$update_address', delivery_phone = '$update_phone', so_status = '$update_status' WHERE so_id = '$shippingorder_update_id'") or die('query failed');
            // $message[] = 'Đơn vận chuyển đã được cập nhật!';
            header('location:admin_shippingorders.php');
        }
    }

    public function deleteShippingOrder()
    {
        if (isset($_GET['delete'])) {
            $delete_id = $_GET['delete'];
            try {
                mysqli_query($this->conn, "DELETE FROM `shippingorders` WHERE so_id = '$delete_id'") or die('query failed');
               //  $message[] = "Xóa đơn vận chuyển thành công!";
            } catch (Exception) {
               //  $message[] = "Xóa đơn vận chuyển không thành công!";
            }
        }
    }
}

$shippingOrderManager = new ShippingOrderManager($conn);
$shippingOrderManager->checkAdminSession();
$shippingOrderManager->addShippingOrder();
$shippingOrderManager->updateShippingOrder();
$shippingOrderManager->deleteShippingOrder();

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đơn vận chuyển</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
   <link rel="stylesheet" href="css/admin_style.css">
   <link rel="stylesheet" href="css/add.css">
   <link rel="icon" href="uploaded_img/logo2.png">

   <style>
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
      .search {
         display: flex;
         justify-content: center;
         align-items: center;
         margin-bottom: 12px;
      }
      .search select {
         padding: 10px 10px;
         width: 100px;
         margin-right: 10px;
         font-size: 18px;
         border-radius: 4px;
      }
      .btn {
         margin-top:  0px !important;
      }
      
    .edit-shippingorder-form{
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
    
    .edit-shippingorder-form form{
        width: 50rem;
        padding:2rem;
        text-align: center;
        border-radius: .5rem;
        background-color: var(--white);
    } 
   </style>
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="orders">

<span style="color: #005490; font-weight: bold; display: flex; justify-content: center; font-size: 40px;">ĐƠN VẬN CHUYỂN</span>


   <section class="add-products" style="padding: 1rem 2rem;">
   <form class="add_sup" action="" method="post" enctype="multipart/form-data">
        <h3>Thêm đơn vận chuyển</h3>
        <label style="font-size: 16px;" for="">Chọn đơn hàng</label>
        <select name="orders_id" class="box">
         <?php
            $select_order= mysqli_query($conn, "SELECT * FROM `orders`") or die('Query failed');
            if(mysqli_num_rows($select_order)>0){
               while($fetch_order=mysqli_fetch_assoc($select_order)){
                  echo "<option value='" . $fetch_order['id'] . "'>".$fetch_order['id']."</option>";
               }
            }
            else{
               echo "<option>Không có đơn hàng nào</option>";
            }
         ?>
      </select>
      <input type="text" name="name" class="box" placeholder="Tên người vận chuyển" required>
      <input type="number" name="phone" class="box" placeholder="Số điện thoại" required>
      <input type="text" name="address" class="box" placeholder="Địa chỉ giao hàng" required>
      <input style="background-color: #005490;" type="submit" value="Thêm" name="add_shippingorder" class="btn">
   </form>
</section>
<!-- <label for="">Tìm kiếm theo id đơn hàng</label> -->
<form class="search" method="GET">
    <select name="order_id" class="box">
        <?php
        $select_order= mysqli_query($conn, "SELECT * FROM `orders`") or die('Query failed');
        if(mysqli_num_rows($select_order)>0){
            while($fetch_order=mysqli_fetch_assoc($select_order)){
                echo "<option value='" . $fetch_order['id'] . "'>".$fetch_order['id']."</option>";
            }
        }
        else{
            echo "<option>Không có đơn hàng nào</option>";
        }
        ?>
    </select>
        <button style="background-color: #005490;" type="submit" class="btn">Tìm kiếm</button>
</form>
<button onclick="active_sup()" id="btn-sup" style="margin-bottom: 10px;
    margin-left: 90px;
    padding: 5px;
    font-size: 16px;
    background-color: #005490;" class="btn btn-info" >Thêm mới
</button>
<div class="container" style="padding: 1rem 0rem 3rem">
   <?php if(isset($_GET['order_id'])) {  ?>
      <table class="table table-striped">
         <thead>
            <tr>
               <th scope="col">ID</th>
               <th scope="col">Order ID</th>
               <th scope="col">Tổng giá</th>
               <th scope="col">Tên người vận chuyển</th>
               <th scope="col">Địa chỉ</th>
               <th scope="col">Số điện thoại</th>
               <th scope="col">Trạng thái đơn</th>
               <th scope="col">Thao tác</th>
            </tr>
         </thead>
         <tbody>
         <?php
            $order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';
            $sql = mysqli_query($conn, "SELECT * FROM shippingorders WHERE orders_id  = '$order_id'");
               if(mysqli_num_rows($sql) > 0){
                  while ($row = mysqli_fetch_array($sql)) {
             ?>
            <tr>
            <th scope="row"><?php echo $row['so_id']; ?></th>
               <td><?php echo $row['orders_id']; ?></td>
               <td><?php echo $row['total_price']; ?></td>
               <td><?php echo $row['delivery_person']; ?></td>
               <td><?php echo $row['delivery_address']; ?></td>
               <td><?php echo $row['delivery_phone']; ?></td>
               <td><?php echo $row['so_status']; ?></td>
               <td>
                  <a style="text-decoration: none;" href="admin_shippingorders.php?update=<?php echo $row['so_id']; ?>" class="fixx">Sửa</a> | 
                  <a style="text-decoration: none;" href="admin_shippingorders.php?delete=<?php echo $row['so_id']; ?>" class="fixxx" onclick="return confirm('Xóa đơn vận chuyển này?');">Xóa</a>
               </td>
            </tr>
         <?php
                  }
            } else {
               echo "<tr>"; echo "<td colspan=6 align=center>"; echo '<p style="font-size: 25px;">Không có đơn vận chuyển phù hợp với yêu cầu tìm kiếm của bạn</p>'; echo "</td>"; echo "</tr>";
            }
         ?>
         </tbody>
      </table>
   <?php  } else { ?>
      <table class="table table-striped">
         <thead>
            <tr>
            <th scope="col">ID</th>
               <th scope="col">Order ID</th>
               <th scope="col">Tổng giá</th>
               <th scope="col">Tên người vận chuyển</th>
               <th scope="col">Địa chỉ giao hàng</th>
               <th scope="col">Số điện thoại</th>
               <th scope="col">Trạng thái đơn</th>
               <th scope="col">Thao tác</th>
            </tr>
         </thead>
         <tbody>
         <?php
            $select_shippingorders = mysqli_query($conn, "SELECT * FROM `shippingorders`") or die('query failed');
            while($fetch_shippingorders = mysqli_fetch_assoc($select_shippingorders)){
         ?>
            <tr>
               <th scope="row"><?php echo $fetch_shippingorders['so_id']; ?></th>
               <td><?php echo $fetch_shippingorders['orders_id']; ?></td>
               <td><?php echo $fetch_shippingorders['total_price']; ?> đ</td>
               <td><?php echo $fetch_shippingorders['delivery_person']; ?></td>
               <td><?php echo $fetch_shippingorders['delivery_address']; ?></td>
               <td><?php echo $fetch_shippingorders['delivery_phone']; ?></td>
               <td><?php echo $fetch_shippingorders['so_status']; ?></td>
               <td>
                  <a style="text-decoration: none;" href="admin_shippingorders.php?update=<?php echo $fetch_shippingorders['so_id']; ?>" class="fixx">Sửa</a> | 
                  <a style="text-decoration: none;" href="admin_shippingorders.php?delete=<?php echo $fetch_shippingorders['so_id']; ?>" class="fixxx" onclick="return confirm('Xóa đơn vận chuyển này?');">Xóa</a>
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
<section class="edit-shippingorder-form">

   <?php
      if(isset($_GET['update'])){//hiện form update từ onclick <a></a> href='update'
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `shippingorders` WHERE so_id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
               <form action="" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="update_s_id" value="<?php echo $fetch_update['so_id']; ?>">
                  <select name="update_order_id" class="box-item">
                    <?php
                        $orders_id =  $fetch_update['orders_id'];
                        $result= mysqli_query($conn, "SELECT * FROM `orders` WHERE id = $orders_id") or die('Query failed');
                        $orders = mysqli_fetch_assoc($result)
                    ?>
                     <option value="<?php echo $orders['id']?>"><?=$orders['id']?></option>
                    <?php
                        $select_order= mysqli_query($conn, "SELECT * FROM `orders`") or die('Query failed');
                        if(mysqli_num_rows($select_order)>0){
                        while($fetch_order=mysqli_fetch_assoc($select_order)){
                            echo "<option value='" . $fetch_order['id'] . "'>".$fetch_order['id']."</option>";
                        }
                        }
                        else{
                            echo "<option>Không có đơn hàng nào</option>";
                        }
                    ?>
                </select>
                <input type="text" name="update_name" value="<?php echo $fetch_update['delivery_person']; ?>" class="box-item" required placeholder="Tên người vận chuyển">
                <input type="text" name="update_phone" value="<?php echo $fetch_update['delivery_phone']; ?>" class="box-item" required placeholder="Số điện thoại">
                <input type="text" name="update_address" value="<?php echo $fetch_update['delivery_address']; ?>" class="box-item" required placeholder="Địa chỉ">
                  <select name="update_status" class="box-item">
                    <option <?php if($fetch_update['so_status'] == 'Đang vận chuyển') echo 'selected' ?> value="Đang vận chuyển">Đang vận chuyển</option>
                    <option <?php if($fetch_update['so_status'] == 'Hoàn thành') echo 'selected' ?> value="Hoàn thành">Hoàn thành</option>
                  </select>
                  <input style="background-color: #005490;" type="submit" value="update" name="update_shippingorder" class="btn btn-primary">
                  <input style="background-color: #005490;" type="reset" value="cancel" id="close-update-shipping" class="btn btn btn-warning">
               </form>
   <?php
            }
         }
      }else{
         echo '<script>document.querySelector(".edit-shippingorder-form").style.display = "none";</script>';
      }
   ?>

</section>
<?php include 'footer.php'; ?>
<script>
   document.querySelector('#close-update-shipping').onclick = () =>{
      document.querySelector('.edit-shippingorder-form').style.display = 'none';
      window.location.href = 'admin_shippingorders.php';
}
</script>
<script src="js/admin_script.js"></script>
<script src="js/add.js" ></script>
</body>
</html>
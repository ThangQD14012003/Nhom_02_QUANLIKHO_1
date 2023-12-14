<?php

include 'config.php';

class AdminOrderManager {
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

    public function addOrder() {
        if (isset($_POST['add_order'])) {
         $customer_id = $_POST['customer_id'];
         $product_id = $_POST['product_id'];
         $name = mysqli_real_escape_string($this->conn, $_POST['name']);
         $phone = mysqli_real_escape_string($this->conn, $_POST['phone']);
         $email = mysqli_real_escape_string($this->conn, $_POST['email']);
         $address = mysqli_real_escape_string($this->conn, $_POST['address']);
         $note = mysqli_real_escape_string($this->conn, $_POST['note']);
         $product_quantity = $_POST['quantity'];

    
         $select_product = mysqli_query($this->conn, "SELECT * FROM `products` WHERE id = $product_id") or die('Query failed');
         $fetch_product = mysqli_fetch_assoc($select_product);
         $product_name = $fetch_product['name'];
         $total_price = $fetch_product['newprice'] * $product_quantity;
         $placed_on = date('Y-m-d');
         $payment_status = "Chờ xác nhận";

    
         $add_order_query = mysqli_query($this->conn, "INSERT INTO `orders`(customer_id, product_id, name, phone, email, address, note, product_quantity, product_name, total_price, placed_on, payment_status) VALUES('$customer_id', '$product_id', '$name', '$phone', '$email', '$address', '$note', '$product_quantity','$product_name',  '$total_price', '$placed_on', '$payment_status')") or die('query failed');

         // if ($add_order_query) {
         //     $message[] = 'Thêm đơn hàng thành công!';
         // } else {
         //     $message[] = 'Thêm đơn hàng không thành công !';
         // }
        }
    }

    public function updateOrder() {
        if (isset($_POST['update_order'])) {
         $order_update_id = $_POST['order_id'];
         $update_payment = $_POST['update_payment'];
         mysqli_query($this->conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');
         // $message[] = 'Trạng thái đơn hàng đã được cập nhật!';   
        }
    }

    public function restoreOrder() {
        if (isset($_GET['return'])) {
         $return = $_GET['return'];
         $return_status = "Chờ xác nhận";
         $total_products= $_GET['product_name'];
         $products = explode(', ', $total_products);//tách riêng
      for($i=0; $i<count($products); $i++){
         $quantity = explode('-', $products[$i]);//tách với số lượng tương ứng cần hủy
         $nums = mysqli_query($this->conn, "SELECT * FROM `products` WHERE name = '$quantity[0]'");
         $res = mysqli_fetch_assoc($nums);
         $return_quantity = $res['quantity'] - $quantity[1];
         mysqli_query($this->conn, "UPDATE `products` SET quantity = '$return_quantity' WHERE name = '$quantity[0]' ");
      }
      mysqli_query($this->conn, "UPDATE `orders` SET payment_status = '$return_status' WHERE id = '$return'") or die('query failed');
      header('location:admin_orders.php');

        }
    }

    public function cancelOrder() {
        if (isset($_GET['cancel'])) {
         $cancel_id = $_GET['cancel'];
         $status = $_GET['status'];
         $total_products= $_GET['products'];
         if($status=="Chờ xác nhận"){
            $products = explode(', ', $total_products);//tách riêng từng sách
            for($i=0; $i<count($products); $i++){
               $quantity = explode('-', $products[$i]);//tách sách với số lượng tương ứng cần hủy
               $nums = mysqli_query($this->conn, "SELECT * FROM `products` WHERE name = '$quantity[0]'");
               $res = mysqli_fetch_assoc($nums);
               $return_quantity = $quantity[1]+$res['quantity'];
               mysqli_query($this->conn, "UPDATE `products` SET quantity = '$return_quantity' WHERE name = '$quantity[0]' ") or die('query failed');
            }
            $status = "Đã hủy";
            mysqli_query($this->conn, "UPDATE `orders` SET payment_status = '$status' WHERE id = '$cancel_id'") or die('query failed');
            header('location:admin_orders.php'); 
        }
    }
   }
    public function deleteOrder() {
        if (isset($_GET['delete'])) {
 $delete_id = $_GET['delete'];
      $status = $_GET['status'];
      try{
         if($status == "Đã hủy" || $status == "Đã xác nhận"){
         mysqli_query($this->conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
         header('location:admin_orders.php');
      }
      // else{
      //    $message[]="Không thể xóa đơn hàng đang trong quá trình xử lý!";
      // }
      }catch(Exception) {
         echo "<script>
         alert('Xóa đơn hàng không thành công');
      </script>";
      }
        }
    }
}

$adminOrderManager = new AdminOrderManager($conn);
$adminOrderManager->checkAdminSession();
$adminOrderManager->addOrder();
$adminOrderManager->updateOrder();
$adminOrderManager->restoreOrder();
$adminOrderManager->cancelOrder();
$adminOrderManager->deleteOrder();

?>


   <!DOCTYPE html>
   <html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Đơn hàng</title>

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
      </style>
   </head>
   <body>
      
   <?php include 'admin_header.php'; ?>

   <section class="orders">

      <!-- <h1 class="title">Đơn đặt hàng</h1> -->
      <span style="color: #005490; font-weight: bold; display: flex; justify-content: center; font-size: 40px;">ĐƠN ĐẶT HÀNG</span>

      <section class="add-products" style="padding: 2rem 2rem;">
      <form class="add_sup" action="" method="post" enctype="multipart/form-data">
         <h3>Thêm đơn hàng</h3>
         <label style="font-size: 18px;" for="">Chọn khách hàng</label>
         <select name="customer_id" class="box">
            <?php
               $select_customer= mysqli_query($conn, "SELECT * FROM `customers`") or die('Query failed');
               if(mysqli_num_rows($select_customer)>0){
                  while($fetch_customer=mysqli_fetch_assoc($select_customer)){
                     echo "<option value='" . $fetch_customer['id'] . "'>".$fetch_customer['name']."</option>";
                  }
               }
               else{
                  echo "<option>Không có khách hàng nào.</option>";
               }
            ?>
         </select>
         <label style="font-size: 18px;" for="">Chọn tên sản phẩm</label>
         <select name="product_id" class="box">
            <?php
               $select_product= mysqli_query($conn, "SELECT * FROM `products`") or die('Query failed');
               if(mysqli_num_rows($select_product)>0){
                  while($fetch_product=mysqli_fetch_assoc($select_product)){
                     echo "<option value='" . $fetch_product['id'] . "'>".$fetch_product['name']."</option>";
                  }
               }
               else{
                  echo "<option>Không có sản phẩm nào.</option>";
               }
            ?>
         </select>
         <input type="number" name="quantity" class="box" placeholder="Số lượng" required>
         <input type="text" name="name" class="box" placeholder="Tên người nhận" required>
         <input type="number" name="phone" class="box" placeholder="Số điện thoại" required>
         <input type="text" name="email" class="box" placeholder="Email" required>
         <input type="text" name="address" class="box" placeholder="Địa chỉ" required>
         <input type="text" name="note" class="box" placeholder="Ghi chú" required>
         <input style="background-color: #005490;" onclick="added_pr()" type="submit" value="Thêm" name="add_order" class="btn added_pr">
      </form>
   </section>
   <button onclick="active_sup()" id="btn-sup" style="margin-bottom: 10px;
      margin-left: 38px;
      padding: 10px;
      font-size: 16px;
      background-color: #005490;" class="btn btn-info" >Thêm mới</button>

      <div class="box-container" style="display: grid;
      grid-template-columns: repeat(4, 30rem);
      justify-content: center;
      gap: 1.5rem;
      max-width: 1200px;
      margin: 0 auto;
      align-items: flex-start;">
         
         <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
            if(mysqli_num_rows($select_orders) > 0){
               while($fetch_orders = mysqli_fetch_assoc($select_orders)){
         ?>
                  <div style="height: -webkit-fill-available;" class="box">
                     <p> Id đơn hàng : <span style="color: #005490"><?php echo $fetch_orders['id']; ?></span> </p>
                     <p> Id khách hàng : <span style="color: #005490"><?php echo $fetch_orders['customer_id']; ?></span> </p>
                     <p> Ngày đặt : <span style="color: #005490"><?php echo $fetch_orders['placed_on']; ?></span> </p>
                     <p> Tên : <span style="color: #005490"><?php echo $fetch_orders['name']; ?></span> </p>
                     <p> Số điện thoại : <span style="color: #005490"><?php echo $fetch_orders['phone']; ?></span> </p>
                     <p> Email : <span style="color: #005490"><?php echo $fetch_orders['email']; ?></span> </p>
                     <p> Địa chỉ : <span style="color: #005490"><?php echo $fetch_orders['address']; ?></span> </p>
                     <p> Ghi chú : <span style="color: #005490"><?php echo $fetch_orders['note']; ?></span> </p>
                     <p> Sản phẩm : <span style="color: #005490"><?php echo $fetch_orders['product_name']; ?></span> </p>
                     <p> Số lượng : <span style="color: #005490"><?php echo $fetch_orders['product_quantity']; ?></span> </p>
                     <p> Tổng giá : <span style="color: #005490"><?php echo number_format($fetch_orders['total_price'],0,',','.' ); ?> VND</span> </p>
                     <form action="" method="post">
                        <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
         <?php
                        if($fetch_orders['payment_status']=="Đã hủy"){
                           echo "<p class='empty' style='color:red'>Đã hủy đơn hàng này.</p>";
         ?>
                           <a href="admin_orders.php?return=<?=$fetch_orders['id']?>& products=<?=$fetch_orders['product_name']?>" onclick="return confirm('Khôi phục đơn hàng này?');" class="option-btn">Khôi phục</a>
         <?php
                        }else{
            ?>
                           <select name="update_payment" required>
                              <option value="" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
                              <!-- <option value="Chờ xác nhận">Chờ xác nhận</option> -->
                              <!-- <option value="Đã xác nhận">Đã xác nhận</option> -->
                              <option value="Đang xử lý">Đang xử lý</option>
                              <option value="Đã xác nhận">Đã xác nhận</option>
                           </select>
                           <input type="submit" value="Cập nhật" name="update_order" class="option-btn">
         <?php
                        }
         ?>
                        <!-- <a href="admin_orders.php?cancel=<?=$fetch_orders['id']?>& status=<?=$fetch_orders['payment_status']?>& products=<?=$fetch_orders['product_name']?>" onclick="return confirm('Hủy đơn hàng này?');" class="delete-btn">Hủy</a> -->
                        <a href="admin_orders.php?delete=<?=$fetch_orders['id']?>& status=<?=$fetch_orders['payment_status']?>" onclick="return confirm('Xóa đơn hàng này?');" class="delete-btn">Xóa</a>
                     </form>
                  </div>
         <?php
               }
            }else{
               echo '<p class="empty">Không có đơn đặt hàng nào!</p>';
            }
         ?>
         
      </div>
      

   </section>
   <?php include 'footer.php'; ?>

   <script src="js/admin_script.js"></script>
   <script src="js/add.js" ></script>
   </body>
   </html>
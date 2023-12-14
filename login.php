<?php

   include 'config.php';
   session_start();

   if(isset($_POST['submit'])){//lấy thông tin đăng nhập từ form submit name='submit'

      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

      $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

      if(mysqli_num_rows($select_users) > 0){//kiểm tra tài khoản có tồn tại không

         $row = mysqli_fetch_assoc($select_users);
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_id'] = $row['id'];
            header('location:home.php');

      }else{
         $message[] = 'Email hoặc mật khẩu không chính xác!';
      }

   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đăng nhập</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .check_email,
      .check_password {
         color: red;
         text-align: justify;
         margin-left: 5px;
         font-size: 15px;
      }
   </style>
</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>';
   }
}
?>
   
<div class="form-container">

   <form id="form" method="post">
      <h3>Đăng nhập</h3>
      <input type="text" name="email" placeholder="Email" class="box">
      <div class="check_email"></div>
      <input type="password" name="password" placeholder="Mật khẩu" class="box">
      <div class="check_password"></div>
      <input style="background-color: #005490;" type="submit" name="submit" value="Đăng nhập" class="btn">
      <p>Bạn chưa có tài khoản? <a style="color: #005490;" href="register.php">Đăng ký</a></p>
   </form>

</div>
<!-- Validate JS -->
<script>
  document.getElementById("form").addEventListener("submit", function(event) {
   var array = document.getElementsByTagName('input');
   var emailRegex = /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/;
   if (array[0].value == "") {
      document.querySelector('.check_email').innerHTML = "Email không được để trống !";
      event.preventDefault();
   } else if(!emailRegex.test(array[0].value)) {
      document.querySelector('.check_email').innerHTML = "Email sai định dạng !";
      event.preventDefault();
   } else {
      document.querySelector('.check_email').innerHTML = "";
   }
   if (array[1].value == "") {
      document.querySelector('.check_password').innerHTML = "Mật khẩu không được để trống !";
      event.preventDefault();
   } else if(array[1].value.length < 6) {
      document.querySelector('.check_password').innerHTML = "Mật khẩu phải lớn hơn hoặc bằng 6 ký tự !";
      event.preventDefault();
   }  else {
      document.querySelector('.check_password').innerHTML = "";
   }
  });
</script>
</body>
</html>
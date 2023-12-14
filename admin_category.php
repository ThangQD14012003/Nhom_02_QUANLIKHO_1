<?php
include 'config.php';

class CategoryManager
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function checkAdminSession()
    {
        session_start();
        $admin_id = $_SESSION['admin_id'];

        if (!isset($admin_id)) {
            header('location:login.php');
        }
    }

    public function addCategory($name, $describes)
    {
        $name = mysqli_real_escape_string($this->conn, $name);
        $describes = mysqli_real_escape_string($this->conn, $describes);

        $select_category_name = mysqli_query($this->conn, "SELECT name FROM `categorys` WHERE name = '$name'") or die('query failed');

        if (mysqli_num_rows($select_category_name) > 0) {
            // $message[] = 'Danh mục đã tồn tại.';
        } else {
            $add_category_query = mysqli_query($this->conn, "INSERT INTO `categorys`(name, describes) VALUES('$name', '$describes')") or die('query failed');

            // if ($add_category_query) {
            //     $message[] = 'Thêm danh mục thành công!';
            // } else {
            //     $message[] = 'Không thể thêm danh mục này!';
            // }
        }
    }

    public function deleteCategory($delete_id)
    {
        try {
            mysqli_query($this->conn, "DELETE FROM `categorys` WHERE id = '$delete_id'") or die('query failed');
            // $message[] = "Xóa danh mục thành công";
        } catch (Exception $e) {
         echo "<script>
         alert('Xóa danh mục không thành công');
      </script>";
        }
    }

    public function updateCategory($update_p_id, $update_name, $update_describes)
    {
        $update_name = mysqli_real_escape_string($this->conn, $update_name);
        $update_describes = mysqli_real_escape_string($this->conn, $update_describes);

        mysqli_query($this->conn, "UPDATE `categorys` SET name = '$update_name', describes = '$update_describes' WHERE id = '$update_p_id'") or die('query failed');

        header('location:admin_category.php');
    }

    public function getCategoryList($search = '')
    {
        $search = mysqli_real_escape_string($this->conn, $search);
        $sql = mysqli_query($this->conn, "SELECT * FROM categorys WHERE name LIKE '%$search%'");

        $categories = [];

        if (mysqli_num_rows($sql) > 0) {
            while ($row = mysqli_fetch_assoc($sql)) {
                $categories[] = $row;
            }
        }

        return $categories;
    }
}

$categoryManager = new CategoryManager($conn);

if (isset($_POST['add_category'])) {
    $categoryManager->addCategory($_POST['name'], $_POST['describes']);
}

if (isset($_GET['delete'])) {
    $categoryManager->deleteCategory($_GET['delete']);
}

if (isset($_POST['update_category'])) {
    $categoryManager->updateCategory($_POST['update_p_id'], $_POST['update_name'], $_POST['update_describes']);
}

$categoryList = $categoryManager->getCategoryList(isset($_GET['search']) ? $_GET['search'] : '');
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
   <link rel="stylesheet" href="css/add.css">
   <link rel="icon" href="uploaded_img/logo2.png">
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

<section class="add-products">
    <span style="color: #005490; font-weight: bold; display: flex; justify-content: center; font-size: 40px;">DANH MỤC SẢN PHẨM</span>
   <!-- <h1 class="title">Danh mục sản phẩm</h1> -->

   <form class="add_sup" method="post" enctype="multipart/form-data">
      <h3>Thêm danh mục</h3>
      <input type="text" name="name" class="box" placeholder="Danh mục" required>
      <input type="text" name="describes" class="box" placeholder="Mô tả" required>
      <input style="background-color: #005490;" type="submit" value="Thêm danh mục " name="add_category" class="btn">
   </form>

</section>

<form class="search" method="GET">
        <input type="text" name="search" placeholder="Nhập tên danh mục cần tìm..." value="<?php if(isset($_GET['search'])) echo $_GET['search'] ?>">
        <button style="background-color: #005490;" type="submit" class="btn">Tìm kiếm</button>
</form>
<button onclick="active_sup()" id="btn-sup" style="margin-bottom: 10px; margin-left: 110px; padding: 5px; font-size: 16px; background-color: #005490;" class="btn btn-info" >Thêm mới</button>
<section class="show-products">

   <div class="container" style="padding: 1rem 0rem 3rem">
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
         <?php
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $sql = mysqli_query($conn, "SELECT * FROM categorys WHERE name LIKE '%$search%'");
               if(mysqli_num_rows($sql) > 0){
                  while ($row = mysqli_fetch_array($sql)) {
             ?>
            <tr>
               <th scope="row"><?php echo $row['id']; ?></th>
               <td><?php echo $row['name']; ?></td>
               <td><?php echo $row['describes']; ?></td>
               <td>
                  <a style="text-decoration: none;" href="admin_category.php?update=<?php echo $row['id']; ?>" class="fixx">Sửa</a> | 
                  <a style="text-decoration: none;" class="fixxx" style="text-decoration: none;" href="admin_category.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Xóa danh mục này?');" >Xóa</a>
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
               <th scope="col">Tên danh mục</th>
               <th scope="col">Mô tả</th>
               <th scope="col">Thao tác</th>
            </tr>
         </thead>
         <tbody>
         <?php
            $select_categories = mysqli_query($conn, "SELECT * FROM `categorys`") or die('query failed');
            while($fetch_cate = mysqli_fetch_assoc($select_categories)){
         ?>
            <tr>
               <th scope="row"><?php echo $fetch_cate['id']; ?></th>
               <td><?php echo $fetch_cate['name']; ?></td>
               <td><?php echo $fetch_cate['describes']; ?></td>
               <td>
                  <a href="admin_category.php?update=<?php echo $fetch_cate['id']; ?>" style="text-decoration: none;" class="fixx">Sửa</a> | 
                  <a style="text-decoration: none;" href="admin_category.php?delete=<?php echo $fetch_cate['id']; ?>" class="fixxx" onclick="return confirm('Xóa danh mục này?');">Xóa</a>
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
         $update_query = mysqli_query($conn, "SELECT * FROM `categorys` WHERE id = '$update_id'") or die('query failed');//lấy ra thông tin loại sách cần cập nhật
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
               <form action="" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
                  <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="Tên">
                  <input type="text" name="update_describes" value="<?php echo $fetch_update['describes']; ?>" class="box" required placeholder="Mô tả">
                  <input type="submit" style="background-color: #005490;" value="Update" name="update_category" class="btn"> <!-- submit form cập nhật -->
                  <input type="reset" style="background-color: #005490;" value="Cancel"  onclick="window.location.href = 'admin_category.php'" class="btn">
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
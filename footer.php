<style>
   .footer{
   background-color: var(--light-bg);
   border-top: 1px solid #ddd;
}

.footer .box-container{
   max-width: 1200px;
   margin:0 auto;
   display: grid;
   grid-template-columns: repeat(auto-fit, minmax(25rem, 1fr));
   gap:3rem;
}

.footer .box-container .box h3{
   text-transform: uppercase;
   color:var(--black);
   font-size: 2rem;
   padding-bottom: 2rem;
}

.footer .box-container .box p,
.footer .box-container .box a{
   display: block;
   font-size: 1.7rem;
   color:var(--light-color);
   padding:1rem 0;
}

.footer .box-container .box p i,
.footer .box-container .box a i{
   color:var(--purple);
   padding-right: .5rem;
}

.footer .box-container .box a:hover{
   color:var(--purple);
   text-decoration: underline;
}

.footer .credit{
   text-align: center;
   font-size: 2rem;
   color:var(--light-color);
   border-top: var(--border);
   margin-top: 2.5rem;
   padding-top: 2.5rem;
}

.footer .credit span{
   color:var(--purple);
}
</style>
<section class="footer">

   <div class="box-container">

      <div class="box">
         <a href="home.php">Trang chủ</a>
         <a href="./admin_products.php">Sản phẩm</a>
         <a href="./admin_category.php">Danh mục</a>
      </div>

      <div class="box">
         <a href="login.php">Đăng nhập</a>
         <a href="register.php">Đăng ký</a>
         <a href="orders.php">Đơn hàng</a>
      </div>

      <div class="box">
         <h3>Thông tin liên lạc</h3>
         <p> <i class="fas fa-phone"></i> +84123456789 </p>
         <p> <i class="fas fa-envelope"></i> quangthang@gmail.com </p>
         <p> <i class="fas fa-map-marker-alt"></i> Hà nội - Việt Nam </p>
      </div>

      <div class="box">
         <h3>Theo dõi chúng tôi</h3>
         <a href="#"> <i class="fab fa-facebook-f"></i> facebook </a>
         <a href="#"> <i class="fab fa-instagram"></i> instagram </a>
      </div>

   </div>

   <p class="credit"> &copy; Copyright  @ <?php echo date('Y'); ?> by <span> Quang Thắng</span> </p>

</section>
  <?php 
    include('../core/init.php');
    $pageTitle = "Dashboard";

    if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
      if($admin->validateRememberMeToken()){

      }else{
        $admin->redirect_to('logout');
      }
    }

    if(isset($_SESSION['user'])){
      $user_id = $_SESSION['user'];
      $getUser = $admin->fetchSingle('tbluser','id',$user_id);

      if(!$getUser){
        $admin->redirect_to('logout');
      }
    }

  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <?php include('../include/head.php'); ?>
  </head>
  <body class="bg-gray-50 min-h-screen">
    <header class="border-b border-gray-200 bg-white">
      <?php include('../include/header.php'); ?>
    </header>

    <main class="container mx-auto px-4 py-6 sm:py-8">
      <?php
          if($getUser->wallet_pin == null){
            $_SESSION['InfoMessage'] = 'You are yet to set your wallet pin. Proceed to settings and set your pin.';
          }
          
          echo ErrorMessage();
          echo SuccessMessage();
          echo InfoMessage();
        ?>
      <div class="bg-white rounded-xl shadow-sm p-6 mb-3 dashboard-card">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
          <!-- Welcome Text -->
          <div class="flex-1">
            <p class="text-gray-500 text-sm mb-1"><?= $getdate; ?>!</p>
            <h2 class="text-2xl font-bold text-gray-800">Welcome back, <?= ucwords($getUser->lastname); ?></h2>

            <!-- Stats Grid -->
            <div class="grid grid-cols-3 gap-4 mt-6 dashboard-stats">
              <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors">
                <center><i class="ri-box-3-line text-xl text-green-700"></i></center>
                <p class="text-2xl font-bold text-center">3</p>
                <p class="text-gray-500 text-sm font-small font-bold text-center" style="font-size: 10px;">Active Boxes</p>
              </div>
              <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors">
                <center><i class="ri-truck-line text-xl text-yellow-500"></i></center>
                <p class="text-2xl font-bold text-center"><?= $order->countUserDeliveredOrders($getUser->id); ?></p>
                <p class="text-gray-500 text-sm font-small font-bold text-center" style="font-size: 10px;">Deliveries</p>
              </div>
              <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors">
                <center><i class="ri-star-line text-xl text-yellow-500"></i></center>
                <p class="text-2xl font-bold text-center">4.8</p>
                <p class="text-gray-500 text-sm font-small font-bold text-center" style="font-size: 10px;">Rating</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Next Delivery Card -->
      <div class="w-full md:w-96 bg-dashboardPrimary rounded-xl p-6 text-white mb-4">
        <h3 class="font-semibold text-lg mb-4">Next Delivery</h3>

        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
            <i class="ri-home-heart-line text-lg"></i>
          </div>
          <div>
            <p class="font-medium">Family Box</p>
            <p class="text-sm opacity-90">Tomorrow, August 15</p>
          </div>
        </div>

        <div class="flex items-center gap-2 text-sm mb-6">
          <i class="ri-time-line"></i>
          <span>Between 9:00 AM - 12:00 PM</span>
        </div>

        <button class="w-full bg-white text-dashboardPrimary py-3 rounded-button font-medium flex items-center justify-center gap-2 hover:bg-gray-100 transition-colors">
          <i class="ri-map-pin-line"></i>
          <span>Track</span>
        </button>
      </div>

      <h2 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4">Top Selling Products</h2>
      <div class="bg-white rounded shadow-sm p-1 sm:p-2 border border-gray-100 mb-2">
        <div style="display: flex; gap: 10px; overflow-x: auto; padding-bottom: 10px;">
          <?php 
            foreach($order->getTopFiveSellingProducts() as $getTopSelling): 
              $allProducts = $admin->fetchSingle('tblproduct','product_id',$getTopSelling->product_id);
          ?>
          <div class="border border-gray-100 rounded p-2 flex-shrink-0 bg-white shadow-sm" style="width: 200px;">
            <div class="w-full h-40 bg-gray-100 rounded overflow-hidden mb-2">
              <img src="../admin/assets/product_image/<?= $allProducts->product_image; ?>" alt="<?= $allProducts->product_name; ?>" class="w-full h-full object-cover" >
            </div>
            <a href="product-details?pid=<?= $allProducts->product_id; ?>">
              <h4 class="text-sm font-semibold mb-1"><?= ucwords($allProducts->product_name); ?></h4>
            </a>
            <p class="text-xs text-gray-500 mb-2">₦ <?= $allProducts->selling_price; ?></p>
            <button class="w-full bg-green-700 text-white px-4 py-2 rounded hover:bg-green-700 text-sm sm:text-base" id="product_id" onclick="addToCart(<?= $allProducts->product_id; ?>)">Add to Cart</button>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="flex justify-center mt-2 mb-6">
          <a href="shop" class="text-green-500 hover:bg-green-600 hover:text-white font-semibold py-2 px-4 rounded text-sm">See More</a>
        </div>
      </div>
        
        <?php
        if(!$order->getTopFiveSellingProducts()):
          ?>
          <div class="space-y-3 sm:space-y-4">
            <div class="flex items-center justify-center">
              <i class="ri-box-2-line text-primary ri-lg" style="font-size: 60px;"></i>
            </div>
            <p class="text-gray-600 text-center text-sm">No product(s) available at the moment</p>
          </div>
        <?php endif; ?>

    </main>

    <footer class="">
      <?php 
        include('../include/footer.php'); 
        include('../include/script.php');
      ?>
    </footer>

  <script>

      const fetchProduct = (value) => {

        let category_id = value;

        $.ajax({
          url: 'fetchProduct.php',
          type: 'POST', 
          dataType: 'json', 
          data:{
            category_id: category_id
          },
          success: function(response) {
            if (response) {
              $('.fetch_product').html(response.html);
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error: " + textStatus, errorThrown); 
          }
        });
      }

      const addToCart = (value) => {

        let product_id = value;

        $('#error-message').hide().empty();

        $.ajax({
          url:'insertToPlanCart.php',
          type: 'POST',
          dataType: 'json',
          data:{
            product_id: product_id
          },
          success: function(response) {
            if (response.success) {
              console.log(response);
              showNotification(response.message, 'success');
            }else{
              showNotification(response.message, 'error');
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error: " + textStatus, errorThrown); 
          }
        });

      }

      fetchProduct();

    </script>

  </body>

    <div id="notification-container" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;"></div>

  </html>

  <?php 
  include('../core/init.php');
  $pageTitle = "Selected Plan";

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

  if(isset($_GET['pid'])){

    $_GET['pid'] = stripcslashes($_GET['pid']);
    $plan_id = $_GET['pid'];

      // check if plan id exist
    if(!$admin->select('tblplan','plan_id',$plan_id)){
      header('location: subscription');
    }
    else if($admin->select('tblplan','plan_id',$plan_id)){
      $fetchPlanData = $admin->fetchSingle('tblplan','plan_id',$plan_id);
    }

  }elseif(!isset($_GET['pid']) AND empty($_GET['pid'])){
    header('location: subscription');
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
      <div class="mb-6 sm:mb-8">
        <nav class="flex items-center text-xs sm:text-sm text-gray-500 mb-2">
          <a href="subscription" class="hover:text-green-600">Subscription Plans</a>
          <div class="w-3 h-3 sm:w-4 sm:h-4 flex items-center justify-center mx-1">
            <i class="ri-arrow-right-s-line"></i>
          </div>
          <span class="text-gray-900"><?= ucwords($fetchPlanData->title); ?></span>
        </nav>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900"><?= ucwords($fetchPlanData->title); ?> Plan Summary</h1>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 sm:gap-8">
        <div class="lg:col-span-2 space-y-6 sm:space-y-8">
          <!-- Selected Plan Summary -->
          <h2 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4"><?= ucwords($fetchPlanData->title); ?> Plan</h2>
          <div class="bg-white rounded shadow-sm p-4 sm:p-6 border border-gray-100">
            <div class="flex items-start gap-3 sm:gap-4">
              <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-green-100 flex items-center justify-center">
                <i class="ri-box-3-line text-primary ri-lg"></i>
              </div>
              <div>
                <h3 class="text-base sm:text-lg font-medium"><?= ucwords($fetchPlanData->title); ?></h3>
                <p class="text-sm sm:text-base text-gray-600">
                  <?= html_entity_decode(ucfirst($fetchPlanData->description)); ?>
                </p>
                <div class="mt-1 sm:mt-2 flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                  <span class="font-semibold text-gray-900">₦ <?= $user->numberFormat($fetchPlanData->amount); ?>/month</span>
                  <span class="text-xs sm:text-sm text-gray-500">Delivery Fee: ₦ <?= $user->numberFormat($fetchPlanData->delivery_fee); ?></span>
                  <span class="text-xs sm:text-sm text-gray-500"><?= $admin->countByColumn('tblplanproduct','plan_id',$plan_id); ?> items per box</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Product Items Section -->
          <h2 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4">Box Contents</h2>
          <div class="bg-white-100 rounded shadow-sm p-1 sm:p-6 border border-gray-100 mb-8">
            <div class="overflow-y-auto max-h-96 space-y-4 p-1 rounded-lg">
              <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2">
                <?php
                foreach($admin->selectWhere('tblplanproduct','plan_id',$fetchPlanData->plan_id) as $getProductPlans): 
                  $allProducts = $admin->fetchSingle('tblproduct','product_id',$getProductPlans->product_id);
                  ?>
                  <div class="border border-gray-100 rounded p-2 flex flex-col items-center space-y-2 bg-white shadow-sm">
                    <div class="w-16 h-16 bg-gray-100 rounded overflow-hidden">
                      <img src="../admin/assets/product_image/<?= $allProducts->product_image; ?>" alt="<?= ucwords($allProducts->product_name); ?>" class="w-full h-full object-cover object-top" />
                    </div>
                    <h4 class="text-sm font-medium text-center"><?= ucwords($allProducts->product_name); ?></h4>
                    <p class="text-xs text-gray-500 text-center mb-2"><?= $allProducts->quantity . " " . $allProducts->unit; ?> of <?= $allProducts->product_name; ?> </p>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <?php
            if(!$admin->selectWhere('tblplanproduct','plan_id',$fetchPlanData->plan_id)):
              ?>
              <div class="space-y-3 sm:space-y-4">
                <div class="flex items-center justify-center">
                  <i class="ri-box-2-line text-primary ri-lg" style="font-size: 60px;"></i>
                </div>
                <p class="text-gray-600 text-center text-sm">No Item(s) in Box</p>
              </div>
            <?php endif; ?>
          </div>

          <!-- Order Summary Card -->
          <div class="lg:col-span-1 mb-10">
            <h2 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4">Order Summary</h2>
            <div class="bg-white rounded shadow-sm p-4 sm:p-6 border border-gray-100 sticky top-4 mb-12">
              <div class="space-y-2 sm:space-y-3 mb-4 sm:mb-6">
                <div class="flex justify-between text-xs sm:text-sm text-gray-600">
                  <span>Subtotal (<?= $admin->countByColumn('tblplanproduct','plan_id',$plan_id); ?> items)</span>
                  <span>₦ <?= $user->numberFormat($fetchPlanData->amount); ?></span>
                </div>
                <div class="flex justify-between text-xs sm:text-sm text-gray-600">
                  <span>Delivery Fee</span>
                  <span class="text-green-600">₦ <?= $user->numberFormat($fetchPlanData->delivery_fee); ?></span>
                </div>
                <div class="pt-2 sm:pt-3 border-t border-gray-100 flex justify-between font-semibold text-sm sm:text-base text-gray-900">
                  <span>Total</span>
                  <span>₦<?= $user->numberFormat(($fetchPlanData->amount + $fetchPlanData->delivery_fee)); ?></span>
                </div>
              </div>
              <?php if($admin->countByColumn('tblplanproduct','plan_id',$plan_id) > 0): ?>
                <input type="submit" role="button" onclick="addToCart('<?= $fetchPlanData->plan_id; ?>')" class="w-full bg-green-700 text-white px-4 py-3 rounded-button hover:bg-green-700 whitespace-nowrap text-sm sm:text-base" name="btnAddToCart" value="Add to Cart">
              <?php endif; ?>
            </div>
          </div>

        </div>

      </div>

    </main>

    <footer class="">
      <?php include('../include/footer.php'); ?>
    </footer>

    <?php include('../include/script.php'); ?>
    
    <script>

      const addToCart = (value) => {

        let plan_id = value;

        $('#error-message').hide().empty();

        $.ajax({
          url:'insertToPlanCart.php',
          type: 'POST',
          dataType: 'json',
          data:{
            plan_id: plan_id
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

    </script>

  </body>

  </html>
  
  <?php 
    include('../core/validate/profile.php');
    $pageTitle = "Profile";

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
      <div class="flex items-center gap-2 mb-6">
        <a href="settings" class="text-gray-500 hover:text-green-600">
          <div class="w-5 h-5 flex items-center justify-center">
            <i class="ri-arrow-left-line"></i>
          </div>
        </a>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Profile</h1>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
        <div class="md:col-span-2">
          <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Personal Information</h3>
            
            <form method="post" autocomplete="off">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                  <input type="text" value="<?= ucwords($getUser->firstname); ?>" name="firstname" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none outline-none text-sm sm:text-base">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                  <input type="text" value="<?= ucwords($getUser->lastname); ?>" name="lastname" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none outline-none text-sm sm:text-base">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                  <input type="email" value="<?= strtolower($getUser->email); ?>" name="email" readonly class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm sm:text-base">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                  <input type="tel" value="<?= $getUser->phone; ?>" name="phone" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm sm:text-base">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                  <select name="gender" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm sm:text-base" required>
                    <option value="" selected>Select Gender</option>
                    <option value="male" <?php if($getUser->gender == "male") { echo "selected"; } ?>>Male</option>
                    <option value="female" <?php if($getUser->gender == "female") { echo "selected"; } ?>>Female</option>
                  </select>
                </div>
              </div>
              <div class="mt-4 sm:mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Address</label>
                <textarea class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm sm:text-base" name="address" rows="3"><?= $userAddress = $getUser->address ? $getUser->address : "N/A"; ?></textarea>
              </div>
              <div class="mt-4 sm:mt-6 flex justify-end">
                <input type="submit" role="button" class="bg-green-700 w-full text-white px-4 py-3 rounded-button hover:bg-green-700 whitespace-nowrap text-sm sm:text-base" name="btnProfile" value="Save Changes">
              </div>
            </form>
          </section>

        </div>

      </div>
    </main>

    <?php include('../include/script.php'); ?> 

  </body>

  </html>
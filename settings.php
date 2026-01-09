  <?php 
    include('../core/validate/checkout.php');
    $pageTitle = "Settings";

    if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
      if($admin->validateRememberMeToken()){

      }else{
        $admin->redirect_to('logout');
      }
    }

    if(isset($_SESSION['user'])){
      $user_id = $_SESSION['user'];
      $getUser = $admin->fetchSingle('tbluser','id',$user_id);

      $getUserSubscription = $order->getLastOrder($getUser->id);

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
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
          <div class="relative">
            <img src="../assets/img/user_img/<?= $picture = $getUser->picture ? $getUser->picture : 'user_default.jpg'; ?>" class="w-20 h-20 object-cover rounded-full">
            <button class="absolute bottom-0 right-0 bg-green-600 text-white w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center shadow-md hover:bg-green-700">
              <div class="w-3 h-3 sm:w-4 sm:h-4 flex items-center justify-center">
                <i class="ri-camera-line" id="uploadImageBtn"></i>
                <form method="post" id="uploadForm" action="upload.php" enctype="multipart/form-data">
                  <input type="file" id="imageInput" name="user_img" onsubmit="this.form.submit()" class="hidden" accept="image/*">
                </form>
              </div>
            </button>
          </div>
          <div class="text-center sm:text-left">
            <h2 class="text-lg sm:text-xl font-bold text-gray-900"><?= ucwords($getUser->firstname . " " . $getUser->lastname); ?></h2>
            <p class="text-gray-600 mb-2 text-sm sm:text-base"><?= strtolower($getUser->email); ?></p>
            <?php if($getUserSubscription): ?>
            <div class="inline-block bg-green-100 text-green-700 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium">
              <?= ucwords($getUserSubscription->title); ?> Subscriber
            </div>
            <?php endif; ?>
          </div>
          <a href="profile" class="mt-3 sm:mt-0 sm:ml-auto bg-white border border-gray-300 text-gray-700 px-3 sm:px-4 py-1.5 sm:py-2 rounded-button hover:bg-gray-50 flex items-center gap-2 whitespace-nowrap text-sm sm:text-base">
            <div class="w-3 h-3 sm:w-4 sm:h-4 flex items-center justify-center">
              <i class="ri-edit-line"></i>
            </div>
            <span>Edit Profile</span>
          </a>
        </div>
      </div>
        

       <div>
        <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Wallet</h3>
            <div class="bg-green-50 border border-green-100 rounded-lg p-3 sm:p-4 mb-4">
              <div class="flex items-center mb-2">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 mr-2 sm:mr-3">
                  <div class="w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center">
                    <i class="ri-wallet-line"></i>
                  </div>
                </div>
                <h4 class="font-bold text-gray-900 text-sm sm:text-base">Wallet Balance</h4>
              </div>
              <p class="text-gray-600 text-xs sm:text-sm mb-2 sm:mb-3"></p>
              <div class="flex justify-between items-center">
                <span class="font-bold text-gray-900 text-sm sm:text-base">₦ <?= $user->numberFormat($getUser->wallet_balance); ?><span class="text-xs sm:text-sm font-normal text-gray-600"></span></span>
                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Active</span>
              </div>
            </div>
            <div class="space-y-3">
              <button id="addFundsBtn" class="w-full bg-white border border-green-600 text-green-600 px-3 sm:px-4 py-1.5 sm:py-2 rounded-button hover:bg-green-50 flex items-center justify-center gap-2 whitespace-nowrap text-sm sm:text-base">
                <div class="w-3 h-3 sm:w-4 sm:h-4 flex items-center justify-center"></div>
                <span>Add funds</span>
              </button>
              <a type="button" href="transactions" class="w-full bg-white border border-green-600 text-green-600 px-3 sm:px-4 py-1.5 sm:py-2 rounded-button hover:bg-green-50 flex items-center justify-center gap-2 whitespace-nowrap text-sm sm:text-base">Transaction History</a>
            </div>
          </section>
      </div>

      <div>
        <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Current Subscription</h3>
            <?php if($getUserSubscription): ?>
            <div class="bg-green-50 border border-green-100 rounded-lg p-3 sm:p-4 mb-4">
              <div class="flex items-center mb-2">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 mr-2 sm:mr-3">
                  <div class="w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center">
                    <i class="ri-home-line"></i>
                  </div>
                </div>
                <h4 class="font-bold text-gray-900 text-sm sm:text-base"><?= $result = $getUserSubscription->title ? ucwords($getUserSubscription->title) : '' ; ?></h4>
              </div>
              <p class="text-gray-600 text-xs sm:text-sm mb-2 sm:mb-3"><?= html_entity_decode(ucwords($getUserSubscription->description)); ?></p>
              <div class="flex justify-between items-center">
                <span class="font-bold text-gray-900 text-sm sm:text-base">₦<?= $user->numberFormat($getUserSubscription->price); ?><span class="text-xs sm:text-sm font-normal text-gray-600">/month</span></span>
              </div>
            </div>
            <?php endif; ?>

            <?php if(!$getUserSubscription): ?>
            <div class="space-y-3 sm:space-y-4 mb-8">
              <div class="flex items-center justify-center">
                <i class="ri-box-3-fill text-primary ri-lg" style="font-size: 60px;"></i>
              </div>
              <p class="text-gray-600 text-center text-sm">You have no subscription.</p>
            </div>
            <?php endif; ?>
          </section>
      </div>

      <div>
          <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 mb-12">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Account Actions</h3>
            <div class="space-y-3">
              <a type="button" href="change-password" class="w-full bg-white border border-gray-300 text-gray-700 px-3 sm:px-4 py-1.5 sm:py-2 rounded-button hover:bg-gray-50 flex items-center justify-center gap-2 whitespace-nowrap text-sm sm:text-base">
                <div class="w-3 h-3 sm:w-4 sm:h-4 flex items-center justify-center">
                  <i class="ri-lock-line"></i>
                </div>
                <span>Change Password</span>
              </a>
              <a type="button" href="set-pin" class="w-full bg-white border border-gray-300 text-gray-700 px-3 sm:px-4 py-1.5 sm:py-2 rounded-button hover:bg-gray-50 flex items-center justify-center gap-2 whitespace-nowrap text-sm sm:text-base">
                <div class="w-3 h-3 sm:w-4 sm:h-4 flex items-center justify-center">
                  <i class="ri-lock-line"></i>
                </div>
                <span>Set Pin</span>
              </a>
              <a type="button" href="support-ticket" class="w-full bg-white border border-gray-300 text-gray-700 px-3 sm:px-4 py-1.5 sm:py-2 rounded-button hover:bg-gray-50 flex items-center justify-center gap-2 whitespace-nowrap text-sm sm:text-base">
                <div class="w-3 h-3 sm:w-4 sm:h-4 flex items-center justify-center">
                  <i class="ri-ticket-line"></i>
                </div>
                <span>Support Ticket</span>
              </a>
              <a type="button" id="contactSupportBtn" href="#" class="w-full bg-white border border-gray-300 text-gray-700 px-3 sm:px-4 py-1.5 sm:py-2 rounded-button hover:bg-gray-50 flex items-center justify-center gap-2 whitespace-nowrap text-sm sm:text-base">
                <div class="w-3 h-3 sm:w-4 sm:h-4 flex items-center justify-center">
                  <i class="ri-headphone-line"></i>
                </div>
                <span>Contact Support</span>
              </a>
              <a type="button" href="#" class="w-full bg-white border border-gray-300 text-gray-700 px-3 sm:px-4 py-1.5 sm:py-2 rounded-button hover:bg-gray-50 flex items-center justify-center gap-2 whitespace-nowrap text-sm sm:text-base">
                <div class="w-3 h-3 sm:w-4 sm:h-4 flex items-center justify-center">
                  <i class="ri-info-i"></i>
                </div>
                <span>Terms & Conditions</span>
              </a>
              <a type="button" href="#" class="w-full bg-white border border-gray-300 text-gray-700 px-3 sm:px-4 py-1.5 sm:py-2 rounded-button hover:bg-gray-50 flex items-center justify-center gap-2 whitespace-nowrap text-sm sm:text-base">
                <div class="w-3 h-3 sm:w-4 sm:h-4 flex items-center justify-center">
                  <i class="ri-info-i"></i>
                </div>
                <span>Privacy Policy</span>
              </a>
              <a type="button" href="#" class="w-full bg-white border border-gray-300 text-gray-700 px-3 sm:px-4 py-1.5 sm:py-2 rounded-button hover:bg-gray-50 flex items-center justify-center gap-2 whitespace-nowrap text-sm sm:text-base">
                <div class="w-3 h-3 sm:w-4 sm:h-4 flex items-center justify-center">
                  <i class="ri-info-i"></i>
                </div>
                <span>About Us</span>
              </a>
              <a type="button" href="logout" class="w-full bg-white border border-red-300 text-red-700 px-3 sm:px-4 py-1.5 sm:py-2 rounded-button hover:bg-red-50 flex items-center justify-center gap-2 whitespace-nowrap text-sm sm:text-base">
                <div class="w-3 h-3 sm:w-4 sm:h-4 flex items-center justify-center">
                  <i class="ri-logout-box-line"></i>
                </div>
                <span>Sign Out</span>
              </a>
            </div>
          </section>
      </div>
    </main>

    <!-- Modal for Fund Wallet -->
    <div id="fundsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-sm w-full relative">
        <!-- Close Button -->
        <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
          <i class="ri-close-line"></i>
        </button>
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Fund wallet</h2>
        <form method="post" class="space-y-4">
          <div>
            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Enter an amount you wish to add.</label>
            <input type="number" id="amount" name="amount" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Enter amount" required>
          </div>
          <input type="submit" class="w-full bg-green-700 text-white py-3 px-4 rounded-lg hover:bg-green-700 transition" value="Continue" name="btnFundWallet">
        </form>
      </div>
    </div>

    <!-- Modal for Contact Support -->
    <div id="supportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-sm w-full relative">
        <!-- Close Button -->
        <button id="closeSupportModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
          <i class="ri-close-line"></i>
        </button>
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Contact Support</h2>
        <p class="text-md font-bold mb-2">We're here to help</p>
        <p class="text-xs font-semibold">Talk to Support</p>

        <div class="flex items-center justify-between w-full max-w-xs p-3 bg-white border rounded shadow mt-2">
          <div class="flex items-center space-x-2">
            <i class="ri-chat-1-line text-xl text-green-700"></i>
            <span class="text-gray-700">Start a Chat</span>
          </div>
          <i class="ri-arrow-right-s-line text-xl text-gray-500"></i>
        </div>

        <a href="https://wa.me/+2347049269626">
          <div class="flex items-center justify-between w-full max-w-xs p-3 bg-white border rounded shadow mt-2">
            <div class="flex items-center space-x-2">
              <i class="ri-whatsapp-line text-xl text-green-700"></i>
              <span class="text-gray-700">Chat on WhatsApp</span>
            </div>
            <i class="ri-arrow-right-s-line text-xl text-gray-500"></i>
          </div>
        </a>

      </div>
    </div>

    <footer class="">
      <?php include('../include/footer.php'); ?>
    </footer>

    <?php include('../include/script.php'); ?> 

    <script>
        const contactSupportBtn = document.getElementById('contactSupportBtn');
        const supportModal = document.getElementById('supportModal');
        const closeSupportModalBtn = document.getElementById('closeSupportModal');

        const addFundsBtn = document.getElementById('addFundsBtn');
        const modal = document.getElementById('fundsModal');
        const closeModalBtn = document.getElementById('closeModal');

        contactSupportBtn.addEventListener('click', () => {
          supportModal.classList.remove('hidden');
        });

        closeSupportModalBtn.addEventListener('click', () => {
          closeSupportModalBtn.classList.add('hidden');
        });

        // Close modal when clicking outside the modal content
        supportModal.addEventListener('click', (e) => {
          if (e.target === supportModal) {
            supportModal.classList.add('hidden');
          }
        });

        addFundsBtn.addEventListener('click', () => {
          modal.classList.remove('hidden');
        });

        closeModalBtn.addEventListener('click', () => {
          modal.classList.add('hidden');
        });

        // Close modal when clicking outside the modal content
        modal.addEventListener('click', (e) => {
          if (e.target === modal) {
            modal.classList.add('hidden');
          }
        });

      const uploadBtn = document.getElementById('uploadImageBtn');
      const fileInput = document.getElementById('imageInput');
      const imagePreviewContainer = document.getElementById('imagePreview');
      const uploadForm = document.getElementById('uploadForm');

      uploadBtn.addEventListener('click', () => {
        fileInput.click();
      });

      fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = (e) => {
            // Display image preview
            //imagePreviewContainer.innerHTML = `<img src="${e.target.result}" class="w-20 h-20 object-cover rounded-full">`;
          };
          reader.readAsDataURL(file);

          uploadForm.submit();
        }
      });
  </script>
    </script>

    <script id="profileDropdownScript">
      document.addEventListener('DOMContentLoaded', function() {
        const profileButton = document.getElementById('profileButton');
        const profileDropdown = document.createElement('div');
        profileDropdown.className = 'absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-10 hidden';
        profileDropdown.innerHTML = `
        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-green-50 hover:text-green-600 text-sm">Your Profile</a>
        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-green-50 hover:text-green-600 text-sm">Settings</a>
        <div class="border-t border-gray-100 my-1"></div>
        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-green-50 hover:text-green-600 text-sm">Sign out</a>
        `;
        profileButton.parentNode.appendChild(profileDropdown);
        profileButton.addEventListener('click', function() {
          profileDropdown.classList.toggle('hidden');
        });
        document.addEventListener('click', function(event) {
          if (!profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
            profileDropdown.classList.add('hidden');
          }
        });
      });
    </script>

  </body>
  </html>
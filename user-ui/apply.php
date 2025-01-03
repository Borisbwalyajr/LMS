<?php
   include "../connection.php";
   session_start();

   if (!isset($_SESSION['user_id'])) {
       echo "<script>
               alert('You are currently not logged in!');
               window.location.href='../index.html';
           </script>";
       exit;
   }

   // Fetch logged-in user details
   $stmt = $pdo->prepare("SELECT * FROM registrations WHERE id_number = ?");
   $stmt->execute([$_SESSION['user_id']]);
   $user = $stmt->fetch(PDO::FETCH_ASSOC);

   // Check if the user has an active loan with "credited" status
   $loanCheckStmt = $pdo->prepare("SELECT * FROM loan_applications WHERE nrc = ? AND status = 'credited'");
   $loanCheckStmt->execute([$user["id_number"]]);
   $activeLoan = $loanCheckStmt->fetch(PDO::FETCH_ASSOC);

   if ($activeLoan) {
       // If the user has an outstanding loan
       $outstandingBalance = $activeLoan['repayment'];
       echo "<script>
               alert('You are still owing an outstanding balance of ZMW $outstandingBalance. Please clear it before applying for a new loan.');
               window.location.href='repayment_page.php'; // Redirect to a repayment page or handle repayment process here
           </script>";
       exit;
   }

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
       // Existing loan application logic
       $loanAmount = filter_input(INPUT_POST, 'loan_amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
       $purpose = htmlspecialchars($_POST['purpose'], ENT_QUOTES, 'UTF-8');
       $weeks = filter_input(INPUT_POST, 'weeks', FILTER_SANITIZE_NUMBER_INT);
       $repayment = filter_input(INPUT_POST, 'repayment', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

       $loanDate = date('Y-m-d'); // Current date
       $dueDate = date('Y-m-d', strtotime("+$weeks weeks")); // Add weeks to the current date
       $loan_code = "FVL" . random_int(10000, 100000);
       $status = "not approved";

       // File upload and insertion logic
       if (isset($_FILES['collateral_image']) && $_FILES['collateral_image']['error'] == 0) {
           $targetDir = "./img/collateral/";
           $fileName = uniqid() . "-" . basename($_FILES['collateral_image']['name']);
           $targetFilePath = $targetDir . $fileName;
           $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

           if (move_uploaded_file($_FILES['collateral_image']['tmp_name'], $targetFilePath)) {
               $stmt = $pdo->prepare("INSERT INTO loan_applications (loan_id, nrc, amount, purpose, weeks, repayment, collateral_image, loan_date, due_date, status) 
                   VALUES (:loan, :nrc, :amount, :purpose, :weeks, :repayment, :collateral_image, :loan_date, :due_date, :status)");

               $stmt->bindParam(':loan', $loan_code);
               $stmt->bindParam(':nrc', $user["id_number"]);
               $stmt->bindParam(':amount', $loanAmount);
               $stmt->bindParam(':purpose', $purpose);
               $stmt->bindParam(':weeks', $weeks);
               $stmt->bindParam(':repayment', $repayment);
               $stmt->bindParam(':collateral_image', $fileName);
               $stmt->bindParam(':loan_date', $loanDate);
               $stmt->bindParam(':due_date', $dueDate);
               $stmt->bindParam(':status', $status);

               if ($stmt->execute()) {
                   echo "<script>
                           alert('Loan Application Submitted Successfully!');
                         </script>";
               } else {
                   echo "<script>
                           alert('Failed To Submit Loan!');
                         </script>";
               }
           } else {
               echo "<script>
                       alert('Failed To Upload Collateral Image!');
                     </script>";
           }
       } else {
           echo "<script>
                   alert('Please upload a valid collateral image!');
                 </script>";
       }
   }
?>

<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>FV Money Lenders</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- <link rel="manifest" href="site.webmanifest"> -->
    <link rel="shortcut icon" type="image/x-icon" href="img/logo.png">


    <!-- CSS here -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/themify-icons.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/gijgo.css">
    <link rel="stylesheet" href="css/animate.min.css">
    <link rel="stylesheet" href="css/slick.css">
    <link rel="stylesheet" href="css/slicknav.css">

    <link rel="stylesheet" href="css/style.css">
    <!-- <link rel="stylesheet" href="css/responsive.css"> -->
</head>

<body>
    <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

    <!-- header-start -->
    <header>
        <div class="header-area ">
            <div id="sticky-header" class="main-header-area">
                <div class="container-fluid ">
                    <div class="header_bottom_border">
                        <div class="row align-items-center">
                            <div class="col-xl-3 col-lg-2">
                                <div class="logo">
                                    <a href="index.php">
                                        <img src="img/logo.png" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-7">
                                <div class="main-menu  d-none d-lg-block">
                                    <nav>
                                        <ul id="navigation">
                                            <li><a href="index.php#">home</a></li>
                                            <li><a href="index.php#offer">Our Offer</a></li>
                                            <li><a href="index.php#about">about</a></li>
                                            <li><a href="index.php#how">How It Works</a></li>
                                            <li><a href="contact.html">Contact</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 d-none d-lg-block">
                                <div class="Appointment">
                                    <div class="phone_num d-none d-xl-block">
                                        <a href="#"> <i class="fa fa-phone"></i>  +260 973 567 367</a>
                                    </div>
                                    <div class="d-none d-lg-block">
                                        <a class="boxed-btn4" href="./logout.php">Logout</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mobile_menu d-block d-lg-none"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </header>
    <!-- header-end -->

      <!-- bradcam_area  -->
      <div class="bradcam_area bradcam_bg_3">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="bradcam_text">
                        <h3>Welcome <?php echo $user["full_name"] ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ bradcam_area  -->
    
    <!-- apply_form_area -->
    <!-- apply_form_area -->
    <div class="apply_form_area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form action="#" class="apply_form" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="apply_info_text text-center">
                                    <h3>How much do you want to borrow?</h3>
                                    <p>We provide instant cash loans with quick approval that suit your term length</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="pay_text">
                                    <p>Image Of Collateral</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="single_field">
                                    <input type="file" accept="image/png, image/jpeg, image/jpg, image/gif" name="collateral_image" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="single_field">
                                    <select class="wide" name="purpose" required>
                                        <option data-display="Purpose">Purpose</option>
                                        <option value="Personal">Personal Loan</option>
                                        <option value="2">Business Loan</option>
                                      </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="single_field">
                                    <input type="number" min="500" id="loan" name="loan_amount" placeholder="Enter Amount" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="single_input">
                                    <select class="wide" id="week" onchange="updateRepayment()" required name="weeks">
                                        <option value="" disabled selected>Week</option>
                                        <option value="1">1 Week</option>
                                        <option value="2">2 Weeks</option>
                                        <option value="3">3 Weeks</option>
                                        <option value="4">4 Weeks</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="pay_text">
                                    <p id="paymentPrompt" style="display: none;">You have to pay: ZMW <span id="repay">0</span></p>
                                    <p id="serviceFeePrompt" style="display: none;">Service Fee: ZMW <span id="serviceFee">0</span></p>
                                    <input type="hidden" id="hiddenCollateral" name="repayment">
                                </div>
                            </div>
                            <div id="paymentForm" class="col-md-12" style="display: none;">
                                <div class="submit_btn">
                                    <button class="boxed-btn3" type="submit">Apply Now</button>
                                </div>
                            </div>

                            <script>
                                // Copy the repayment amount to the hidden input before form submission
                                document.querySelector('form').addEventListener('submit', function () {
                                    const paragraphText = document.getElementById('repay').innerText;
                                    document.getElementById('hiddenCollateral').value = paragraphText;
                                });

                                function updateRepayment() {
                                    const loan = document.getElementById("loan").value;
                                    const week = document.getElementById("week").value;
                                    const repay = document.getElementById("repay");
                                    const serviceFee = document.getElementById("serviceFee");
                                    const paymentPrompt = document.getElementById("paymentPrompt");
                                    const serviceFeePrompt = document.getElementById("serviceFeePrompt");
                                    const paymentForm = document.getElementById("paymentForm");

                                    // Ensure both values are selected
                                    if (!loan || !week) {
                                        repay.innerText = "0";
                                        serviceFee.innerText = "0";
                                        paymentPrompt.style.display = "none";
                                        serviceFeePrompt.style.display = "none";
                                        paymentForm.style.display = "none";
                                        return;
                                    }

                                    const loanAmount = parseInt(loan, 10);
                                    const numOfWeeks = parseInt(week, 10);

                                    let totalRepayment;
                                    let fee = 25; // fixed service fee

                                    // Calculate repayment based on the selected week
                                    switch (numOfWeeks) {
                                        case 1:
                                            totalRepayment = loanAmount + 0.3 * loanAmount;
                                            break;
                                        case 2:
                                            totalRepayment = loanAmount + 0.35 * loanAmount;
                                            break;
                                        case 3:
                                            totalRepayment = loanAmount + 0.4 * loanAmount;
                                            break;
                                        case 4:
                                            totalRepayment = loanAmount + 0.45 * loanAmount;
                                            break;
                                        default:
                                            totalRepayment = 0;
                                    }

                                    // Calculate service fee
                                    const finalRepayment = totalRepayment - fee;

                                    // Update the repayment amount and service fee
                                    repay.innerText = totalRepayment.toFixed(2);
                                    serviceFee.innerText = fee.toFixed(2);

                                    // Show the payment prompt and form if repayment is greater than 0
                                    if (totalRepayment > 0) {
                                        paymentPrompt.style.display = "block";
                                        serviceFeePrompt.style.display = "block";
                                        paymentForm.style.display = "block";
                                    } else {
                                        paymentPrompt.style.display = "none";
                                        serviceFeePrompt.style.display = "none";
                                        paymentForm.style.display = "none";
                                    }
                                }
                            </script>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--/ apply_form_area -->
    <!-- works_area_start  -->
    <div class="works_area" id="how">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section_title text-center mb-90">
                        <span class="wow lightSpeedIn" data-wow-duration="1s" data-wow-delay=".1s"></span>
                        <h3 class="wow fadeInUp" data-wow-duration="1s" data-wow-delay=".2s">How it Works</h3>
                        <p class="wow fadeInUp" data-wow-duration="1s" data-wow-delay=".3s">We provide online instant cash loans with quick approval that suit your term</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <div class="single_works wow fadeInUp" data-wow-duration="1s" data-wow-delay=".4s">
                        <span>
                            01
                        </span>
                        <h3>Apply for loan</h3>
                        <p>Apply for a loan amout of your choice through our system.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="single_works wow fadeInUp" data-wow-duration="1s" data-wow-delay=".5s">
                        <span>
                            02
                        </span>
                        <h3>Application review</h3>
                        <p>Our specialized team go through the submitted details.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="single_works wow fadeInUp" data-wow-duration="1s" data-wow-delay=".6s">
                        <span>
                            03
                        </span>
                        <h3>Get funding fast</h3>
                        <p>The desired amount applied for is disbursed directly to you.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="apply_loan overlay">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-7">
                    <div class="loan_text wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".3s">
                        <h3>Apply for a Loan for your startup, 
                            education or company</h3>
                    </div>
                </div>
                <div class="col-lg-4 col-md-5">
                    <div class="loan_btn wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".4s">
                        <a class="boxed-btn3" href="apply.php">Apply Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- footer start -->
    <footer class="footer">
        <div class="footer_top">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-md-6 col-lg-3">
                        <div class="footer_widget wow fadeInUp" data-wow-duration="1s" data-wow-delay=".3s">
                            <div class="footer_logo">
                                <a href="#">
                                    <img src="img/logo.png" alt=""> FV Money Lenders
                                </a>
                            </div>
                            <p>
                                fvmoneylenders@support.com <br>
                                +260 973 567 367 <br>
                                Lusaka, Zambia
                            </p>
                            <div class="socail_links">
                                <ul>
                                    <li>
                                        <a href="#">
                                            <i class="ti-facebook"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-google-plus"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-twitter"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-instagram"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                    <div class="col-xl-2 col-md-6 col-lg-3">
                        <div class="footer_widget wow fadeInUp" data-wow-duration="1.1s" data-wow-delay=".4s">
                            <h3 class="footer_title">
                                Services
                            </h3>
                            <ul>
                                <li><a href="#">Money Lending </a></li>
                                <li><a href="#">Collateral Based </a></li>
                                <li><a href="#">Non-Collateral</a></li>
                                <li><a href="#">Business Loan</a></li>
                            </ul>

                        </div>
                    </div>
                    <div class="col-xl-2 col-md-6 col-lg-2">
                        <div class="footer_widget wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".5s">
                            <h3 class="footer_title">
                                Useful Links
                            </h3>
                            <ul>
                                <li><a href="#about">About</a></li>
                                <li><a href="./contact.html"> Contact</a></li>
                                <li><a href="#">Support</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 col-lg-4">
                        <div class="footer_widget wow fadeInUp" data-wow-duration="1.3s" data-wow-delay=".6s">
                            <h3 class="footer_title">
                                Subscribe
                            </h3>
                            <form action="#" class="newsletter_form">
                                <input type="text" placeholder="Enter your mail">
                                <button type="submit">Subscribe</button>
                            </form>
                            <p class="newsletter_text">Enter Your email address to subscribe to our newsletter and be updated.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copy-right_text wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".3s">
            <div class="container">
                <div class="footer_border"></div>
                <div class="row">
                    <div class="col-xl-12">
                        <p class="copy_right text-center">
                           
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved. Developed by <a href="#" target="_blank">ZamboImpact Technologies</a>

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--/ footer end  -->

    <!-- link that opens popup -->
    <!-- JS here -->
    <script src="js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="js/vendor/jquery-1.12.4.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/isotope.pkgd.min.js"></script>
    <script src="js/ajax-form.js"></script>
    <script src="js/waypoints.min.js"></script>
    <script src="js/jquery.counterup.min.js"></script>
    <script src="js/imagesloaded.pkgd.min.js"></script>
    <script src="js/scrollIt.js"></script>
    <script src="js/jquery.scrollUp.min.js"></script>
    <script src="js/wow.min.js"></script>
    <script src="js/nice-select.min.js"></script>
    <script src="js/jquery.slicknav.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/gijgo.min.js"></script>
    <script src="js/slick.min.js"></script>



    <!--contact js-->
    <script src="js/contact.js"></script>
    <script src="js/jquery.ajaxchimp.min.js"></script>
    <script src="js/jquery.form.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/mail-script.js"></script>


    <script src="js/main.js"></script>

</body>

</html>
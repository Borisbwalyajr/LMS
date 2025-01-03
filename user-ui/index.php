<?php
include "../connection.php";
session_start();
if(!isset($_SESSION['user_id']))
{
    echo "<script>
            alert('You are currently not logged in!');
            window.location.href='../index.html';
          </script>";
    exit;  // Always ensure the script exits after redirection to prevent further execution
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
    <style>
   body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    overflow-x: hidden;
}

.chat-popup {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #0078d7;
    color: #fff;
    padding: 15px 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
    justify-content: space-between;
    z-index: 1000;
    cursor: pointer;
    animation: bounce 1s infinite;
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.chat-popup a {
    color: #fff;
    text-decoration: ;
    font-weight: bold;
    margin-right: 10px;
}

.chat-popup a:hover {
    color: #ffd700;
}

.chat-popup span {
    font-size: 18px;
    cursor: pointer;
    margin-left: 10px;
}

.chat-popup.hidden {
    transform: translateY(100%);
    opacity: 0;
    pointer-events: none;
}

/* Keyframes for bounce animation */
@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

/* Optional pulsing effect for attention */
@keyframes pulse {
    0% {
        box-shadow: 0 0 10px rgba(0, 120, 215, 0.5);
    }
    50% {
        box-shadow: 0 0 20px rgba(0, 120, 215, 0.8);
    }
    100% {
        box-shadow: 0 0 10px rgba(0, 120, 215, 0.5);
    }
}

        </style>
</head>

<body>

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
                                            <li><a href="index.php#">Home</a></li>
                                            <li><a href="index.php#offer">Our Offer</a></li>
                                            <li><a href="index.php#about">About</a></li>
                                            <li><a href="index.php#how">How It Works</a></li>
                                            <li><a href="contact.html">Contact</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 d-none d-lg-block">
                                <div class="Appointment">
                                    <div class="phone_num d-none d-xl-block">
                                        <a href="#"> <i class="fa fa-phone"></i> +260 973 567 367</a>
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
    <div id="chat-popup" class="chat-popup">
        <p>Need help? <a href=""chat.php id="start-chat">Chat with us!</a></p>
        <span id="close-popup">&times;</span>
    </div>
    <!-- slider_area_start -->
    <div class="slider_area">
        <div class="single_slider  d-flex align-items-center slider_bg_1">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-7 col-md-6">
                        <div class="slider_text">
                            <h3 class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".1s">Get Loan for your Business growth or Pessonal startup</h3>
                            <div class="sldier_btn wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".2s">
                                <a href="#how" class="boxed-btn3">How it Works</a>
                            </div>
                        </div>
                    </div>
                    <?php
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
    $dueDate = new DateTime($activeLoan['due_date']);
    $today = new DateTime();
    $overdueDays = $today->diff($dueDate)->days;
    $amountOwed = $outstandingBalance + ($overdueDays * 5); // Assuming a charge of ZMW 5 per day overdue
}
?>

<div class="col-lg-5 col-md-6">
    <div class="payment_form white-bg wow fadeInDown" data-wow-duration="1.2s" data-wow-delay=".2s">
        <div class="info text-center">
            <h4>How much do you want <?php echo htmlspecialchars($user["full_name"]); ?>?</h4>
            <p>We provide instant cash loans with quick pay</p>
        </div>
        <div class="form">
            <?php if ($activeLoan): ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="single_input">
                            <p>Outstanding Balance: ZMW <?php echo number_format($amountOwed, 2); ?></p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="single_input">
                            <p>Due Date: <?php echo $dueDate->format('Y-m-d'); ?></p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="single_input">
                            <select class="wide" id="loan">
                                <option value="" disabled selected>Amount</option>
                                <option value="500">ZMW500</option>
                                <option value="1000">ZMW1000</option>
                                <option value="1500">ZMW1500</option>
                                <option value="2000">ZMW2000</option>
                                <option value="2500">ZMW2500</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="single_input">
                            <select class="wide" id="week">
                                <option value="" disabled selected>Week</option>
                                <option value="1">1 Week</option>
                                <option value="2">2 Weeks</option>
                                <option value="3">3 Weeks</option>
                                <option value="4">4 Weeks</option>
                            </select>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Display repayment amount -->
        <?php if ($activeLoan): ?>
            <p>You have to pay: ZMW <span id="repay"><?php echo number_format($amountOwed, 2); ?></span></p>
            <div class="submit_btn">
                <button class="boxed-btn3" onclick="pay();">Pay Now</button>
            </div>
        <?php else: ?>
            <p>You have to pay: ZMW <span id="repay">0</span></p>
            <div class="submit_btn">
                <button class="boxed-btn3" onclick="calculate();">Calculate</button>
            </div>
        <?php endif; ?>

        <br/>
        <div class="submit_btn">
            <?php if (!$activeLoan): ?>
                <button class="boxed-btn3" onclick="apply();">Apply Now</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Function to handle loan amount calculation (for users without an active loan)
    function calculate() {
        const loanAmount = document.getElementById("loan").value;
        const weeks = document.getElementById("week").value;
        const repayment = loanAmount * weeks * 0.1; // Example repayment formula (adjust as needed)
        
        // Display calculated repayment amount
        document.getElementById("repay").innerText = repayment.toFixed(2);
    }

    // Function to handle loan payment (for users with an outstanding loan)
    function pay() {
        // Handle payment logic (this could be a form submission or redirect to a payment page)
        window.location.href="repayment_page.php"
    }

    // Function to handle loan application submission (for users without an active loan)
    function apply() {
        // Perform form validation and submission logic here
        alert("Loan application submitted! Wait for Approve in 5 minutes");

    }
</script>

                            <script>
                                function apply(){
                                    window.location.assign("./apply.php");
                                }
                                function calculate() {
                                    const loan = document.getElementById("loan").value;
                                    const week = document.getElementById("week").value;

                                    // Check if valid values are selected
                                    if (!loan || !week) {
                                        alert("Please select both loan amount and duration!");
                                        return;
                                    }

                                    const loanAmount = parseInt(loan, 10);
                                    const numOfWeeks = parseInt(week, 10);

                                    let totalRepayment;

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

                                    // Update the repayment amount
                                    document.getElementById("repay").innerText = totalRepayment.toFixed(2);
                                }
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- slider_area_end -->

    <!-- service_area_start  -->
    <div class="service_area" id="offer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section_title text-center mb-90">
                        <span class="wow fadeInUp" data-wow-duration="1s" data-wow-delay=".1s"></span>
                        <h3 class="wow fadeInUp" data-wow-duration="1s" data-wow-delay=".2s">What we offer for you</h3>
                        <p class="wow fadeInUp" data-wow-duration="1s" data-wow-delay=".3s">We provide instant cash loans with quick approval that suit your term</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="single_service wow fadeInLeft" data-wow-duration="1.2s" data-wow-delay=".5s">
                        <div class="service_icon_wrap text-center">
                            <div class="service_icon ">
                                <img src="img/svg_icon/service_1.png" alt="">
                            </div>
                        </div>
                        <div class="info text-center">
                            <h3>1 WEEK</h3>
                        </div>
                        <div class="service_content">
                            <ul>
                                <li> Borrow - 1,000 over 1 week </li>
                                
                                <p>Total amount payable - ZMW1,300</p>
                            </ul>
                            <div class="apply_btn">
                                <button class="boxed-btn3" type="submit">Apply Now</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single_service wow fadeInUp" data-wow-duration="1s" data-wow-delay=".3s">
                        <div class="service_icon_wrap text-center">
                            <div class="service_icon ">
                                <img src="img/svg_icon/service_1.png" alt="">
                            </div>
                        </div>
                        <div class="info text-center">
                            <h3>2 WEEKS</h3>
                        </div>
                        <div class="service_content">
                            <ul>
                                <li> Borrow - 1,000 over 2 weeks </li>
                               
                                <p>Total amount payable - ZMW1,350</p>
                            </ul>
                            <div class="apply_btn">
                                <button class="boxed-btn3" type="submit">Apply Now</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single_service wow fadeInRight" data-wow-duration="1.2s" data-wow-delay=".5s">
                        <div class="service_icon_wrap text-center">
                            <div class="service_icon ">
                                <img src="img/svg_icon/service_1.png" alt="">
                            </div>
                        </div>
                        <div class="info text-center">
                            <h3>3 WEEKS</h3>
                        </div>
                        <div class="service_content">
                            <ul>
                                <li> Borrow - 1,000 over 3 weeks </li>
                                
                                <p>Total amount payable - ZMW1,400</p>
                            </ul>
                            <div class="apply_btn">
                                <button class="boxed-btn3" type="submit">Apply Now</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="single_service wow fadeInRight" data-wow-duration="1.2s" data-wow-delay=".5s">
                        <div class="service_icon_wrap text-center">
                            <div class="service_icon ">
                                <img src="img/svg_icon/service_1.png" alt="">
                            </div>
                        </div>
                        <div class="info text-center">
                            <h3>4 WEEKS</h3>
                        </div>
                        <div class="service_content">
                            <ul>
                                <li> Borrow - 1,000 over 4 weeks </li>
                                <p>Total amount payable - ZMW1,450</p>
                            </ul>
                            <div class="apply_btn">
                                <button class="boxed-btn3" type="submit">Apply Now</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- service_area_end  -->
    
    <!-- about_area_start  -->
    <div class="about_area" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6">
                    <div class="about_img wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".3s">
                        <img src="img/about/about.png" alt="">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="about_info pl-68">
                        <div class="section_title wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".3s">
                            <h3>Why Choose Us?</h3>
                        </div>
                        <p class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".4s">We provide instant cash loans with quick approval that suit your term</p>
                        <div class="about_list">
                            <ul>
                                <li class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".5s">Loans with quick approval.</li>
                                <li class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".6s">Customize a loan based on the amount.</li>
                                <li class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".7s">Good credit profile and you have built your loan.</li class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".8s">
                                <li class="wow fadeInRight" data-wow-duration="1s" data-wow-delay=".9s">We provide instant cash loans.</li>
                            </ul>
                        </div>
                        <div class="about_btn wow fadeInRight" data-wow-duration="1.3s" data-wow-delay=".5s">
                            <a class="boxed-btn3" href="apply.php">About Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- about_area_end  -->

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
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const chatPopup = document.getElementById("chat-popup");
        const closePopup = document.getElementById("close-popup");
        const startChat = document.getElementById("start-chat");

        // Show the popup after a delay
        setTimeout(() => {
            chatPopup.classList.remove("hidden");
        }, 3000); // Show after 3 seconds

        // Close the popup when the "×" is clicked
        closePopup.addEventListener("click", () => {
            chatPopup.classList.add("hidden");
        });

        // Handle the chat link click
        startChat.addEventListener("click", (e) => {
            e.preventDefault();
            // Redirect to chat.php
            window.location.href = "chat.php";
        });
    });
</script>


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
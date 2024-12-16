<?php
include_once("../connection.php");
// File upload directory
$uploadDir = 'uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Collect and sanitize inputs
        $fullName = htmlspecialchars($_POST['full_name']);
        $dob = $_POST['dob'];
        $email = htmlspecialchars($_POST['email']);
        $mobileNumber = htmlspecialchars($_POST['mobile_number']);
        $gender = $_POST['gender'];
        $occupation = htmlspecialchars($_POST['occupation']);
        $idType = htmlspecialchars($_POST['id_type']);
        $idNumber = htmlspecialchars($_POST['id_number']);
        $issuedAuthority = htmlspecialchars($_POST['issued_authority']);
        $addressType = htmlspecialchars($_POST['address_type']);
        $nationality = htmlspecialchars($_POST['nationality']);
        $province = htmlspecialchars($_POST['province']);
        $district = htmlspecialchars($_POST['district']);
        $kin1Relationship = $_POST['next_of_kin1_relationship'];
        $kin1Name = htmlspecialchars($_POST['next_of_kin1_name']);
        $kin1Phone = htmlspecialchars($_POST['next_of_kin1_phone']);
        $kin2Relationship = $_POST['next_of_kin2_relationship'];
        $kin2Name = htmlspecialchars($_POST['next_of_kin2_name']);
        $kin2Phone = htmlspecialchars($_POST['next_of_kin2_phone']);
        $password = htmlspecialchars($_POST['password']);
        $confirmPassword = htmlspecialchars($_POST['confirm_password']);

        if($password!=$confirmPassword)
        {
            echo(
                "
                <script>
                    alert('Passwords do not match');
                    window.location.href='signup.html';
                </script>
                "
            );
        }
        else
        {
            // check if user already exists
            $chk = $pdo->prepare("SELECT COUNT(*) AS possible from registrations where id_number = ? ");
            
            $chk->execute([$idNumber]);
            $row = $chk->fetch(PDO::FETCH_ASSOC);
            $possible = $row['possible']; // Number of matching rows

            if ($possible > 0) {
                echo(
                    "
                    <script>
                        alert('Oops! user with this NRC already exists');
                        window.location.href='signup.html';
                    </script>
                    "
                );
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Handle file uploads
                $idFrontPath = $uploadDir . basename($_FILES['id_front']['name']);
                $idBackPath = $uploadDir . basename($_FILES['id_back']['name']);
                $portraitPhotoPath = $uploadDir . basename($_FILES['portrait_photo']['name']);

                if (!move_uploaded_file($_FILES['id_front']['tmp_name'], $idFrontPath) ||
                    !move_uploaded_file($_FILES['id_back']['tmp_name'], $idBackPath) ||
                    !move_uploaded_file($_FILES['portrait_photo']['tmp_name'], $portraitPhotoPath)) {
                    throw new Exception("Failed to upload files.");
                }

                // Insert into database
                $stmt = $pdo->prepare("INSERT INTO registrations (
                    full_name, dob, email, mobile_number, gender, occupation, id_type, id_number, issued_authority, 
                    id_front_path, id_back_path, portrait_photo_path, address_type, nationality, province, district, 
                    next_of_kin1_relationship, next_of_kin1_name, next_of_kin1_phone, 
                    next_of_kin2_relationship, next_of_kin2_name, next_of_kin2_phone,password
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                
                $stmt->execute([
                    $fullName, $dob, $email, $mobileNumber, $gender, $occupation, $idType, $idNumber, $issuedAuthority, 
                    $idFrontPath, $idBackPath, $portraitPhotoPath, $addressType, $nationality, $province, $district, 
                    $kin1Relationship, $kin1Name, $kin1Phone, $kin2Relationship, $kin2Name, $kin2Phone,$hashedPassword
                ]);

                echo(
                    "
                    <script>
                        alert('You have successfully been Registered');
                        window.location.href='../index.php';
                    </script>
                    "
                );
            }
        }
            
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>

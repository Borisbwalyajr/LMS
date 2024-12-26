<?php
// Assuming you're using PDO to connect to the database
$dsn = 'mysql:host=localhost;dbname=loan_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the input data
    $data = json_decode(file_get_contents("php://input"));
    $nrc = $data->nrc;
    $referral_code = $data->referral_code;

    // Insert the referral code into the database
    $stmt = $pdo->prepare("INSERT INTO referralcode (nrc, code) VALUES (:nrc, :referral_code)");
    $stmt->bindParam(':nrc', $nrc);
    $stmt->bindParam(':referral_code', $referral_code);
    $stmt->execute();

    // Return success response
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    // Return error response
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>

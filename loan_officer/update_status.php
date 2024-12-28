<?php
include 'connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['loan_id']) && isset($data['status'])) {
    $loanId = $data['loan_id'];
    $status = $data['status'];

    $sql = "UPDATE loan_applications SET status = :status WHERE loan_id = :loan_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':loan_id', $loanId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}
?>

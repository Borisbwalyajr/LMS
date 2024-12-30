<?php
// Database connection
try {
    $con = new PDO("mysql:host=localhost; dbname=loan_db", 'root', '');
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

// Capture filters
$from = !empty($_POST['from']) ? $_POST['from'] : null;
$to = !empty($_POST['to']) ? $_POST['to'] : null;
$amount = !empty($_POST['amount']) ? $_POST['amount'] : null;
$loan_date = !empty($_POST['loan_date']) ? $_POST['loan_date'] : null;

// Base query
$query = "SELECT loan_id, nrc, amount, purpose, weeks, repayment, collateral_image, loan_date, due_date
          FROM loan_applications WHERE 1";

// Apply filters
$conditions = [];
if ($from && $to) {
    $conditions[] = "DATE(loan_date) BETWEEN :from AND :to";
}
if ($amount) {
    $conditions[] = "amount = :amount";
}
if ($loan_date) {
    $conditions[] = "loan_date = :loan_date";
}
if ($conditions) {
    $query .= " AND " . implode(" AND ", $conditions);
}

// Get totals
$totalsQuery = "SELECT SUM(amount) AS total_amount_distributed, SUM(repayment) AS total_income
                FROM loan_applications WHERE 1";
if ($conditions) {
    $totalsQuery .= " AND " . implode(" AND ", $conditions);
}

// Prepare statements
$stmt = $con->prepare($query);
$totalsStmt = $con->prepare($totalsQuery);

// Bind parameters
if ($from && $to) {
    $stmt->bindParam(':from', $from);
    $stmt->bindParam(':to', $to);
    $totalsStmt->bindParam(':from', $from);
    $totalsStmt->bindParam(':to', $to);
}
if ($amount) {
    $stmt->bindParam(':amount', $amount);
    $totalsStmt->bindParam(':amount', $amount);
}
if ($loan_date) {
    $stmt->bindParam(':loan_date', $loan_date);
    $totalsStmt->bindParam(':loan_date', $loan_date);
}

// Execute queries
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalsStmt->execute();
$totals = $totalsStmt->fetch(PDO::FETCH_ASSOC);

// Output JSON
echo json_encode([
    'data' => $data,
    'totals' => [
        'total_amount_distributed' => $totals['total_amount_distributed'] ?? 0,
        'total_income' => $totals['total_income'] ?? 0
    ]
]);
?>

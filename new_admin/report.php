<?php
session_start(); // Start the session
include("header.php");

// Database connection
try {
    $con = new PDO("mysql:host=localhost; dbname=loan_db", 'root', '');
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
</head>
<body style="background-color:">
    <div align="center" class="container mt-5" >
        <h5 align="center">Summary Report by Date & Activity Filter</h5><br>
        <h6 align="center">Search Date Range</h6><br>
        <form id="filterForm" class="myForm" method="post">
            <div class="form-row" align="left">
                <div class="form-group col-md-3">
                    <label>From Date:</label>
                    <input type="date" class="datepicker btn-block" name="from" id="fromDate">
                </div>
                <div class="form-group col-md-3">
                    <label>To Date:</label>
                    <input type="date" name="to" id="toDate" class="datepicker btn-block">
                </div>
                <div class="form-group col-md-3">
                    <label>Amount:</label>
                    <select class="custom-select" name="amount" id="amount">
                        <option value="">--Select Amount--</option>
                        <option value="500">500</option>
                        <option value="2000">2000</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Loan Date (value from other table)</label>
                    <?php
                    $loan_date_options = '';
                    $query = "SELECT DISTINCT status FROM loan_applications ORDER BY loan_date ASC";
                    $stmt = $con->query($query);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $loan_date_options .= '<option value="' . $row["status"] . '">' . $row["status"] . '</option>';
                    }
                    ?>
                    <select name="status" id="loan_date" class="custom-select">
                        <option value="">--Select Loan status--</option>
                        <?php echo $loan_date_options; ?>
                    </select>
                </div>
            </div>
            <div class="form-row" align="left">
                <div class="form-group col-md-3 offset-md-6">
                    <button type="button" id="resetFilters" class="btn btn-danger btn-block">Reset</button>
                </div>
                <div class="form-group col-md-3">
                    <button type="button" id="applyFilters" class="btn btn-primary btn-block">Apply</button>
                </div>
            </div>
        </form>
        <br>
        <div class="tg-wrap">
            <table id="table" class="display nowrap" cellspacing="0" style="width:100%">
                <thead align="center">
                    <tr>
                      
                        <td>NRC</td>
                        <td>Amount</td>
                        <td>Purpose</td>
                        <td>Weeks</td>
                        <td>Repayment</td>
                        <td>Collateral Image</td>
                        <td>Loan Date</td>
                        <td>Due Date</td>
                        <td>Total Amount</td>
                        <td>Total Repayment</td>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div align="center">
            <h5>Total Amount Distributed: <span id="totalDistributed">0</span></h5>
            <h5>Total Income Made: <span id="totalIncome">0</span></h5>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize DataTable with Buttons for export
            var table = $('#table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Summary Report',
                        text: 'Export to Excel'
                    },
                    {
                        extend: 'print',
                        title: 'Summary Report',
                        text: 'Print Report'
                    }
                ],
                ajax: {
                    url: 'filter_loans.php',
                    type: 'POST',
                    data: function(d) {
                        return $('#filterForm').serialize();
                    },
                    dataSrc: function(json) {
                        // Update totals
                        $('#totalDistributed').text(json.totals.total_amount_distributed);
                        $('#totalIncome').text(json.totals.total_income);

                        // Return row data
                        return json.data;
                    }
                },
                columns: [
                   
                    { data: 'nrc' },
                    { data: 'amount' },
                    { data: 'purpose' },
                    { data: 'weeks' },
                    { data: 'repayment' },
                    { data: 'collateral_image' },
                    { data: 'loan_date' },
                    { data: 'due_date' },
                    { data: null, render: function(data) { return data.amount; } },
                    { data: null, render: function(data) { return data.repayment; } }
                ]
            });

            // Apply filters
            $('#applyFilters').click(function() {
                table.ajax.reload();
            });

            // Reset filters
            $('#resetFilters').click(function() {
                $('#filterForm')[0].reset();
                table.ajax.reload();
            });
        });
    </script>
</body>
</html>

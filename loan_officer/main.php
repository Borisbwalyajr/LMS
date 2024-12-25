<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Admin Dashboard</title>
</head>

<body>
    
   <?php
   include 'sidebar.php';
   ?>

    <section class="dashboard">
        <div class="container">
            
        <style>
    .form-container {
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
    }
    label {
        display: block;
        margin-bottom: 5px;
    }
    #nrcSelect, #codeField {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    button {
        background-color: #28a745;
        color: white;
        border: none;
        cursor: pointer;
    }
    button:hover {
        background-color: #218838;
    }
</style>

<div class="form-container">
    <h2>Referral Form</h2>
    <form id="referralForm">
        <label for="nrcSelect">Select User NRC:</label>
        <select id="nrcSelect">
            <option value="">-- Select NRC --</option>
            <option value="123456/78/9">123456/78/9</option>
            <option value="987654/32/1">987654/32/1</option>
            <option value="456789/12/3">456789/12/3</option>
            <option value="321654/98/7">321654/98/7</option>
        </select>

        <label for="codeField">Generated Code:</label>
        <input type="text" id="codeField" disabled placeholder="Select an NRC to generate code">

    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to generate a random code
        function generateRandomCode() {
            return Math.random().toString(36).substring(2, 8).toUpperCase(); // Generates a random code
        }

        // Event listener for the NRC selection
        document.getElementById('nrcSelect').addEventListener('change', function() {
            const selectedNRC = this.value;
            const codeField = document.getElementById('codeField');

            // Generate and display the referral code if an NRC is selected
            if (selectedNRC) {
                const randomCode = generateRandomCode();
                codeField.value = randomCode; // Assign the generated code to the input field

                // Send the referral code and NRC to the server to store in the database
                fetch('save_code.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        nrc: selectedNRC,
                        referral_code: randomCode
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Code saved successfully.');
                    } else {
                        console.error('Error saving the code.');
                    }
                })
                .catch(error => console.error('Error:', error));
            } else {
                codeField.placeholder = "Select an NRC to generate code";
            }
        });
    });
</script>



        </div>
    </section>

    <script src="index.js"></script>
    
    <!-- Sources for icons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    
</body>

</html>
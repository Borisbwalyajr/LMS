document.addEventListener('DOMContentLoaded', function() {
    // Get the search input and table elements
    const searchInput = document.getElementById('search');
    const usersTable = document.getElementById('users-table');
    const tableBody = usersTable.querySelector('tbody');

    // Function to filter table rows based on search input
    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase(); // Get the search term and convert to lowercase
        const rows = tableBody.getElementsByTagName('tr'); // Get all rows in the table body

        // Loop through all rows and hide/show based on search term
        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td'); // Get all cells in the current row
            let rowVisible = false; // Flag to check if the row should be visible

            // Loop through each cell in the row
            for (let j = 0; j < cells.length; j++) {
                const cellText = cells[j].textContent.toLowerCase(); // Get cell text and convert to lowercase
                if (cellText.includes(searchTerm)) { // Check if cell text includes the search term
                    rowVisible = true; // Set flag to true if match found
                    break; // No need to check other cells in this row
                }
            }

            // Show or hide the row based on the flag
            rows[i].style.display = rowVisible ? '' : 'none';
        }
    });
});
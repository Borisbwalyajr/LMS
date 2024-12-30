document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("search");
    const loansTableBody = document.querySelector("#loans-table tbody");

    // Fetch approved loans from the backend
    fetch("fetch_approved_loans.php")
        .then((response) => response.json())
        .then((data) => {
            populateTable(data);
        })
        .catch((error) => {
            console.error("Error fetching loans:", error);
        });

    // Populate the table with loan data
    function populateTable(loans) {
        loansTableBody.innerHTML = ""; // Clear existing rows

        loans.forEach((loan) => {
            const row = document.createElement("tr");

            row.innerHTML = `
                <td>${loan.loan_id}</td>
                <td>${loan.user_nrc}</td>
                <td>${loan.disbursed_amount}</td>
                <td>${loan.purpose}</td>
                <td>${loan.weeks}</td>
                <td>${loan.repayment_amount}</td>
                <td>${loan.loan_date}</td>
                <td>${loan.due_date}</td>
            `;

            loansTableBody.appendChild(row);
        });
    }

    // Add search functionality
    searchInput.addEventListener("input", (event) => {
        const searchValue = event.target.value.toLowerCase();
        const rows = loansTableBody.querySelectorAll("tr");

        rows.forEach((row) => {
            const loanID = row.cells[0].textContent.toLowerCase();
            const userNRC = row.cells[1].textContent.toLowerCase();

            if (loanID.includes(searchValue) || userNRC.includes(searchValue)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
});

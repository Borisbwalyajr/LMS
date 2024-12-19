// Set the current year in the footer
document.getElementById('current-year').textContent = new Date().getFullYear();

document.querySelectorAll('.menu-btn').forEach(button => {
    button.addEventListener('click', function() {
        const contentType = this.getAttribute('data-content');
        loadContent(contentType);
    });
});

function loadContent(contentType = 'dashboard') { // Default to 'dashboard'
    const xhr = new XMLHttpRequest();
    let url = '';

    switch(contentType) {
        case 'dashboard':
            url = 'dashboard.html'; // Path to the dashboard content file
            break;
        case 'users':
            url = 'users.html'; // Path to the registered users content file
            break;
        case 'profile':
            url = 'profile.html'; // Path to the profile content file
            break;
        default:
            url = 'dashboard.html'; // Fallback to dashboard if no valid content type
            break;
    }

    xhr.open('GET', url, true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            document.getElementById('dynamic-content').innerHTML = xhr.responseText;
        } else {
            document.getElementById('dynamic-content').innerHTML = '<h2>Error</h2><p>Content could not be loaded.</p>';
        }
    };
    xhr.onerror = function() {
        document.getElementById('dynamic-content').innerHTML = '<h2>Error</h2><p>Request failed.</p>';
    };
    xhr.send();
}

// Call loadContent with default parameter on page load
document.addEventListener('DOMContentLoaded', function() {
    loadContent(); // Load dashboard content by default
});

// Toggle sidebar visibility for smaller screens
document.getElementById('hamburger-btn').addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('visible'); // Toggle the visible class
});

// Close sidebar when close button is clicked
document.getElementById('close-btn').addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.remove('visible'); // Hide the sidebar
});

$(document).ready(function() {
    // Initialize DataTable
    var table = $('#users-table').DataTable();

    // Search functionality
    $('#search').on('keyup', function() {
        table.search(this.value).draw();
    });
});

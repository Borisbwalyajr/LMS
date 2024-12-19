// Set the current year in the footer
document.getElementById('current-year').textContent = new Date().getFullYear();

document.querySelectorAll('.menu-btn').forEach(button => {
    button.addEventListener('click', function() {
        const contentType = this.getAttribute('data-content');
        loadContent(contentType);
    });
});

function loadContent(contentType) {
    const xhr = new XMLHttpRequest();
    let url = '';

    switch(contentType) {
        case 'dashboard':
            url = 'dashboard.html'; // Path to the dashboard content file
            break;
        case 'settings':
            url = 'settings.html'; // Path to the settings content file
            break;
        case 'profile':
            url = 'profile.html'; // Path to the profile content file
            break;
        default:
            document.getElementById('dynamic-content').innerHTML = '<h2>Welcome</h2><p>Select a menu item to see content here.</p>';
            return;
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

// Main application JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // get tokens
    const access_token = localStorage.getItem('access_token');
    const refresh_token = localStorage.getItem('refresh_token');

    // check if tokens are still valid


    // Mobile menu toggle
    const mobileMenuToggle = document.createElement('button');
    mobileMenuToggle.className = 'mobile-menu-toggle btn-icon';
    mobileMenuToggle.innerHTML = '<span>☰</span>';
    
    const headerLeft = document.querySelector('.header-left');
    if (headerLeft) {
        headerLeft.appendChild(mobileMenuToggle);
        
        mobileMenuToggle.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    }
    
    // New chat button
    const newChatBtn = document.getElementById('newChatBtn');
    if (newChatBtn) {
        newChatBtn.addEventListener('click', function(e) {
            e.preventDefault();
            alert('New chat functionality will be implemented here');
        });
    }
    
    // Tab functionality for admin page
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            tabButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            // Here you would load the appropriate tab content
        });
    });
    
    // File upload preview (for chat page)
    const attachBtn = document.querySelector('.attach-btn');
    if (attachBtn) {
        attachBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.multiple = true;
            fileInput.click();
            
            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    // Handle file upload preview
                    // display the file names in a preview area
                }
            });
        });
    }
    
    // User menu functionality
    const userMenu = document.querySelector('.user-menu');
    if (userMenu) {
        userMenu.addEventListener('click', function() {
            // This would toggle a dropdown menu in a real app
            window.location.href = '/user/settings';
        });
    }
});
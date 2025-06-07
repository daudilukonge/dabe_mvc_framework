import { showFlash } from './convo.js';

document.addEventListener('DOMContentLoaded', function () {

    /**
     * Function to update user active status
     */
    // set last activity
    let lastActivity = Date.now();

    // listen to user activities in page
    document.addEventListener('mousemove', () => lastActivity = Date.now());
    document.addEventListener('keydown', () => lastActivity = Date.now());

    setInterval(() => {
        
        const now = Date.now();
        const isIdle = now - lastActivity > 10 * 60 * 1000; // 10 minutes

        fetch('/api/user/user-active-status', {

            method: "POST",
            headers: {
                "ACCESS-TOKEN": "Bearer " + localStorage.getItem('access_token'),
                "REFRESH-TOKEN": "Bearer " + localStorage.getItem('refresh_token'),
                "Content-Type": "Application/json"
            },
            body: JSON.stringify({ idle: isIdle })

        })
        .then(result => result.json())
        .then(data => {
            
            if (data.status === 0 && (data.error_status === 'Expired Token')) {

                let flash_message = 'SESSION Expired!';
                let flash_type = 'error';
                showFlash(flash_message, flash_type);
                setTimeout(() => window.location.href = '/login', 2500);

            }
            
        })
        .catch(error => {

            console.log("Error: ", error);
            
        })

    }, 10000); // call function after every 10 seconds

});
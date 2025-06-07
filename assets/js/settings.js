/**
 * Settings JavaScript
 * User settings page functionality
 */
document.addEventListener('DOMContentLoaded', function() {

    // get avatar input and change button
    const changeAvatarButton = document.getElementById('change-avatar-btn');
    const avatarInput = document.getElementById('avatar-input');

    // function to trigger avatar input click
    function triggerAvatarInput() {
        avatarInput.click();
    }

    // Add event listeners for avatar change
    changeAvatarButton.addEventListener('click', triggerAvatarInput);

    // Handle avatar file selection
    avatarInput.addEventListener('change', function(event) {

        // get the selected file and read it
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const avatarPreview = document.getElementById('user-avatar');
                avatarPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);

            // change the button text, and background color to green, and font color to white
            changeAvatarButton.textContent = 'Apply Changes';
            changeAvatarButton.style.backgroundColor = 'green';
            changeAvatarButton.style.color = 'white';

            // remove the original click event
            changeAvatarButton.removeEventListener('click', triggerAvatarInput);


            // Add a click event to the button to submit the form
            changeAvatarButton.addEventListener('click', applyChangesHandler);
            async function applyChangesHandler(e) {

                // prevent default
                e.preventDefault();

                // Create a FormData object to send the file
                const formData = new FormData();
                formData.append('profile_picture', avatarInput.files[0]);

                // get tokens
                const access_token = localStorage.getItem('access_token');
                const refresh_token = localStorage.getItem('refresh_token');

                // check if tokens are present
                if (!access_token || !refresh_token) {
                    // redirect to login page
                    window.location.href = '/login';
                    return;
                }


                // change profile picture
                const url = '/api/users/settings/change-profile-picture';
                let data = await sendData(url, access_token, refresh_token, formData);

                // loader
                document.getElementById('loader-overlay').style.display = 'flex';
                
                if (data.status === 0 && data.error === true) {

                    if (data.error_status === 'Redirect') {

                        // redirect to logout
                        showFlash('Session Expired', 'error');
                        setTimeout(() => window.location.href = '/api/users/logout', 2500);
                        return;

                    } else if (data.error_status === 'User Side') {

                        // show error message
                        showFlash(data.message, 'error');
                        setTimeout(() => window.location.href = '/user/settings', 3000);

                        console.log(data);
                        
                        return;

                    }
                    
                }

                if (data.new_access_token_status === true) {

                    // store new access token
                    localStorage.setItem("access_token", data.data.access_token);
                    showFlash('Profile picture updated successfully!', 'success');
                    setTimeout(() => window.location.href = '/user/settings', 3000);

                } else {

                    // show success message
                    showFlash('Profile picture updated successfully!', 'success');
                    setTimeout(() => window.location.href = '/user/settings', 3000);

                }

                // remove the event listener
                changeAvatarButton.removeEventListener('click', applyChangesHandler);

            };

            
        }

    });



    /**
     * Function to send data
     */
    async function sendData(url, access_token, refresh_token, formData) {
        
        try {

            // fetch to verify tokens
            const response = await fetch(url, {

                method: 'POST',
                headers: {
                    "ACCESS-TOKEN": "Bearer " + access_token,
                    "REFRESH-TOKEN": "Bearer " + refresh_token,
                },
                body: formData

            });

            return await response.json();

        } catch (error) {

            console.log("Error: ", error);
            showFlash('Something went wrong. please try again.', 'error');
            
        }

    }



    /** 
     * Function to show flash message
     */
    function showFlash(message, type = 'success', duration = 5000) {

        const flash = document.getElementById('flash-message');

        // if (!flash) return;

        // Set message and style class
        flash.textContent = message;
        flash.className = `flash-message flash-${type}`;
        // flash.style.display = 'block';
        flash.style.opacity = '1';

        // Auto-hide after `duration` ms
        setTimeout(() => {
            flash.style.opacity = '0';
            setTimeout(() => {
                flash.style.display = 'none';
            }, 500); // fade out
        }, duration);

    }
});
/**
 * 
 * Authentication JavaScript
 * Login and Register pages authentication
 * 
 */
document.addEventListener('DOMContentLoaded', function() {


    /**
     * Login form authentication
     */
    const loginForm = document.getElementById('loginForm');
    // check login form
    if (loginForm) {

        // login form submit function
        loginForm.addEventListener('submit', async function(e) {

            // prevent defaults
            e.preventDefault();

            // loader
            document.getElementById('loader-overlay').style.display = 'flex';

            const csrf_token = document.getElementById('x-site-verify').value;
            const formdata = new FormData(loginForm);
            const formObject = {};
            formdata.forEach((formValue, keyInput) => {

                formObject[keyInput] = formValue;

            });


            const url = "/api/users/login";       
            let data = await sendAuthData(url, csrf_token, formObject);

            if (data.status === 0 && data.error === true) {

                // reload the page, display flash message with error message
                showFlash(data.message, 'error');               
                setTimeout(() => window.location.href = '/login', 2500);

            } else {

                // user successfully logged in, save tokens to the local storage
                localStorage.setItem("access_token", data.access_token);
                localStorage.setItem("refresh_token", data.refresh_token);

                // redirect user
                showFlash('Loading your data...', 'success');
                sessionStorage.setItem('flash_message', data.message);
                sessionStorage.setItem('flash_type', 'success');
                setTimeout(() => window.location.href = '/user/conversations', 3000);

            }
            
        });

    }


    

    /**
     * Register authentication
     */
    const registerForm = document.getElementById('registerForm');
    // check register form 
    if (registerForm) {

        // register form submit function
        registerForm.addEventListener('submit', async function(e) {

            // prevent default
            e.preventDefault();

            // loader
            document.getElementById('loader-overlay').style.display = 'flex';
            
            // get passwords values
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            // check password confirmation
            if (password === '' || (password !== confirmPassword)) {
                
                // show flash message
                showFlash('Please confirm password', 'error');
                setTimeout(() => window.location.href = '/register', 2500);
                return;

            }

            // get form values
            const csrf_token = document.getElementById('x-site-verify').value;
            const formdata = new FormData(registerForm);
            const formObject = {};
            formdata.forEach((formValue, keyInput) => {

                formObject[keyInput] = formValue;

            });

            const url = '/api/users/register';
            let data = await sendAuthData(url, csrf_token, formObject);

            if (data.status === 0 && data.error === true) {

                // reload the page, display flash message with error message
                showFlash(data.message, 'error');               
                setTimeout(() => window.location.href = '/register', 4000);

            } else {

                // redirect user
                showFlash('Processing your data...', 'success');
                sessionStorage.setItem('flash_message', data.message);
                sessionStorage.setItem('flash_type', 'success');
                setTimeout(() => window.location.href = '/user/register/otp-verification-page', 3000);

            }
            
        });
    }



    /**
     * Email confirmation 
     */
    const otpForm = document.getElementById('otpForm');
    // check otp form
    if (otpForm) {

        // Function to handle OTP input
        otpForm.addEventListener('submit', async function (e) {

            // prevent default
            e.preventDefault();

            // loader
            document.getElementById('loader-overlay').style.display = 'flex';

            // get form values
            csrf_token = document.getElementById('x-token-verify').value;
            const formdata = new FormData(otpForm);
            const formObject = {};
            formdata.forEach((formValue, keyInput) => {

                formObject[keyInput] = formValue;

            });

            const url = '/api/users/register/otp-verification';
            let data = await sendAuthData(url, csrf_token, formObject);


            if (data.status === 0 && data.error === true) {

                // reload the page, display flash message with error message
                showFlash(data.message, 'error');               
                setTimeout(() => window.location.href = '/user/register/otp-verification-page', 4000);

            } else {

                // store tokens in local storage
                localStorage.setItem("access_token", data.access_token);
                localStorage.setItem("refresh_token", data.refresh_token);

                // redirect user
                showFlash('Verifying your email...', 'success');
                sessionStorage.setItem('flash_message', data.message);
                sessionStorage.setItem('flash_type', 'success');
                setTimeout(() => window.location.href = '/user/conversations', 3000);

            }

        });

    }



    /**
     * Function to send data to the server
     */
    async function sendAuthData(url, csrf_token, formObject) {

        try {

            // fetch to send data to server
            const response = await fetch(url, {

                method: 'POST',
                headers: {
                    "X-CSRF-Token": "Bearer " + csrf_token,
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({formObject})

            });

            return await response.json();

        } catch(error) {

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
document.addEventListener('DOMContentLoaded', function() {
    // Get all the OTP input fields
    const digit1 = document.getElementById('digit1');
    const digit2 = document.getElementById('digit2');
    const digit3 = document.getElementById('digit3');
    const digit4 = document.getElementById('digit4');
    const digit5 = document.getElementById('digit5');
    const digit6 = document.getElementById('digit6');
    const otpFields = [digit1, digit2, digit3, digit4, digit5, digit6];
    const fullOtp = document.getElementById('fullOtp');
    const verifyBtn = document.getElementById('verifyBtn');
    const resendBtn = document.getElementById('resendBtn');
    const countdownElement = document.getElementById('countdown');

    // Auto-focus first digit on load
    digit1.focus();

    // Handle OTP input navigation
    otpFields.forEach((field, index) => {
        field.addEventListener('input', function() {
            if (this.value.length === 1) {
                if (index < otpFields.length - 1) {
                    otpFields[index + 1].focus();
                } else {
                    this.blur();
                    verifyBtn.focus();
                }
            }
            
            updateFullOtp();
        });

        field.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                otpFields[index - 1].focus();
            }
        });
    });

    // Update the hidden full OTP field
    function updateFullOtp() {
        fullOtp.value = otpFields.map(field => field.value).join('');
    }

    
    // Resend OTP functionality
    let countdown = 60; // 60 seconds countdown
    let countdownInterval;
    
    function startCountdown() {
        resendBtn.disabled = true;
        countdown = 60;
        updateCountdownDisplay();
        
        countdownInterval = setInterval(() => {
            countdown--;
            updateCountdownDisplay();
            
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                resendBtn.disabled = false;
            }
        }, 1000);
    }
    
    function updateCountdownDisplay() {
        const minutes = Math.floor(countdown / 60);
        const seconds = countdown % 60;
        countdownElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
    
    // Start the initial countdown
    startCountdown();
});
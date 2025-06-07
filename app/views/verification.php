<?php
    /**
     * 
     * Verification vew file
     * 
     */
    use App\Core\Session;

    // check if csrf token is available
    if (!isset($_SESSION['csrf_token'])) {

        // reload the page
        Header('Location: ' . $helpers::route('/register'));
        exit;

    }

    $userData = Session::getUser();
    if (!$userData) {

        // redirect to register page
        Header('Location: ' . $helpers::route('/register'));
        exit;

    }

    $email = $userData['email'];
    $otp_reason = $userData['otp_reason'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            /**
             * Header file
             */
            require_once 'layout/header.php';

        ?>
    </head>
    <body>
        <div class="verification-container">
            <div class="verification-card">
                <div class="verification-header">
                    <div class="logo">
                        <img src="<?= $helpers::asset('logo.png') ?>" alt="<?= htmlspecialchars($siteName) ?> Logo" loading="lazy">
                        <span><?= htmlspecialchars($siteName) ?></span>
                    </div>
                    <h2>Verify Your Email Address</h2>
                    <p>We've sent a 6-digit verification code to <strong id="userEmail"> <?= $email ?> </strong></p>
                </div>

                <form id="otpForm" class="verification-form">
                    <div class="otp-input-group">
                        <span>Enter Verification Code</span>
                        <div class="otp-fields" id="otp">
                            <input type="text" id="digit1" maxlength="1" pattern="[0-9]" inputmode="numeric" autofocus>
                            <input type="text" id="digit2" maxlength="1" pattern="[0-9]" inputmode="numeric">
                            <input type="text" id="digit3" maxlength="1" pattern="[0-9]" inputmode="numeric">
                            <input type="text" id="digit4" maxlength="1" pattern="[0-9]" inputmode="numeric">
                            <input type="text" id="digit5" maxlength="1" pattern="[0-9]" inputmode="numeric">
                            <input type="text" id="digit6" maxlength="1" pattern="[0-9]" inputmode="numeric">
                        </div>
                        <input type="hidden" id="fullOtp" name="user_otp">
                        <input type="hidden" id="x-token-verify" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" id="email" name="email" value="<?= htmlspecialchars($email) ?>">
                        <input type="hidden" id="otp-reason" name="otp_reason" value="<?= htmlspecialchars($otp_reason) ?>">
                    </div>

                    <div class="verification-footer">
                        <button type="submit" class="btn-primary" id="verifyBtn">Verify Email</button>
                        <div class="resend-section">
                            <p>Didn't receive the code? <span id="countdown">01:00</span></p>
                            <button type="button" class="btn-text" id="resendBtn" disabled>Resend Code</button>
                        </div>
                    </div>
                </form>

                <div class="verification-success" id="successMessage" style="display: none;">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3>Email Verified Successfully!</h3>
                    <p>Your email address has been verified. You can now access all features of FileShare.</p>
                    <a href="conversations.html" class="btn-primary">Continue to Dashboard</a>
                </div>
            </div>
        </div>


        <!-- Loader Overlay -->
        <div id="loader-overlay" class="hidden">
            <div class="loader-box">
                <!-- Flash Message Container -->
                <div id="flash-message" class="flash-message"></div>

                <div id="loader-message" class="loader-title">Loading...</div>
                <div class="dots">
                    <span class="dot dot1"></span>
                    <span class="dot dot2"></span>
                    <span class="dot dot3"></span>
                    <span class="dot dot4"></span>
                </div>
            </div>
        </div>



        <script src="<?= $helpers::asset('js/loader.js?v='. time()) ?>"></script>
        <script src="<?= $helpers::asset('js/verify.js?v=' . time()) ?>"></script>
        <script src="<?= $helpers::asset('js/auth.js?v=' . time()) ?>"></script>
    </body>
</html>
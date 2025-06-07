<?php
    /**
     * Login view file
     * to handle the login page
     */

    use App\Core\Session;

    $siteName ??= 'ShareNami';
    $pageName ??= 'Login';
    $pageDescription ??= 'Share files securely with other users';

    // check if csrf token is available
    if (!isset($_SESSION['csrf_token'])) {

        // reload the page
        Header('Location: ' . $helpers::route('/login'));
        exit;

    }

?>

<!DOCTYPE html>
<html lang="en">
    <?php 
        /**
         * Header file for the login page
        */
        require_once 'layout/header.php'; 
    ?>
    <body>
        <div class="auth-wrapper">
            <div class="auth-container">
                <div class="logo"> 
                    <div>
                        <img src="<?= $helpers::asset('logo.png') ?>" alt="<?= htmlspecialchars($siteName) ?> Logo" loading="lazy">
                        <span><?= htmlspecialchars($siteName) ?></span>
                    </div>
                    <p><?= htmlspecialchars($pageDescription) ?></p>
                </div>

                <form method="post" action="<?= $helpers::route('/users/login') ?>" id="loginForm" class="auth-form">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password">
                        <input type="hidden" id="x-site-verify" value="<?= $_SESSION['csrf_token'] ?>">
                    </div>
                    <button type="submit" class="btn-primary">Login</button>
                    <p class="auth-link">Don't have an account? <a href="/register">Sign up</a></p>
                </form>

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


        

        <!-- Add Loader js file source here -->
        <script src="<?= $helpers::asset('/js/auth.js?v='. time(). '') ?>"></script>
        <script src="<?= $helpers::asset('/js/loader.js?v='. time(). '') ?>"></script>
    </body>
</html>
<?php

    /**
     * Settings view
     */

    // user session
    use App\Core\Session;

    // check if user is logged in
    if (Session::isLoggedIn() !== true) {

        // redirect to login page
        $helpers::redirect("/login");
        
    }

    // get user data from database
    $sessionData = Session::getUser(); 
    $userRole = $sessionData['role'] ?? 'user';
    $user_email = $sessionData['email'];

    [$userdataStatus, $userData] = $User->getUserData($user_email);
    if ($userdataStatus === false) {

        // user data not found, redirect to login
        $helpers::redirect('login');

    } 

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            /**
             * include header file
             */
            require_once 'layout/header.php';
        ?>
    </head>
    <body>
        <div class="app-container">
            <?php
                /**
                 * include header file
                 */
                require_once 'layout/container-header.php';
            ?>


            <div class="main-content">
                <aside class="sidebar">
                    <div class="search-box">
                        <input type="text" placeholder="Search conversations...">
                    </div>
                    <div class="conversation-list">
                        <div class="conversation">
                            <div class="conversation-avatar">JD</div>
                            <div class="conversation-details">
                                <h4>John Doe</h4>
                                <p>project-final.zip</p>
                            </div>
                            <span class="conversation-time">2h ago</span>
                        </div>
                        <div class="conversation">
                            <div class="conversation-avatar">AS</div>
                            <div class="conversation-details">
                                <h4>Alice Smith</h4>
                                <p>meeting-notes.pdf</p>
                            </div>
                            <span class="conversation-time">1d ago</span>
                        </div>
                    </div>
                    <?php
                        /**
                         * include aside links file
                         */
                        $active_page_convo = '';
                        $active_page_users = '';
                        $active_page_settings = 'active';
                        require_once 'layout/aside-links.php';
                    ?>
                </aside>

                <main class="content-area settings-area">
                    <h2>Account Settings</h2>

                    <div class="settings-section">

                        <h3>Personal details</h3>
                        <div class="settings-avatar">
                            <img src="<?= $helpers::asset($userData['profile_image']) ?>" alt="User" class="avatar-large" id="user-avatar">
                            <form id="change-avatar-form">
                                <input type="file" id="avatar-input" accept="image/*" style="display: none;">
                                <button type="button" class="btn-secondary" id="change-avatar-btn">Change Avatar</button>
                            </form>
                        </div>
                        
                        <form class="settings-form" id="personal-details-form">
                            <div class="form-group">
                                <label for="settings-name">Full Name</label>
                                <input type="text" id="settings-name" value="<?= htmlspecialchars($userData['name'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label for="settings-email">Email</label>
                                <input type="email" id="settings-email" value="<?= htmlspecialchars($userData['email'] ?? '') ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="settings-oldpassword">Old Password</label>
                                <input type="password" id="settings-oldpassword" placeholder="Write your old password to change">
                            </div>
                            <div class="form-group">
                                <label for="settings-newpassword">New Password</label>
                                <input type="password" id="settings-newpassword" placeholder="Write a new password to change">
                            </div>
                            <div class="form-group">
                                <label for="settings-confirm">Confirm Password</label>
                                <input type="password" id="settings-confirm" placeholder="Confirm your new password">
                            </div>
                            <button type="submit" class="btn-primary">Save Changes</button>
                        </form>

                        <h3 style="margin-top: 20px;">Create your unique username</h3>
                        <div class="username-disc">
                            <i class="fa-brands fa-readme"></i>
                            <span>This username will appear <strong>ONLY</strong> to users that you do not wish to see your full name. You can change who can see your full name in <strong>Privacy and Security</strong> section below.</span>
                        </div>
                        <form class="settings-form username" id="username-form">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="input-username" placeholder="Write new username here...">
                            </div>
                            <button type="submit" class="btn-primary">Save username</button>
                        </form>

                    </div>

                    <div class="settings-section">
                        <h3>Unique Search ID <i class="fas fa-info-circle" style="color: #999;"></i></h3>
                    </div>

                    <div class="settings-section">
                        <h3>Privacy and Security <i class="fas fa-info-circle" style="color: #999;"></i></h3>
                        <div class="privacy-section">
                            <div class="left-div">
                                <h5>Account Visibility</h5>
                                <div class="left-data">
                                    <?php if ($userData['visibility'] === 'Public'): ?>
                                        <i class="fa fa-globe" aria-hidden="true"></i>
                                    <?php elseif ($userData['visibility'] === 'Private'): ?>
                                        <i class="fa fa-lock" aria-hidden="true"></i>
                                    <?php endif ?>
                                    <span class="left-data-value"><?= htmlspecialchars($userData['visibility']) ?></span>
                                </div>
                            </div>

                            <div class="right-div">
                                <select name="user-visibility" id="user-visibility-option">
                                    <option value="Public">Public</option>
                                    <option value="Private">Private</option>
                                </select>
                                <div class="right-action">
                                    <button class="btn-primary" id="user-visibility-button">Change</button>
                                </div>
                            </div>
                        </div>

                        <div class="privacy-section">
                            <div class="left-div">
                                <h5>Receive Files From</h5>
                                <div class="left-data">
                                    <?php if ($userData['visibility'] === 'Public'): ?>
                                        <i class="fa fa-globe" aria-hidden="true"></i>
                                        <span>Everyone</span>
                                    <?php elseif ($userData['visibility'] === 'Private'): ?>
                                        <i class="fa fa-lock" aria-hidden="true"></i>
                                        <span>Friends</span>
                                    <?php endif ?>
                                </div>
                            </div>

                            <div class="right-div">
                                <select name="user-visibility" id="user-visibility-option">
                                    <option value="Public">Everyone</option>
                                    <option value="Private">Friends</option>
                                </select>
                                <div class="right-action">
                                    <button class="btn-primary" id="message-from-button">Change</button>
                                </div>
                            </div>
                        </div>

                        <div class="privacy-section">
                            <div class="left-div">
                                <h5>Who can see my email</h5>
                                <div class="left-data">
                                    <?php if ($userData['visibility'] === 'Public'): ?>
                                        <i class="fa fa-globe" aria-hidden="true"></i>
                                        <span>Everyone</span>
                                    <?php elseif ($userData['visibility'] === 'Private'): ?>
                                        <i class="fa fa-lock" aria-hidden="true"></i>
                                        <span>Friends</span>
                                    <?php endif ?>
                                </div>
                            </div>

                            <div class="right-div">
                                <select name="user-visibility" id="user-visibility-option">
                                    <option value="Public">Everyone</option>
                                    <option value="Private">Friends</option>
                                    <option value="Fovourites">Favourites</option>
                                </select>
                                <div class="right-action">
                                    <button class="btn-primary" id="see-email-button">Change</button>
                                </div>
                            </div>
                        </div>

                        <div class="privacy-section">
                            <div class="left-div">
                                <h5>Who can see my name</h5>
                                <div class="left-data">
                                    <?php if ($userData['visibility'] === 'Public'): ?>
                                        <i class="fa fa-globe" aria-hidden="true"></i>
                                        <span>Everyone</span>
                                    <?php elseif ($userData['visibility'] === 'Private'): ?>
                                        <i class="fa fa-lock" aria-hidden="true"></i>
                                        <span>Friends</span>
                                    <?php endif ?>
                                </div>
                            </div>

                            <div class="right-div">
                                <select name="user-visibility" id="user-visibility-option">
                                    <option value="Public">Everyone</option>
                                    <option value="Private">Friends</option>
                                    <option value="Fovourites">Favourites</option>
                                </select>
                                <div class="right-action">
                                    <button class="btn-primary" id="see-email-button">Change</button>
                                </div>
                            </div>
                        </div>

                        <div class="privacy-section">
                            <div class="left-div">
                                <h5>Who can see my profile image</h5>
                                <div class="left-data">
                                    <?php if ($userData['visibility'] === 'Public'): ?>
                                        <i class="fa fa-globe" aria-hidden="true"></i>
                                        <span>Everyone</span>
                                    <?php elseif ($userData['visibility'] === 'Private'): ?>
                                        <i class="fa fa-lock" aria-hidden="true"></i>
                                        <span>Friends</span>
                                    <?php endif ?>
                                </div>
                            </div>

                            <div class="right-div">
                                <select name="user-visibility" id="user-visibility-option">
                                    <option value="Public">Everyone</option>
                                    <option value="Private">Friends</option>
                                    <option value="Fovourites">Favourites</option>
                                </select>
                                <div class="right-action">
                                    <button class="btn-primary" id="see-email-button">Change</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="settings-section">
                        <h3>Storage</h3>
                        <div class="storage-meter">
                            <div class="meter-bar" style="width: 65%"></div>
                            <span>65% used (13.2 GB of 20 GB)</span>
                        </div>
                        <button class="btn-secondary">Upgrade Storage</button>
                    </div>

                    <div class="settings-section danger-zone">
                        <h3>Danger Zone</h3>
                        <button class="btn-danger">Delete Account</button>
                    </div>
                </main>
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

        <!-- Flash Message Container -->
        <div id="flash-message" class="flash-message"></div>


        <script src="<?= $helpers::asset('js/app.js?v='. time()) ?>"></script>
        <script src="<?= $helpers::asset('js/settings.js?v='. time()) ?>"></script>
        <script src="<?= $helpers::asset('js/loader.js?v='. time()) ?>"></script>
        <script type="module" src="<?= $helpers::asset('js/active-status.js?v='. time()) ?>"></script>
    </body>
</html>
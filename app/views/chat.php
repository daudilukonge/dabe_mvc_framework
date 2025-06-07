<?php

    /**
     * 
     * Chat view file
     * 
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
                        <div class="conversation active">
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
                        $active_page_settings = '';
                        $active_page_admin = '';
                        require_once 'layout/aside-links.php';
                    ?>
                </aside>


                <main class="content-area chat-area">
                    <div class="chat-header">
                        <div class="chat-user">
                            <div class="chat-avatar">AS</div>
                            <h3>Alice Smith</h3>
                        </div>
                        <div class="chat-actions">
                            <button class="btn-icon" style="display: block;">
                                <span>Call</span>
                            </button>
                            <button class="btn-icon" style="display: block;">
                                <span>Info</span>
                            </button>
                        </div>
                    </div>

                    <div class="chat-messages">
                        <div class="message received">
                            <div class="message-content">
                                <p>Hi there! Here's the file you requested.</p>
                                <div class="file-preview">
                                    <div class="file-icon pdf"></div>
                                    <div class="file-info">
                                        <h5>meeting-notes.pdf</h5>
                                        <p>2.4 MB</p>
                                    </div>
                                    <a href="#" class="download-btn">Download</a>
                                </div>
                            </div>
                            <span class="message-time">10:30 AM</span>
                        </div>

                        <div class="message sent">
                            <div class="message-content">
                                <p>Thanks! I've uploaded the images we discussed.</p>
                                <div class="file-preview">
                                    <div class="file-icon zip"></div>
                                    <div class="file-info">
                                        <h5>project-images.zip</h5>
                                        <p>15.7 MB</p>
                                    </div>
                                    <a href="#" class="download-btn">Download</a>
                                </div>
                            </div>
                            <span class="message-time">10:32 AM</span>
                        </div>
                    </div>
                    <div class="chat-input">
                        <button class="btn-icon attach-btn" style="display: block;">
                            <i class="fa-solid fa-paperclip"></i>
                        </button>
                        <input type="text" placeholder="Type a message...">
                        <button class="btn-primary send-btn">Send</button>
                    </div>
                </main>
            </div>
        </div>

        <!-- Flash Message Container -->
        <div id="flash-message" class="flash-message"></div>


        <script src="<?= $helpers::asset('js/app.js?v='. time()) ?>"></script>
        <script src="<?= $helpers::asset('js/chat.js?v='. time()) ?>"></script>
        <script type="module" src="<?= $helpers::asset('js/active-status.js?v='. time()) ?>"></script>
    </body>
</html>
<?php
    // Conversation page

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
                        $active_page_convo = 'active';
                        $active_page_users = '';
                        $active_page_settings = '';
                        require_once 'layout/aside-links.php';
                    ?>
                </aside>
                <main class="content-area">
                    <div class="empty-state">
                        <img src="<?= $helpers::asset('select-convo.png') ?>" alt="Select a conversation">
                        <h2>Select a conversation</h2>
                        <p>Choose an existing chat or start a new one</p>
                    </div>
                </main>
            </div>

            <div class="new-chat-btn" title="New Chat">
                <i class="fa-solid fa-plus fa-2x"></i>
            </div>
        </div>

        <!-- Flash Message Container -->
        <div id="flash-message" class="flash-message"></div>

        <script src="<?= $helpers::asset('js/app.js?v='. time()) ?>"></script> 
        <script type="module" src="<?= $helpers::asset('js/convo.js?v='. time()) ?>"></script> 
        <script type="module" src="<?= $helpers::asset('js/active-status.js?v='. time()) ?>"></script> 
    </body>
</html>
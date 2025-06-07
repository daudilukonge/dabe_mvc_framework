<?php

    /**
     * 
     * Users list view file
     * 
     */

    // user session
    use App\Core\Session;

    // check if user is logged in
    if (Session::isLoggedIn() !== true) {

        // user is not logged in, set flash message
        Session::setFlash('error', 'You must be logged in to access the page.');

        // redirect to login page
        Header('Location: ' . $helpers::route('/login'));
        exit();
        
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

    // user active status
    if ($userData['active_status'] === 1) {

        $userActiveStatus = 'online';

    } else {

        $userActiveStatus = 'offline';

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
                        $active_page_users = 'active';
                        $active_page_settings = '';
                        require_once 'layout/aside-links.php';
                    ?>
                </aside>

                <main class="content-area users-area">
                    <div class="users-header">
                        <h2>All Users</h2>
                        <div class="search-filter">
                            <input type="text" placeholder="Search users..." class="search-input" id="search-input">
                            <select>
                                <option>All Users</option>
                                <option>Online</option>
                                <option>Offline</option>
                            </select>
                        </div>
                        <div class="search-filter icons">
                            <button class="btn-secondary search-button" id="search-button">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                            <button class="btn-secondary filter-button" id="filter-button">
                                <i class="fa fa-sliders" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="users-header mobile-header">
                        <div class="search-filter icons" id="search-filter-mobile">
                            <input type="text" placeholder="Search users..." class="search-input" id="search-input-mobile" autofocus>
                        </div>
                    </div>


                    <div class="users-list">

                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <div class="user-card">
                                    <div class="user-avatar">
                                        <img src="<?= $helpers::asset($user['profile_image']) ?>" alt="<?= htmlspecialchars($user['name'][0]) ?>" class="avatar" <?php if ($user['active_status'] == 1): ?> style="border: 4px double #00b007;" <?php endif; ?>>
                                    </div>
                                    <div class="user-info">
                                        <h4><?= htmlspecialchars($user['name']) ?></h4>
                                        <p><?= htmlspecialchars($user['email']) ?></p>
                                        <?php if ($user['active_status'] == 1): ?>
                                            <span class="user-status online">Online</span>
                                        <?php else: ?>
                                            <span class="user-status offline">Offline</span>
                                        <?php endif; ?>
                                        <button class="btn-secondary action-mobile">
                                            <i class="fa-solid fa-message"></i>
                                        </button>
                                        <button class="btn-secondary action-mobile">
                                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="action-div">
                                        <button class="btn-secondary action">
                                            <i class="fa-solid fa-message"></i>
                                        </button>
                                        <button class="btn-secondary action">
                                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div> 
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="no-users">No Users Found</p>
                        <?php endif; ?>

                    </div>
                </main>
            </div>
        </div>

        <!-- Flash Message Container -->
        <div id="flash-message" class="flash-message"></div>

        <script src="<?= $helpers::asset('js/app.js?v='. time()) ?>"></script>
        <script type="module" src="<?= $helpers::asset('js/active-status.js?v='. time()) ?>"></script>
    </body>
</html>
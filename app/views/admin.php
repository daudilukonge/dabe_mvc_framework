<?php

    /**
     * 
     * Admin view file
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

    // authorize only admin to access this page
    if ($userRole !== 'admin') {

        // user is not admin
        $helpers::redirect('/user/conversations');

    }

    // total users
    $totalUsers = $User->countUsers();

    // user active status
    $userActiveStatus = $userData['active_status'] === 1 ? 'Online' : 'Offline';

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
                        $active_page_settings = '';
                        $active_page_admin = 'active';
                        require_once 'layout/aside-links.php';
                    ?>
                </aside>

                <main class="content-area admin-area">
                    <h2>Admin Dashboard - <i class="admin-name"><?= $userData['name'] ?></i></h2><br>
                    <div class="admin-stats">
                        <div class="stat-card">
                            <h3>Total Users</h3>
                            <p><?= htmlspecialchars($totalUsers) ?></p>
                        </div>
                        <div class="stat-card">
                            <h3>Active Today</h3>
                            <p>342</p>
                        </div>
                        <div class="stat-card">
                            <h3>Files Shared</h3>
                            <p>5,672</p>
                        </div>
                        <div class="stat-card">
                            <h3>Storage Used</h3>
                            <p>1.2 TB</p>
                        </div>
                    </div>
                    <div class="admin-tabs">
                        <button class="tab-btn active">Users</button>
                        <button class="tab-btn">Files</button>
                        <button class="tab-btn">Reports</button>
                        <button class="tab-btn">Settings</button>
                    </div>
                    <div class="admin-table-container">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Last Active</th>
                                    <th>Storage Used</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td>
                                                <div class="user-cell">
                                                    <div class="user-avatar" <?php if ($user['active_status'] == 1): ?> style="border: 4px double #00b007;" <?php endif; ?>>
                                                        <img src="<?= $helpers::asset($user['profile_image']) ?>" alt="<?= htmlspecialchars($user['name'][0]) ?>" class="avatar">
                                                    </div>
                                                    <span>
                                                        <?= htmlspecialchars($user['name']) ?> <br> 
                                                        <i><?= htmlspecialchars($user['role']) ?></i>
                                                    </span>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($user['email']) ?></td>
                                            <td><span class="status-badge active"><?= htmlspecialchars($user['status']) ?></span></td>
                                            <td>
                                                <?php if ($user['active_status'] == 1): ?>
                                                    <span style="color: #00b007;">Online</span>
                                                <?php elseif ($user['last_seen'] == null): ?>
                                                    Unavailable
                                                <?php else: ?>
                                                    <?php
                                                        $lastSeen = $user['last_seen'];
                                                        $lastseentime = $User->getTimeAgo($lastSeen);
                                                        echo $lastseentime;
                                                    ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>15.2 GB</td>
                                            <td>
                                                <button class="btn-icon small" style="display: flex;">Edit</button>
                                                <button class="btn-icon small danger" style="display: flex;">Suspend</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                
                            </tbody>
                        </table>
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
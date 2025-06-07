<?php

    /**
     * 
     * Aside links file for the application
     */

?>

<nav class="sidebar-nav">
    <a href="<?= $helpers::route('/user/conversations') ?>" class="<?= $active_page_convo ?>">
        <i class="fas fa-comments"></i>
        <span>Chats</span>
    </a>
    <a href="<?= $helpers::route('/users/users-list') ?>" class="<?= $active_page_users ?>">
        <i class="fa-solid fa-users"></i>
        <span>Users</span>
    </a>
    <a href="<?= $helpers::route('/user/settings') ?>" class="<?= $active_page_settings ?>">
        <i class="fa-solid fa-gear"></i>
        <span>Settings</span>
    </a>
    <?php if ($userRole === 'admin'): ?>
        <a href="<?= $helpers::route('/admin') ?>" class="admin-link <?= $active_page_admin ?>">
            <i class="fa-solid fa-user"></i>
            <span>Admin</span>
        </a>
    <?php endif; ?>
    <button class="logout-button" id="logout-button">
        <i class="fas fa-sign-out"></i>
        <span>Logout</span>
    </button>

    <script src="<?= $helpers::asset('js/logout.js?v='. time()) ?>"></script>
</nav>
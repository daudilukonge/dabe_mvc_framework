<?php

    /**
     * 
     * Header file for container layout
     * 
     */

?>

<header class="app-header">
    <div class="header-left">
        <img src="<?= $helpers::asset('logo.png') ?>" alt="<?= htmlspecialchars($siteName) ?> Logo" loading="lazy">
        <span class="site-name"><?= htmlspecialchars($siteName) ?></span>
    </div>
    <div class="header-right">
        <button id="newChatBtn" class="btn-icon-new">
            <span><i class="fa-solid fa-plus"></i> New Chat</span>
        </button>
        <div class="user-menu" title="Settings">
            <img src="<?= $helpers::asset($userData['profile_image']) ?>" alt="User" class="avatar" <?php if ($userData['active_status'] == 1): ?> style="border: 4px double #00b007;" <?php endif; ?>>
        </div>
    </div>
</header>

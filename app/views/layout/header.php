<?php
    /**
     * 
     * 
     * header file
     * to handle the header of the page
     * 
     * 
    */
?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($siteName) ?> is a secure file sharing platform with end-to-end encryption, real-time chat, and cross-platform support.">
    <meta name="keywords" content="file sharing, secure file sharing, encrypted file sharing, team collaboration, ShareNami">
    <meta name="author" content="<?= htmlspecialchars($ownerName) ?>">
    <title><?= htmlspecialchars($siteName) ?> | <?= htmlspecialchars($pageName) ?></title>
    <link rel="icon" href="<?= $helpers::asset('favicon/favicon.ico') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= $helpers::asset('css/'.$cssFile.'?v='. time()) ?>" rel="preload" as="style"> 
    <link rel="stylesheet" href="<?= $helpers::asset('css/flash.css?v='. time()) ?>" rel="preload" as="style">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

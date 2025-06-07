<?php
    // error code, heading, and message are expected to be passed here and displayed

    $code ??= '500';
    $heading ??= 'Ooops!';
    $message ??= 'Something went wrong';
?>

<!DOCTYPE html>
<html lang="en"> 
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <link rel="stylesheet" type="text/css" href="/assets/css/error.css">
        <title>Error <?= htmlspecialchars($code) ?></title>
    </head>
    <body>
        <h1>Error <?= htmlspecialchars($code) . ': ' . htmlspecialchars($heading) ?></h1>
        <p><?= htmlspecialchars($message) ?></p>

        <a href="/">Go to Homepage</a>
    </body>
</html>
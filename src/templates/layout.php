<?php

declare(strict_types=1);

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?> | SalesAutopilot Exercise</title>
</head>
<body>
    <header>
        <nav aria-label="Primary">
            <a href="/">Home</a>
            <a href="/lists">Lists</a>
            <a href="/subscribers">Subscribers</a>
        </nav>
    </header>
    <main>
        <?php require $template; ?>
    </main>
</body>
</html>

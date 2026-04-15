<?php

declare(strict_types=1);

$lists = is_array($lists ?? null) ? $lists : [];
$errorMessage = is_string($errorMessage ?? null) ? $errorMessage : null;

?>
<h1>Lists</h1>
<p>This page renders list data through the application service and SalesAutopilot client boundary.</p>
<?php if ($errorMessage !== null): ?>
<p><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></p>
<?php elseif ($lists === []): ?>
<p>No lists are available right now.</p>
<?php else: ?>
<ul>
    <?php foreach ($lists as $list): ?>
    <li>
        <strong><?= htmlspecialchars((string) ($list['name'] ?? 'Untitled list'), ENT_QUOTES, 'UTF-8') ?></strong>
        <br>
        ID: <?= htmlspecialchars((string) ($list['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
        <br>
        Subscribers: <?= htmlspecialchars((string) ($list['subscriberCount'] ?? 'n/a'), ENT_QUOTES, 'UTF-8') ?>
        <br>
        Created: <?= htmlspecialchars((string) ($list['createdAt'] ?? 'n/a'), ENT_QUOTES, 'UTF-8') ?>
        <br>
        <a href="/subscribers?listId=<?= urlencode((string) ($list['id'] ?? '')) ?>">View subscribers</a>
    </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

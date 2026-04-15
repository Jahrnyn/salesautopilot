<?php

declare(strict_types=1);

$listId = $_GET['listId'] ?? null;

?>
<h1>Subscribers</h1>
<p>This page is the placeholder shell for the future subscriber view.</p>
<?php if (is_string($listId) && $listId !== ''): ?>
<p>Selected list ID: <?= htmlspecialchars($listId, ENT_QUOTES, 'UTF-8') ?></p>
<?php else: ?>
<p>No list is selected yet. You can still use this page as the navigation target for the subscriber flow.</p>
<?php endif; ?>
<p><a href="/lists">Back to lists</a></p>

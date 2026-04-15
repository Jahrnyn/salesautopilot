<?php

declare(strict_types=1);

$selectedListId = is_string($selectedListId ?? null) ? $selectedListId : null;
$subscribers = is_array($subscribers ?? null) ? $subscribers : [];
$sortBy = is_string($sortBy ?? null) ? $sortBy : 'name';
$errorMessage = is_string($errorMessage ?? null) ? $errorMessage : null;
$emptyMessage = is_string($emptyMessage ?? null) ? $emptyMessage : null;

?>
<h1>Subscribers</h1>
<p>This page renders subscriber data through the application service and SalesAutopilot client boundary.</p>
<?php if ($selectedListId !== null): ?>
<p>Selected list ID: <?= htmlspecialchars($selectedListId, ENT_QUOTES, 'UTF-8') ?></p>
<form method="get" action="/subscribers">
    <input type="hidden" name="listId" value="<?= htmlspecialchars($selectedListId, ENT_QUOTES, 'UTF-8') ?>">
    <label for="sort">Sort by</label>
    <select id="sort" name="sort">
        <option value="name"<?= $sortBy === 'name' ? ' selected' : '' ?>>Name</option>
        <option value="email"<?= $sortBy === 'email' ? ' selected' : '' ?>>Email</option>
    </select>
    <button type="submit">Apply</button>
</form>
<?php endif; ?>
<?php if ($errorMessage !== null): ?>
<p><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></p>
<?php elseif ($emptyMessage !== null): ?>
<p><?= htmlspecialchars($emptyMessage, ENT_QUOTES, 'UTF-8') ?></p>
<?php elseif ($subscribers !== []): ?>
<ul>
    <?php foreach ($subscribers as $subscriber): ?>
    <li>
        <strong><?= htmlspecialchars((string) ($subscriber['name'] ?? 'Unnamed subscriber'), ENT_QUOTES, 'UTF-8') ?></strong>
        <br>
        Email: <?= htmlspecialchars((string) ($subscriber['email'] ?? 'n/a'), ENT_QUOTES, 'UTF-8') ?>
        <br>
        ID: <?= htmlspecialchars((string) ($subscriber['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
    </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<p>No subscriber data is available right now.</p>
<?php endif; ?>
<p><a href="/lists">Back to lists</a></p>

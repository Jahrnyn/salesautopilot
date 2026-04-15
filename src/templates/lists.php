<?php

declare(strict_types=1);

$lists = is_array($lists ?? null) ? $lists : [];
$errorMessage = is_string($errorMessage ?? null) ? $errorMessage : null;

?>
<h1>Lists</h1>
<p class="lede">This page renders list data through the application service and SalesAutopilot client boundary.</p>
<div class="scenario-links">
    <a href="<?= htmlspecialchars(buildScenarioUrl('/lists'), ENT_QUOTES, 'UTF-8') ?>">Happy path</a>
    <a href="<?= htmlspecialchars(buildScenarioUrl('/lists', ['scenario' => 'invalid_credentials']), ENT_QUOTES, 'UTF-8') ?>">Invalid credentials</a>
    <a href="<?= htmlspecialchars(buildScenarioUrl('/lists', ['scenario' => 'timeout']), ENT_QUOTES, 'UTF-8') ?>">Timeout</a>
    <a href="<?= htmlspecialchars(buildScenarioUrl('/lists', ['scenario' => 'rate_limit']), ENT_QUOTES, 'UTF-8') ?>">Rate limit</a>
    <a href="<?= htmlspecialchars(buildScenarioUrl('/subscribers', ['listId' => 'demo-list-3', 'scenario' => 'empty_list']), ENT_QUOTES, 'UTF-8') ?>">Empty list</a>
</div>
<?php if ($errorMessage !== null): ?>
<div class="state-box error"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div>
<?php elseif ($lists === []): ?>
<div class="state-box">No lists are available right now.</div>
<?php else: ?>
<div class="stack">
    <?php foreach ($lists as $list): ?>
    <article class="item-card">
        <strong><?= htmlspecialchars((string) ($list['name'] ?? 'Untitled list'), ENT_QUOTES, 'UTF-8') ?></strong>
        <div class="meta-list">
            <div class="meta-row"><span class="meta-label">ID:</span> <?= htmlspecialchars((string) ($list['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
            <div class="meta-row"><span class="meta-label">Subscribers:</span> <?= htmlspecialchars((string) ($list['subscriberCount'] ?? 'n/a'), ENT_QUOTES, 'UTF-8') ?></div>
            <div class="meta-row"><span class="meta-label">Created:</span> <?= htmlspecialchars((string) ($list['createdAt'] ?? 'n/a'), ENT_QUOTES, 'UTF-8') ?></div>
        </div>
        <a class="action-link" href="<?= htmlspecialchars(buildScenarioUrl('/subscribers', ['listId' => (string) ($list['id'] ?? '')]), ENT_QUOTES, 'UTF-8') ?>">View subscribers</a>
    </article>
    <?php endforeach; ?>
</div>
<?php endif; ?>

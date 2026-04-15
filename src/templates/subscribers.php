<?php

declare(strict_types=1);

$selectedListId = is_string($selectedListId ?? null) ? $selectedListId : null;
$subscribers = is_array($subscribers ?? null) ? $subscribers : [];
$sortBy = is_string($sortBy ?? null) ? $sortBy : 'name';
$errorMessage = is_string($errorMessage ?? null) ? $errorMessage : null;
$emptyMessage = is_string($emptyMessage ?? null) ? $emptyMessage : null;

?>
<h1>Subscribers</h1>
<p class="lede">This page renders subscriber data through the application service and SalesAutopilot client boundary.</p>
<div class="scenario-links">
    <a href="<?= htmlspecialchars(buildScenarioUrl('/subscribers', ['listId' => $selectedListId]), ENT_QUOTES, 'UTF-8') ?>">Happy path</a>
    <a href="<?= htmlspecialchars(buildScenarioUrl('/subscribers', ['listId' => $selectedListId, 'scenario' => 'invalid_credentials']), ENT_QUOTES, 'UTF-8') ?>">Invalid credentials</a>
    <a href="<?= htmlspecialchars(buildScenarioUrl('/subscribers', ['listId' => $selectedListId, 'scenario' => 'timeout']), ENT_QUOTES, 'UTF-8') ?>">Timeout</a>
    <a href="<?= htmlspecialchars(buildScenarioUrl('/subscribers', ['listId' => $selectedListId, 'scenario' => 'rate_limit']), ENT_QUOTES, 'UTF-8') ?>">Rate limit</a>
    <a href="<?= htmlspecialchars(buildScenarioUrl('/subscribers', ['listId' => $selectedListId ?: 'demo-list-3', 'scenario' => 'empty_list']), ENT_QUOTES, 'UTF-8') ?>">Empty list</a>
</div>
<?php if ($selectedListId !== null): ?>
<div class="state-box">
    Selected list ID: <span class="badge"><?= htmlspecialchars($selectedListId, ENT_QUOTES, 'UTF-8') ?></span>
</div>
<form class="controls" method="get" action="/subscribers">
    <input type="hidden" name="listId" value="<?= htmlspecialchars($selectedListId, ENT_QUOTES, 'UTF-8') ?>">
    <?php if (getMockScenario() !== null): ?>
    <input type="hidden" name="scenario" value="<?= htmlspecialchars((string) getMockScenario(), ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
    <div class="field-group">
        <label for="sort">Sort subscribers by</label>
        <select id="sort" name="sort">
            <option value="name"<?= $sortBy === 'name' ? ' selected' : '' ?>>Name</option>
            <option value="email"<?= $sortBy === 'email' ? ' selected' : '' ?>>Email</option>
        </select>
    </div>
    <button type="submit">Apply</button>
</form>
<?php endif; ?>
<?php if ($errorMessage !== null): ?>
<div class="state-box error"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div>
<?php elseif ($emptyMessage !== null): ?>
<div class="state-box"><?= htmlspecialchars($emptyMessage, ENT_QUOTES, 'UTF-8') ?></div>
<?php elseif ($subscribers !== []): ?>
<div class="stack">
    <?php foreach ($subscribers as $subscriber): ?>
    <article class="item-card">
        <strong><?= htmlspecialchars((string) ($subscriber['name'] ?? 'Unnamed subscriber'), ENT_QUOTES, 'UTF-8') ?></strong>
        <div class="meta-list">
            <div class="meta-row"><span class="meta-label">Email:</span> <?= htmlspecialchars((string) ($subscriber['email'] ?? 'n/a'), ENT_QUOTES, 'UTF-8') ?></div>
            <div class="meta-row"><span class="meta-label">ID:</span> <?= htmlspecialchars((string) ($subscriber['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
        </div>
    </article>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="state-box">No subscriber data is available right now.</div>
<?php endif; ?>
<div class="quick-links">
    <a href="<?= htmlspecialchars(buildScenarioUrl('/lists'), ENT_QUOTES, 'UTF-8') ?>">Back to lists</a>
</div>

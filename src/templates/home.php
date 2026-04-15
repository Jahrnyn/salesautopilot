<?php

declare(strict_types=1);

$runtimeState = $runtimeState ?? [
    'appEnv' => 'development',
    'mockModeActive' => false,
    'envCredentialsAvailable' => false,
    'sessionCredentialsAvailable' => false,
    'activeCredentialSource' => 'none',
    'activeClientType' => 'mock',
];

?>
<h1>SalesAutopilot Exercise</h1>
<p class="lede">This is the server-rendered runtime overview for the exercise shell.</p>

<section class="stack">
    <h2>Runtime State</h2>
    <div class="status-grid">
        <div class="status-card">
            <strong>Application environment</strong>
            <span class="badge"><?= htmlspecialchars($runtimeState['appEnv'], ENT_QUOTES, 'UTF-8') ?></span>
        </div>
        <div class="status-card">
            <strong>Mock mode</strong>
            <span class="badge"><?= $runtimeState['mockModeActive'] ? 'Active' : 'Inactive' ?></span>
        </div>
        <div class="status-card">
            <strong>Credential availability</strong>
            <div class="meta-list">
                <div class="meta-row"><span class="meta-label">Env:</span> <?= $runtimeState['envCredentialsAvailable'] ? 'Available' : 'Not available' ?></div>
                <div class="meta-row"><span class="meta-label">Session:</span> <?= $runtimeState['sessionCredentialsAvailable'] ? 'Available' : 'Not available' ?></div>
            </div>
        </div>
        <div class="status-card">
            <strong>Active resolution</strong>
            <div class="meta-list">
                <div class="meta-row"><span class="meta-label">Source:</span> <?= htmlspecialchars($runtimeState['activeCredentialSource'], ENT_QUOTES, 'UTF-8') ?></div>
                <div class="meta-row"><span class="meta-label">Client:</span> <?= htmlspecialchars($runtimeState['activeClientType'], ENT_QUOTES, 'UTF-8') ?></div>
            </div>
        </div>
    </div>
</section>

<div class="state-box">
    This page reports configuration state only and does not validate credentials with an external API call.
</div>

<div class="quick-links">
    <a href="/lists">Open lists</a>
    <a href="/subscribers">Open subscribers</a>
</div>

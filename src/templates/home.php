<?php

declare(strict_types=1);

$runtimeState = $runtimeState ?? [
    'appEnv' => 'development',
    'mockModeActive' => false,
    'envCredentialsAvailable' => false,
    'sessionCredentialsAvailable' => false,
    'activeCredentialSource' => 'none',
];

?>
<h1>SalesAutopilot Exercise</h1>
<p>This is the basic server-rendered home page for the exercise runtime shell.</p>
<p>Runtime state summary:</p>
<ul>
    <li>Application environment: <?= htmlspecialchars($runtimeState['appEnv'], ENT_QUOTES, 'UTF-8') ?></li>
    <li>Mock mode active: <?= $runtimeState['mockModeActive'] ? 'yes' : 'no' ?></li>
    <li>Env credentials available: <?= $runtimeState['envCredentialsAvailable'] ? 'yes' : 'no' ?></li>
    <li>Manual session credentials available: <?= $runtimeState['sessionCredentialsAvailable'] ? 'yes' : 'no' ?></li>
    <li>Active credential source: <?= htmlspecialchars($runtimeState['activeCredentialSource'], ENT_QUOTES, 'UTF-8') ?></li>
</ul>
<p>This page reports configuration state only and does not validate credentials with an external API call.</p>
<p><a href="/lists">Go to lists</a> or <a href="/subscribers">open the subscribers page</a>.</p>

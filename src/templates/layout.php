<?php

declare(strict_types=1);

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?> | SalesAutopilot Exercise</title>
    <style>
        :root {
            color-scheme: light;
            --page-bg: #f5f7fa;
            --surface: #ffffff;
            --surface-muted: #f8fafc;
            --border: #d7dee7;
            --text: #1f2933;
            --text-muted: #52606d;
            --link: #1d4ed8;
            --error-bg: #fef2f2;
            --error-border: #f5c2c7;
            --error-text: #991b1b;
            --empty-bg: #f8fafc;
            --empty-border: #cbd5e1;
            --badge-bg: #e8eefc;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--page-bg);
            color: var(--text);
            font-family: Arial, sans-serif;
            line-height: 1.5;
        }

        a {
            color: var(--link);
            text-decoration: none;
        }

        a:hover,
        a:focus {
            text-decoration: underline;
        }

        .page-shell {
            max-width: 960px;
            margin: 0 auto;
            padding: 24px 16px 48px;
        }

        .site-header {
            margin-bottom: 24px;
            padding: 16px 20px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .site-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .site-nav a {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 8px;
            background: var(--surface-muted);
            font-weight: 600;
        }

        .page-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
        }

        .page-card > * + * {
            margin-top: 16px;
        }

        h1,
        h2 {
            margin: 0;
            line-height: 1.2;
        }

        p {
            margin: 0;
        }

        .lede {
            color: var(--text-muted);
        }

        .scenario-links,
        .quick-links {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .scenario-links a,
        .quick-links a,
        .action-link {
            display: inline-block;
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--surface-muted);
        }

        .stack {
            display: grid;
            gap: 16px;
        }

        .grid-two {
            display: grid;
            gap: 16px;
        }

        @media (min-width: 720px) {
            .grid-two {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .status-grid {
            display: grid;
            gap: 12px;
        }

        @media (min-width: 720px) {
            .status-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .status-card,
        .item-card {
            padding: 16px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--surface-muted);
        }

        .status-card strong,
        .item-card strong {
            display: block;
            margin-bottom: 6px;
        }

        .meta-list {
            display: grid;
            gap: 6px;
            margin: 0;
        }

        .meta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            color: var(--text-muted);
        }

        .meta-label {
            font-weight: 700;
            color: var(--text);
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            background: var(--badge-bg);
            color: var(--text);
            font-size: 0.9rem;
            font-weight: 700;
        }

        .state-box {
            padding: 14px 16px;
            border-radius: 10px;
            border: 1px solid var(--empty-border);
            background: var(--empty-bg);
        }

        .state-box.error {
            border-color: var(--error-border);
            background: var(--error-bg);
            color: var(--error-text);
        }

        .controls {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: end;
            padding: 16px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--surface-muted);
        }

        .field-group {
            display: grid;
            gap: 6px;
        }

        label {
            font-weight: 700;
        }

        select,
        button {
            font: inherit;
        }

        select {
            min-width: 180px;
            padding: 8px 10px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--surface);
        }

        button {
            padding: 9px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--text);
            color: #fff;
            cursor: pointer;
        }

        button:hover,
        button:focus {
            background: #111827;
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <header class="site-header">
            <nav class="site-nav" aria-label="Primary">
                <a href="/">Home</a>
                <a href="/lists">Lists</a>
                <a href="/subscribers">Subscribers</a>
            </nav>
        </header>
        <main class="page-card">
            <?php require $template; ?>
        </main>
    </div>
</body>
</html>

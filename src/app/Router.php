<?php

declare(strict_types=1);

require_once __DIR__ . '/Services/ListPageService.php';

function resolveRoute(string $method, string $path): array
{
    if ($method !== 'GET') {
        return [
            'title' => 'Page Not Found',
            'template' => __DIR__ . '/../templates/not-found.php',
            'handler' => null,
        ];
    }

    return match ($path) {
        '/', '' => [
            'title' => 'Home',
            'template' => __DIR__ . '/../templates/home.php',
            'handler' => null,
        ],
        '/lists' => [
            'title' => 'Lists',
            'template' => __DIR__ . '/../templates/lists.php',
            'handler' => static fn (): array => (new ListPageService())->buildViewModel(),
        ],
        '/subscribers' => [
            'title' => 'Subscribers',
            'template' => __DIR__ . '/../templates/subscribers.php',
            'handler' => null,
        ],
        default => [
            'title' => 'Page Not Found',
            'template' => __DIR__ . '/../templates/not-found.php',
            'handler' => null,
        ],
    };
}

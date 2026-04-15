<?php

declare(strict_types=1);

function resolveRoute(string $method, string $path): array
{
    if ($method !== 'GET') {
        return [
            'title' => 'Page Not Found',
            'template' => __DIR__ . '/../templates/not-found.php',
        ];
    }

    return match ($path) {
        '/', '' => [
            'title' => 'Home',
            'template' => __DIR__ . '/../templates/home.php',
        ],
        '/lists' => [
            'title' => 'Lists',
            'template' => __DIR__ . '/../templates/lists.php',
        ],
        '/subscribers' => [
            'title' => 'Subscribers',
            'template' => __DIR__ . '/../templates/subscribers.php',
        ],
        default => [
            'title' => 'Page Not Found',
            'template' => __DIR__ . '/../templates/not-found.php',
        ],
    };
}

<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Router.php';
require_once __DIR__ . '/../app/Runtime.php';

loadEnvironmentConfig(dirname(__DIR__, 2));
startRuntimeSession();

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

$page = resolveRoute($requestMethod, is_string($requestPath) ? $requestPath : '/');
$runtimeState = getRuntimeState();
$pageData = [];

if (isset($page['handler']) && is_callable($page['handler'])) {
    $pageData = $page['handler']();
}

http_response_code($page['title'] === 'Page Not Found' ? 404 : 200);
header('Content-Type: text/html; charset=UTF-8');

$title = $page['title'];
$template = $page['template'];
$lists = $pageData['lists'] ?? [];
$errorMessage = $pageData['errorMessage'] ?? null;

require __DIR__ . '/../templates/layout.php';

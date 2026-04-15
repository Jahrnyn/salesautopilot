<?php

declare(strict_types=1);

$_ENV['USE_MOCK_SAPI'] = 'true';
$_SERVER['USE_MOCK_SAPI'] = 'true';
putenv('USE_MOCK_SAPI=true');

$_GET = [];
$_SESSION = [];

require_once dirname(__DIR__) . '/src/app/Runtime.php';
require_once dirname(__DIR__) . '/src/app/Services/ListPageService.php';
require_once dirname(__DIR__) . '/src/app/Services/SubscriberPageService.php';

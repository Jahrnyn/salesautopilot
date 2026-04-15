<?php

declare(strict_types=1);

require_once __DIR__ . '/SalesAutopilot/Exceptions.php';
require_once __DIR__ . '/SalesAutopilot/SalesAutopilotClientInterface.php';
require_once __DIR__ . '/SalesAutopilot/MockSalesAutopilotClient.php';
require_once __DIR__ . '/SalesAutopilot/SalesAutopilotHttpClient.php';

function loadEnvironmentConfig(string $projectRoot): void
{
    $envFile = $projectRoot . '/.env';

    if (!is_file($envFile) || !is_readable($envFile)) {
        return;
    }

    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $trimmedLine = trim($line);

        if ($trimmedLine === '' || str_starts_with($trimmedLine, '#')) {
            continue;
        }

        $separatorPosition = strpos($trimmedLine, '=');

        if ($separatorPosition === false) {
            continue;
        }

        $key = trim(substr($trimmedLine, 0, $separatorPosition));
        $value = trim(substr($trimmedLine, $separatorPosition + 1));
        $value = trim($value, " \t\n\r\0\x0B\"'");

        if ($key === '' || array_key_exists($key, $_ENV) || getenv($key) !== false) {
            continue;
        }

        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
        putenv($key . '=' . $value);
    }
}

function startRuntimeSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function getConfigValue(string $key, ?string $default = null): ?string
{
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

    if ($value === false || $value === null || $value === '') {
        return $default;
    }

    return (string) $value;
}

function isMockModeEnabled(): bool
{
    $value = strtolower(getConfigValue('USE_MOCK_SAPI', 'false') ?? 'false');

    return in_array($value, ['1', 'true', 'yes', 'on'], true);
}

function hasEnvCredentials(): bool
{
    return getConfigValue('SAPI_USERNAME') !== null
        && getConfigValue('SAPI_PASSWORD') !== null
        && getConfigValue('SAPI_BASE_URL') !== null;
}

function hasSessionCredentials(): bool
{
    return isset($_SESSION['manual_credentials'])
        && is_array($_SESSION['manual_credentials'])
        && !empty(trim((string) ($_SESSION['manual_credentials']['username'] ?? '')))
        && !empty(trim((string) ($_SESSION['manual_credentials']['password'] ?? '')))
        && !empty(trim((string) ($_SESSION['manual_credentials']['base_url'] ?? '')));
}

function resolveCredentialSource(): string
{
    if (isMockModeEnabled()) {
        return 'mock';
    }

    if (hasSessionCredentials()) {
        return 'session';
    }

    if (hasEnvCredentials()) {
        return 'env';
    }

    return 'none';
}

function getRuntimeState(): array
{
    return [
        'appEnv' => getConfigValue('APP_ENV', 'development') ?? 'development',
        'mockModeActive' => isMockModeEnabled(),
        'activeMockScenario' => getMockScenario(),
        'envCredentialsAvailable' => hasEnvCredentials(),
        'sessionCredentialsAvailable' => hasSessionCredentials(),
        'activeCredentialSource' => resolveCredentialSource(),
        'activeClientType' => resolveActiveClientType(),
    ];
}

function getEnvCredentials(): array
{
    return [
        'username' => getConfigValue('SAPI_USERNAME', '') ?? '',
        'password' => getConfigValue('SAPI_PASSWORD', '') ?? '',
        'base_url' => getConfigValue('SAPI_BASE_URL', '') ?? '',
    ];
}

function getSessionCredentials(): array
{
    if (!isset($_SESSION['manual_credentials']) || !is_array($_SESSION['manual_credentials'])) {
        return [
            'username' => '',
            'password' => '',
            'base_url' => '',
        ];
    }

    return [
        'username' => trim((string) ($_SESSION['manual_credentials']['username'] ?? '')),
        'password' => trim((string) ($_SESSION['manual_credentials']['password'] ?? '')),
        'base_url' => trim((string) ($_SESSION['manual_credentials']['base_url'] ?? '')),
    ];
}

function getResolvedCredentials(): array
{
    if (isMockModeEnabled()) {
        return [];
    }

    if (hasSessionCredentials()) {
        return getSessionCredentials();
    }

    if (hasEnvCredentials()) {
        return getEnvCredentials();
    }

    return [];
}

function resolveActiveClientType(): string
{
    return isMockModeEnabled() ? 'mock' : 'live';
}

function getMockScenario(): ?string
{
    if (!isMockModeEnabled()) {
        return null;
    }

    $scenario = strtolower(trim((string) ($_GET['scenario'] ?? '')));

    return in_array($scenario, ['invalid_credentials', 'timeout', 'rate_limit', 'empty_list'], true)
        ? $scenario
        : null;
}

function buildScenarioUrl(string $path, array $query = []): string
{
    $scenario = getMockScenario();

    if ($scenario !== null && !array_key_exists('scenario', $query)) {
        $query['scenario'] = $scenario;
    }

    $query = array_filter(
        $query,
        static fn (mixed $value): bool => $value !== null && $value !== ''
    );

    if ($query === []) {
        return $path;
    }

    return $path . '?' . http_build_query($query);
}

function resolveSalesAutopilotClient(): SalesAutopilotClientInterface
{
    if (isMockModeEnabled()) {
        return new MockSalesAutopilotClient(getMockScenario());
    }

    $credentials = getResolvedCredentials();
    $baseUrl = trim((string) ($credentials['base_url'] ?? ''));

    return new SalesAutopilotHttpClient($baseUrl);
}

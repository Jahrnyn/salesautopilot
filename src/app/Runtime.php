<?php

declare(strict_types=1);

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
        'envCredentialsAvailable' => hasEnvCredentials(),
        'sessionCredentialsAvailable' => hasSessionCredentials(),
        'activeCredentialSource' => resolveCredentialSource(),
    ];
}

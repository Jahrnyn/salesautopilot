<?php

declare(strict_types=1);

require_once __DIR__ . '/SalesAutopilotClientInterface.php';
require_once __DIR__ . '/Exceptions.php';

class SalesAutopilotHttpClient implements SalesAutopilotClientInterface
{
    public function __construct(
        private readonly string $baseUrl,
    ) {
    }

    public function getLists(array $credentials = []): array
    {
        $this->assertUsableConfiguration($credentials);

        throw new SalesAutopilotNotValidatedException(
            'Live list retrieval is not implemented yet because the SalesAutopilot endpoint details are not validated in this slice.'
        );
    }

    public function getSubscribers(array $credentials = [], string|int|null $listId = null, int $limit = 20): array
    {
        $this->assertUsableConfiguration($credentials);

        throw new SalesAutopilotNotValidatedException(
            'Live subscriber retrieval is not implemented yet because the SalesAutopilot endpoint details are not validated in this slice.'
        );
    }

    private function assertUsableConfiguration(array $credentials): void
    {
        if ($this->baseUrl === '') {
            throw new SalesAutopilotConfigurationException('A SalesAutopilot base URL is required for the live client.');
        }

        $username = trim((string) ($credentials['username'] ?? ''));
        $password = trim((string) ($credentials['password'] ?? ''));

        if ($username === '' || $password === '') {
            throw new SalesAutopilotConfigurationException('Live client credentials are incomplete.');
        }
    }
}

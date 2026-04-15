<?php

declare(strict_types=1);

require_once __DIR__ . '/SalesAutopilotClientInterface.php';
require_once __DIR__ . '/Exceptions.php';

class SalesAutopilotHttpClient implements SalesAutopilotClientInterface
{
    private const REQUEST_TIMEOUT_SECONDS = 5;

    public function __construct(
        private readonly string $baseUrl,
    ) {
    }

    public function getLists(array $credentials = []): array
    {
        $this->assertUsableConfiguration($credentials);

        $payload = $this->performGetRequest('/getlists', $credentials);
        $records = $this->extractRecordList($payload);

        $lists = [];

        foreach ($records as $record) {
            if (!is_array($record)) {
                continue;
            }

            $listId = $this->extractScalarValue($record, ['nl_id', 'id', 'list_id']);
            $name = $this->extractScalarValue($record, ['name', 'list_name', 'listname']);

            if ($listId === null || $name === null) {
                continue;
            }

            $lists[] = [
                'id' => $listId,
                'name' => $name,
                'subscriberCount' => null,
                'createdAt' => null,
            ];
        }

        if ($lists === []) {
            throw new SalesAutopilotResponseException('The live list response did not contain usable list records.');
        }

        return $lists;
    }

    public function getSubscribers(array $credentials = [], string|int|null $listId = null, int $limit = 20): array
    {
        $this->assertUsableConfiguration($credentials);

        $resolvedListId = trim((string) ($listId ?? ''));

        if ($resolvedListId === '') {
            throw new SalesAutopilotConfigurationException('A list identifier is required for live subscriber retrieval.');
        }

        $payload = $this->performGetRequest('/list/' . rawurlencode($resolvedListId), $credentials);
        $records = $this->extractRecordList($payload);
        $subscribers = [];

        foreach ($records as $record) {
            if (!is_array($record)) {
                continue;
            }

            $subscribers[] = [
                'id' => $this->extractScalarValue($record, ['id', 'subscriber_id', 'contact_id']) ?? '',
                'email' => $this->extractScalarValue($record, ['email', 'emailaddress', 'email_address']) ?? '',
                'name' => $this->extractScalarValue($record, ['name', 'fullname', 'full_name']) ?? '',
            ];
        }

        return array_slice($subscribers, 0, max(0, $limit));
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

    private function performGetRequest(string $path, array $credentials): array
    {
        $url = rtrim($this->baseUrl, '/') . $path;
        $username = trim((string) ($credentials['username'] ?? ''));
        $password = trim((string) ($credentials['password'] ?? ''));
        $warningMessage = null;
        $context = stream_context_create(
            [
                'http' => [
                    'method' => 'GET',
                    'timeout' => self::REQUEST_TIMEOUT_SECONDS,
                    'ignore_errors' => true,
                    'header' => implode(
                        "\r\n",
                        [
                            'Accept: application/json; charset=UTF-8',
                            'Authorization: Basic ' . base64_encode($username . ':' . $password),
                        ]
                    ),
                ],
            ]
        );

        set_error_handler(
            static function (int $severity, string $message) use (&$warningMessage): bool {
                $warningMessage = $message;

                return true;
            }
        );

        try {
            $responseBody = file_get_contents($url, false, $context);
        } finally {
            restore_error_handler();
        }

        $responseHeaders = $http_response_header ?? [];

        if ($responseBody === false) {
            if ($warningMessage !== null && str_contains(strtolower($warningMessage), 'timed out')) {
                throw new SalesAutopilotTimeoutException('The live SalesAutopilot request timed out.');
            }

            throw new SalesAutopilotResponseException(
                'The live SalesAutopilot request failed: ' . ($warningMessage !== null ? $warningMessage : 'unknown transport error')
            );
        }

        $statusCode = $this->extractStatusCode($responseHeaders);

        return $this->decodeAndValidateResponse($statusCode, $responseBody);
    }

    private function decodeAndValidateResponse(int $statusCode, string $responseBody): array
    {
        if ($statusCode === 401) {
            throw new SalesAutopilotAuthenticationException('The live SalesAutopilot request was not authorized.');
        }

        if ($statusCode !== 200) {
            throw new SalesAutopilotResponseException(
                'The live SalesAutopilot request returned an unexpected HTTP status: ' . $statusCode
            );
        }

        try {
            $decoded = json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new SalesAutopilotResponseException('The live SalesAutopilot response was not valid JSON.', 0, $exception);
        }

        if (!is_array($decoded)) {
            throw new SalesAutopilotResponseException('The live SalesAutopilot response did not decode to an array.');
        }

        return $decoded;
    }

    private function extractStatusCode(array $responseHeaders): int
    {
        foreach ($responseHeaders as $headerLine) {
            if (preg_match('/^HTTP\/\S+\s+(\d{3})\b/', $headerLine, $matches) === 1) {
                return (int) $matches[1];
            }
        }

        throw new SalesAutopilotResponseException('The live SalesAutopilot response did not include an HTTP status line.');
    }

    private function extractRecordList(array $payload): array
    {
        if (array_is_list($payload)) {
            return $payload;
        }

        foreach ($payload as $value) {
            if (is_array($value) && array_is_list($value)) {
                return $value;
            }
        }

        throw new SalesAutopilotResponseException('The live SalesAutopilot response did not contain a usable record list.');
    }

    private function extractScalarValue(array $record, array $candidateKeys): ?string
    {
        foreach ($candidateKeys as $candidateKey) {
            if (!array_key_exists($candidateKey, $record)) {
                continue;
            }

            $value = $record[$candidateKey];

            if (is_scalar($value) && trim((string) $value) !== '') {
                return trim((string) $value);
            }
        }

        return null;
    }
}

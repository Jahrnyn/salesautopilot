<?php

declare(strict_types=1);

require_once __DIR__ . '/SalesAutopilotClientInterface.php';
require_once __DIR__ . '/Exceptions.php';

class MockSalesAutopilotClient implements SalesAutopilotClientInterface
{
    public function __construct(
        private readonly ?string $scenario = null,
    ) {
    }

    public function getLists(array $credentials = []): array
    {
        $this->throwIfFailureScenarioIsActive();

        return [
            [
                'id' => 'demo-list-1',
                'name' => 'Newsletter Subscribers',
                'subscriberCount' => 128,
                'createdAt' => '2024-01-15',
            ],
            [
                'id' => 'demo-list-2',
                'name' => 'Webinar Registrants',
                'subscriberCount' => 42,
                'createdAt' => '2024-03-20',
            ],
            [
                'id' => 'demo-list-3',
                'name' => 'Dormant Leads',
                'subscriberCount' => 0,
                'createdAt' => '2024-04-05',
            ],
        ];
    }

    public function getSubscribers(array $credentials = [], string|int|null $listId = null, int $limit = 20): array
    {
        $this->throwIfFailureScenarioIsActive();

        $subscribersByList = [
            'demo-list-1' => [
                [
                    'id' => 'sub-1001',
                    'email' => 'anna@example.test',
                    'name' => 'Anna Kovacs',
                ],
                [
                    'id' => 'sub-1002',
                    'email' => 'mark@example.test',
                    'name' => 'Mark Toth',
                ],
                [
                    'id' => 'sub-1003',
                    'email' => 'eva@example.test',
                    'name' => 'Eva Szabo',
                ],
            ],
            'demo-list-2' => [
                [
                    'id' => 'sub-2001',
                    'email' => 'julia@example.test',
                    'name' => 'Julia Farkas',
                ],
                [
                    'id' => 'sub-2002',
                    'email' => 'peter@example.test',
                    'name' => 'Peter Nagy',
                ],
                [
                    'id' => 'sub-2003',
                    'email' => 'adam@example.test',
                    'name' => 'Adam Varga',
                ],
            ],
            'demo-list-3' => [],
        ];

        $resolvedListId = $listId === null || $listId === '' ? 'demo-list-1' : (string) $listId;

        if ($this->scenario === 'empty_list') {
            return [];
        }

        $subscribers = $subscribersByList[$resolvedListId] ?? [];

        return array_slice($subscribers, 0, max(0, $limit));
    }

    private function throwIfFailureScenarioIsActive(): void
    {
        match ($this->scenario) {
            'invalid_credentials' => throw new SalesAutopilotAuthenticationException('Invalid credentials.'),
            'timeout' => throw new SalesAutopilotTimeoutException('The request timed out.'),
            'rate_limit' => throw new SalesAutopilotRateLimitException('The request was rate limited.'),
            default => null,
        };
    }
}

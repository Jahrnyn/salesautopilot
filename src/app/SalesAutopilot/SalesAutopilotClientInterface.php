<?php

declare(strict_types=1);

interface SalesAutopilotClientInterface
{
    public function getLists(array $credentials = []): array;

    public function getSubscribers(array $credentials = [], string|int|null $listId = null, int $limit = 20): array;
}

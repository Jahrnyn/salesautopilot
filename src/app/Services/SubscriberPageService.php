<?php

declare(strict_types=1);

require_once __DIR__ . '/../Runtime.php';

class SubscriberPageService
{
    public function buildViewModel(?string $listId, string $sortBy = 'name'): array
    {
        if ($listId === null || trim($listId) === '') {
            return [
                'selectedListId' => null,
                'subscribers' => [],
                'sortBy' => $this->normalizeSortBy($sortBy),
                'errorMessage' => null,
                'emptyMessage' => 'Select a list from the lists page to view its subscribers.',
            ];
        }

        try {
            $client = resolveSalesAutopilotClient();
            $credentials = getResolvedCredentials();
            $subscribers = $client->getSubscribers($credentials, $listId, 20);
            $sortBy = $this->normalizeSortBy($sortBy);
            $subscribers = $this->sortSubscribers($subscribers, $sortBy);

            return [
                'selectedListId' => $listId,
                'subscribers' => $subscribers,
                'sortBy' => $sortBy,
                'errorMessage' => null,
                'emptyMessage' => $subscribers === []
                    ? 'The selected list currently has no subscribers.'
                    : null,
            ];
        } catch (SalesAutopilotException $exception) {
            return [
                'selectedListId' => $listId,
                'subscribers' => [],
                'sortBy' => $this->normalizeSortBy($sortBy),
                'errorMessage' => 'The subscriber data is currently unavailable. Please check the runtime configuration and try again.',
                'emptyMessage' => null,
            ];
        }
    }

    private function normalizeSortBy(string $sortBy): string
    {
        return in_array($sortBy, ['email', 'name'], true) ? $sortBy : 'name';
    }

    private function sortSubscribers(array $subscribers, string $sortBy): array
    {
        usort(
            $subscribers,
            static function (array $left, array $right) use ($sortBy): int {
                $leftValue = strtolower((string) ($left[$sortBy] ?? ''));
                $rightValue = strtolower((string) ($right[$sortBy] ?? ''));

                return $leftValue <=> $rightValue;
            }
        );

        return $subscribers;
    }
}

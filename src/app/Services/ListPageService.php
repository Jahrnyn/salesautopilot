<?php

declare(strict_types=1);

require_once __DIR__ . '/../Runtime.php';

class ListPageService
{
    public function buildViewModel(): array
    {
        try {
            $client = resolveSalesAutopilotClient();
            $credentials = getResolvedCredentials();
            $lists = $client->getLists($credentials);

            return [
                'lists' => $lists,
                'errorMessage' => null,
            ];
        } catch (SalesAutopilotException $exception) {
            return [
                'lists' => [],
                'errorMessage' => 'The list data is currently unavailable. Please check the runtime configuration and try again.',
            ];
        }
    }
}

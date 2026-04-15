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
                'errorMessage' => $this->mapErrorMessage($exception),
            ];
        }
    }

    private function mapErrorMessage(SalesAutopilotException $exception): string
    {
        return match (true) {
            $exception instanceof SalesAutopilotAuthenticationException => 'The provided credentials are not valid.',
            $exception instanceof SalesAutopilotTimeoutException => 'The SalesAutopilot request took too long. Please try again.',
            $exception instanceof SalesAutopilotRateLimitException => 'Too many requests were sent. Please wait a moment and try again.',
            default => 'The list data is currently unavailable. Please check the runtime configuration and try again.',
        };
    }
}

<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class SubscriberPageServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        $_GET = [];
    }

    public function testSubscribersHappyPathReturnsSortedSubscribers(): void
    {
        $_GET = [];

        $result = (new SubscriberPageService())->buildViewModel('demo-list-2', 'email');

        self::assertNull($result['errorMessage']);
        self::assertNull($result['emptyMessage']);
        self::assertCount(3, $result['subscribers']);
        self::assertSame('adam@example.test', $result['subscribers'][0]['email']);
        self::assertSame('julia@example.test', $result['subscribers'][1]['email']);
        self::assertSame('peter@example.test', $result['subscribers'][2]['email']);
    }

    public function testEmptyListScenarioReturnsEmptyState(): void
    {
        $_GET = ['scenario' => 'empty_list'];

        $result = (new SubscriberPageService())->buildViewModel('demo-list-3');

        self::assertSame([], $result['subscribers']);
        self::assertNull($result['errorMessage']);
        self::assertSame('The selected list currently has no subscribers.', $result['emptyMessage']);
    }

    public function testInvalidCredentialsScenarioReturnsUserFacingMessage(): void
    {
        $_GET = ['scenario' => 'invalid_credentials'];

        $result = (new SubscriberPageService())->buildViewModel('demo-list-1');

        self::assertSame([], $result['subscribers']);
        self::assertSame('The provided credentials are not valid.', $result['errorMessage']);
    }

    public function testTimeoutScenarioReturnsUserFacingMessage(): void
    {
        $_GET = ['scenario' => 'timeout'];

        $result = (new SubscriberPageService())->buildViewModel('demo-list-1');

        self::assertSame([], $result['subscribers']);
        self::assertSame('The SalesAutopilot request took too long. Please try again.', $result['errorMessage']);
    }

    public function testRateLimitScenarioReturnsUserFacingMessage(): void
    {
        $_GET = ['scenario' => 'rate_limit'];

        $result = (new SubscriberPageService())->buildViewModel('demo-list-1');

        self::assertSame([], $result['subscribers']);
        self::assertSame('Too many requests were sent. Please wait a moment and try again.', $result['errorMessage']);
    }
}

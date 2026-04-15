<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ListPageServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        $_GET = [];
    }

    public function testListsHappyPathReturnsMockLists(): void
    {
        $_GET = [];

        $result = (new ListPageService())->buildViewModel();

        self::assertNull($result['errorMessage']);
        self::assertCount(3, $result['lists']);
        self::assertSame('demo-list-1', $result['lists'][0]['id']);
        self::assertSame('Newsletter Subscribers', $result['lists'][0]['name']);
    }

    public function testInvalidCredentialsScenarioReturnsUserFacingMessage(): void
    {
        $_GET = ['scenario' => 'invalid_credentials'];

        $result = (new ListPageService())->buildViewModel();

        self::assertSame([], $result['lists']);
        self::assertSame('The provided credentials are not valid.', $result['errorMessage']);
    }

    public function testTimeoutScenarioReturnsUserFacingMessage(): void
    {
        $_GET = ['scenario' => 'timeout'];

        $result = (new ListPageService())->buildViewModel();

        self::assertSame([], $result['lists']);
        self::assertSame('The SalesAutopilot request took too long. Please try again.', $result['errorMessage']);
    }

    public function testRateLimitScenarioReturnsUserFacingMessage(): void
    {
        $_GET = ['scenario' => 'rate_limit'];

        $result = (new ListPageService())->buildViewModel();

        self::assertSame([], $result['lists']);
        self::assertSame('Too many requests were sent. Please wait a moment and try again.', $result['errorMessage']);
    }
}

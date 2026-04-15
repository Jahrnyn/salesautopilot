<?php

declare(strict_types=1);

class SalesAutopilotException extends RuntimeException
{
}

class SalesAutopilotConfigurationException extends SalesAutopilotException
{
}

class SalesAutopilotAuthenticationException extends SalesAutopilotException
{
}

class SalesAutopilotTimeoutException extends SalesAutopilotException
{
}

class SalesAutopilotRateLimitException extends SalesAutopilotException
{
}

class SalesAutopilotResponseException extends SalesAutopilotException
{
}

class SalesAutopilotNotValidatedException extends SalesAutopilotException
{
}

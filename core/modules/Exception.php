<?php
defined('RUN') or http_response_code(404) and die();

class PageNotFoundException extends Exception
{
    public function __construct($message = '404 Not Found', $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

class PageFileDoesNotExistException extends Exception
{
    public function __construct($message = 'Page file does not exist', $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

class ObjectNotFoundException extends Exception
{
    public function __construct($message = 'Object not found', $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

class InvalidPageReturnType extends Exception
{
    public function __construct($message = 'Invalid page return type', $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

class ConfigurationException extends Exception
{
    public function __construct($message = 'Configuration invalid', $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

class MethodNotAllowedException extends Exception
{
    public function __construct($message = 'Method not allowed', $code = 405, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

class AuthenticationException extends Exception
{
    public function __construct($message = 'Unauthenticated', $code = 401, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

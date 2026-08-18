<?php
defined('RUN') or http_response_code(404) and die();

class PageNotFoundException extends Exception
{
    public function __construct($message = '404 Not Found', $code = 404, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

class PageFileDoesNotExistException extends Exception
{
    public function __construct($message = 'Page file does not exist', $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

class ObjectNotFoundException extends Exception
{
    public function __construct($message = 'Object not found', $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

class FunctionNotFoundException extends Exception
{
    public function __construct($message = 'Function not found', $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

class InvalidPageReturnType extends Exception
{
    public function __construct($message = 'Invalid page return type', $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

class ConfigurationException extends Exception
{
    public function __construct($message = 'Configuration invalid', $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

class MethodNotAllowedException extends Exception
{
    public function __construct($message = 'Method not allowed', $code = 405, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

class AuthenticationException extends Exception
{
    public function __construct($message = 'Unauthenticated', $code = 401, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

class ExceptionHandler
{
    public function handle(Request $request, Throwable $th)
    {
        $code = $th->getCode() ?? 500;
        http_response_code($code);

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        if ($code >= 500) {
            echo sprintf('Code: %s\n', $th->getCode());
            echo sprintf('Message: %s\n', $th->getMessage());
            echo sprintf('File: %s\n', $th->getFile());
            echo sprintf('Line: %s\n', $th->getLine());
            echo sprintf('Trace: %s\n', print_r($th->getTrace(), true));
            exit;
        }

        if ($code >= 300) {
            echo sprintf('Code: %s\n', $th->getCode());
            echo sprintf('Message: %s\n', $th->getMessage());
            exit;
        }
    }
}
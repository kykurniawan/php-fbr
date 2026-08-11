<?php
defined('RUN') or http_response_code(404) and die();

require_once __DIR__ . '/exception.php';
require_once __DIR__ . '/request.php';
require_once __DIR__ . '/response.php';
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/middleware.php';
require_once __DIR__ . '/common.php';

class FBR
{
    private static self $instance;

    private ?string $pageFilesDir = null;
    private ?string $layoutFilesDir = null;
    private array $routes = [];
    private string $currentPath;
    private array $objects = [];
    private ?string $baseUrl = null;
    private ?ExceptionHandler $exceptionHandler = null;
    private ?Request $request = null;
    private array $eventHandlers = [];
    private SessionInterface $sessionHandler;
    private array $middlewares = [];
    private ?string $useLayout = null;

    public function __construct()
    {
        $this->sessionHandler = new Session();
        $this->sessionHandler->start();

        static::$instance = $this;
    }

    public function middlewares(MiddlewareInterface ...$middlewares)
    {
        $this->middlewares = $middlewares;
    }

    public function session(): SessionInterface
    {
        return $this->sessionHandler;
    }

    public function setSessionHandler(SessionInterface $sessionHandler)
    {
        $this->sessionHandler = $sessionHandler;
    }

    public function setPageFilesDir(string $pageFilesDir)
    {
        $this->pageFilesDir = $pageFilesDir;
    }

    public function setLayoutFilesDir(string $layoutFilesDir)
    {
        $this->layoutFilesDir = $layoutFilesDir;
    }

    public function request(): ?Request
    {
        return $this->request;
    }

    public function setBaseUrl(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function setExceptionHandler(ExceptionHandler $exceptionHandler)
    {
        $this->exceptionHandler = $exceptionHandler;
    }

    private function init()
    {
        if ($this->pageFilesDir === null) {
            throw new ConfigurationException(
                sprintf('Page files dir not set')
            );
        }
        if ($this->baseUrl === null) {
            throw new ConfigurationException(
                sprintf('Base URL not set')
            );
        }
        if ($this->layoutFilesDir === null) {
            throw new ConfigurationException(
                sprintf('Layout files dir not set')
            );
        }

        $this->routes = $this->collectRoutes($this->pageFilesDir);
        $this->currentPath = $this->resolveCurrentPath();
    }

    public function bindObject(string $name, object $object)
    {
        $this->objects[$name] = $object;
    }

    public function on(string $event, Closure $handler)
    {
        $this->eventHandlers[$event] = $handler;
    }

    public function getCurrentPath()
    {
        return static::$instance->currentPath;
    }

    public function getObject(string $name): object
    {
        if (!isset($this->objects[$name])) {
            throw new ObjectNotFoundException(
                sprintf('Object not found: %s', $name)
            );
        }
        return $this->objects[$name];
    }

    public function run()
    {
        $this->request = new Request(
            $_GET,
            $_POST,
            $_FILES,
            $_SERVER,
            $_COOKIE,
            [],
            getallheaders(),
        );

        try {
            $this->init();

            foreach ($this->routes as $route) {
                $filePath = $this->sanitizeFilePath($this->pageFilesDir . $route . '.php');
                if (!file_exists($filePath)) {
                    $filePath = $this->sanitizeFilePath($this->pageFilesDir . $route . '/index.php');
                    if (!file_exists($filePath)) {
                        throw new PageFileDoesNotExistException(
                            sprintf('Page file does not exist: %s', $filePath)
                        );
                    }
                }

                $this->handleRoute($route, $filePath);
            }
            throw new PageNotFoundException();
        } catch (Throwable $th) {
            $this->handleException($th);
        }
    }

    private function sanitizeFilePath(string $filePath): string
    {
        $filePath = str_replace('../', '', $filePath);
        $filePath = preg_replace('#/{2,}#', '/', $filePath);

        return $filePath;
    }

    private function resolveCurrentPath(): string
    {
        if (isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] !== '') {
            return $_SERVER['PATH_INFO'];
        }

        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        return $path !== '' ? $path : '/';
    }

    private function collectRoutes(string $dir, string $prefix = '/')
    {
        $routes = [];
        $stack = [[$dir, $prefix]];

        while (!empty($stack)) {
            [$currentDir, $currentPrefix] = array_pop($stack);

            foreach (scandir($currentDir) as $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                $currentPath = $currentDir . '/' . $file;

                if (is_file($currentPath)) {
                    $lastSegment = ($file === 'index.php') ? $currentPrefix : $currentPrefix . substr($file, 0, -4) . '/';
                    $routes[] = $lastSegment !== '/' ? substr($lastSegment, 0, -1) : $lastSegment;
                }

                if (is_dir($currentPath)) {
                    $stack[] = [$currentPath, $currentPrefix . $file . '/'];
                }
            }
        }

        usort($routes, function ($a, $b) {
            $paramCountA = substr_count($a, '[');
            $paramCountB = substr_count($b, '[');

            return $paramCountA - $paramCountB;
        });

        return $routes;
    }

    private function handleRoute(string $route, string $filePath)
    {
        $routeParameters = [];

        $routeSegments = explode('/', $route);
        $currentPathSegments = explode('/', $this->currentPath);

        if (count($routeSegments) !== count($currentPathSegments)) {
            return;
        }

        foreach ($routeSegments as $index => $segment) {
            if (preg_match('/^\[\w+\]$/', $segment)) {
                $routeParameters[substr($segment, 1, -1)] = $currentPathSegments[$index];
            } else {
                if ($segment !== $currentPathSegments[$index]) {
                    return;
                }
            }
        }

        $this->request = new Request(
            $_GET,
            $_POST,
            $_FILES,
            $_SERVER,
            $_COOKIE,
            $routeParameters,
            getallheaders(),
        );

        foreach ($this->middlewares as $middleware) {
            if (in_array($route, $middleware->getExceptRoutes(), true)) {
                continue;
            }

            $routes = $middleware->getRoutes();

            foreach ($routes as $middlewareRoute) {
                if ($middlewareRoute === '*' || $middlewareRoute === $route) {
                    $this->request = $middleware->handle($this->request, function ($request) {
                        return $request;
                    }, ...$middleware->getParams());
                }
            }
        }

        include_once $filePath;

        exit;
    }

    public function handleException(Throwable $th)
    {
        if ($this->exceptionHandler === null) {
            throw $th;
        }

        $this->exceptionHandler->handle($this->request, $th);
        exit;
    }

    public function url($route = '/'): string
    {
        $url = trim($this->baseUrl, '/');
        $route = trim($route, '/');

        if (substr($route, 0, 1) !== '/') {
            $route = '/' . $route;
        }

        $url .= $route;

        $parsedUrl = parse_url($url);

        $url = $parsedUrl['scheme'];
        $url .= '://';
        $url .= $parsedUrl['host'];
        if (isset($parsedUrl['port'])) {
            $url .= ':' . $parsedUrl['port'];
        }
        $url .= $parsedUrl['path'];
        if (isset($parsedUrl['query'])) {
            $url .= '?' . $parsedUrl['query'];
        }

        return $url;
    }

    public function object(string $name)
    {
        return $this->getObject($name);
    }

    public function dispatch(string $event, ...$params)
    {
        if (isset($this->eventHandlers[$event])) {
            ($this->eventHandlers[$event])(...$params);
        }
    }

    public function redirect(string $url, int $statusCode = 302)
    {
        header('HTTP/1.1 ' . $statusCode);
        header('Location: ' . $url);
        exit;
    }

    function allowedMethods(...$methods)
    {
        $method = $this->request->method();

        $methods = array_map('strtolower', $methods);

        if (!in_array($method, $methods)) {
            throw new MethodNotAllowedException(
                sprintf('Method %s not allowed', $method)
            );
        }
    }

    public function pageStart(string $layout)
    {
        $this->useLayout = $layout;
        ob_start();
    }

    public function pageEnd()
    {
        define('PAGE_CONTENT', ob_get_clean());

        include_once $this->sanitizeFilePath($this->layoutFilesDir . '/' . $this->useLayout . '.php');
    }

    public static function instance()
    {
        return static::$instance;
    }
}
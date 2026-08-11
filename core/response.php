<?php
defined('RUN') or http_response_code(404) and die();

class Response
{
    private string $contentType = 'text/html';
    private int $statusCode = 200;
    private array $headers = [];

    public function __construct(string $contentType = 'text/html', int $statusCode = 200, array $headers = [])
    {
        $this->contentType = $contentType;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    public function contentType(string $contentType): Response
    {
        $this->contentType = $contentType;

        return $this;
    }

    public function statusCode(int $statusCode): Response
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function header(string $key, string $value): Response
    {
        $this->headers[$key] = $value;

        return $this;
    }

    public function send(string $content): void
    {
        header('Content-Type: ' . $this->contentType);
        http_response_code($this->statusCode);

        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }

        echo $content;
    }

    public function html(string $content): void
    {
        header('Content-Type: text/html');
        http_response_code($this->statusCode);

        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }

        echo $content;
    }

    public function json(mixed $data, int $options = 0, int $depth = 512): void
    {
        header('Content-Type: application/json');
        http_response_code($this->statusCode);

        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }

        echo json_encode($data, $options, $depth);
    }
}
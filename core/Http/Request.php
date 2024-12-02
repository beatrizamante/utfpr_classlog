<?php

namespace Core\Http;

use function json_decode;

class Request
{
    private string $method;
    private string $uri;

    /** @var mixed[] */
    private array $params;

  /** @var mixed[] */

    private array $body = [];

    /** @var array<string, string> */
    private array $headers;

    public function __construct()
    {
        $this->method = $_REQUEST['_method'] ?? $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->params = $_REQUEST;
        $this->headers = function_exists('getallheaders') ? getallheaders() : [];
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    /** @return mixed[] */
    public function getParams(): array
    {
        return $this->params;
    }



    /** @return array<string, string>*/
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /** @param mixed[] $params*/
    public function addParams(array $params): void
    {
        $this->params = array_merge($this->params, $params);
    }

    public function acceptJson(): bool
    {
        return (isset($_SERVER['HTTP_ACCEPT']) && $_SERVER['HTTP_ACCEPT'] === 'application/json');
    }

    public function getParam(string $key, mixed $default = null): mixed
    {
        return $this->params[$key] ?? $default;
    }

  /**
   *
   * @return mixed
   */
    public function getBody(): mixed
    {
        $contentType = $this->getHeader('Content-Type');

        if (strpos($contentType, 'application/json') !== false) {
            $body = file_get_contents('php://input');
            if ($body) {
                $decodedBody = json_decode($body, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $this->body = $decodedBody;
                    return $this->body;
                }
                return ['error' => 'Invalid JSON'];
            }
            return null;
        }

        if (
            strpos($contentType, 'application/x-www-form-urlencoded') !== false ||
            strpos($contentType, 'multipart/form-data') !== false
        ) {
            return $this->params ?: [];
        }

        return null;
    }

  /**
   * Get a specific header value
   *
   * @param string $key
   * @return string|null
   */
    public function getHeader(string $key): ?string
    {
        return $this->headers[$key] ?? null;
    }
}

<?php

declare(strict_types=1);

namespace Tigusigalpa\Shippo;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Tigusigalpa\Shippo\Exceptions\AuthenticationException;
use Tigusigalpa\Shippo\Exceptions\NotFoundException;
use Tigusigalpa\Shippo\Exceptions\RateLimitException;
use Tigusigalpa\Shippo\Exceptions\ServerException;
use Tigusigalpa\Shippo\Exceptions\ShippoException;
use Tigusigalpa\Shippo\Exceptions\ValidationException;

final class Client
{
    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory,
        private readonly Config $config
    ) {
    }

    public function get(string $uri, array $query = []): array
    {
        $url = $this->buildUrl($uri, $query);
        $request = $this->createRequest('GET', $url);

        return $this->sendRequest($request);
    }

    public function post(string $uri, array $data = []): array
    {
        $request = $this->createRequest('POST', $this->buildUrl($uri))
            ->withBody($this->streamFactory->createStream(json_encode($data)));

        return $this->sendRequest($request);
    }

    public function put(string $uri, array $data = []): array
    {
        $request = $this->createRequest('PUT', $this->buildUrl($uri))
            ->withBody($this->streamFactory->createStream(json_encode($data)));

        return $this->sendRequest($request);
    }

    public function delete(string $uri): array
    {
        $request = $this->createRequest('DELETE', $this->buildUrl($uri));

        return $this->sendRequest($request);
    }

    private function createRequest(string $method, string $uri): RequestInterface
    {
        $request = $this->requestFactory->createRequest($method, $uri);

        foreach ($this->config->getHeaders() as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        return $request;
    }

    private function buildUrl(string $uri, array $query = []): string
    {
        $url = rtrim($this->config->baseUrl, '/') . '/' . ltrim($uri, '/');

        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        return $url;
    }

    private function sendRequest(RequestInterface $request, int $attempt = 1): array
    {
        try {
            $response = $this->httpClient->sendRequest($request);

            return $this->handleResponse($response);
        } catch (RateLimitException $e) {
            if ($attempt < $this->config->retryAttempts) {
                $delay = $e->getRetryAfter() ?? ($this->config->retryDelay * $attempt);
                usleep($delay * 1000);

                return $this->sendRequest($request, $attempt + 1);
            }

            throw $e;
        }
    }

    private function handleResponse(ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();
        $body = (string) $response->getBody();
        $data = json_decode($body, true) ?? [];

        if ($statusCode >= 200 && $statusCode < 300) {
            return $data;
        }

        $message = $data['detail'] ?? $data['message'] ?? 'An error occurred';

        match (true) {
            $statusCode === 401 => throw new AuthenticationException($message, $statusCode, null, $data),
            $statusCode === 404 => throw new NotFoundException($message, $statusCode, null, $data),
            $statusCode === 422 || $statusCode === 400 => throw new ValidationException($message, $statusCode, null, $data),
            $statusCode === 429 => throw new RateLimitException(
                $message,
                $statusCode,
                null,
                $data,
                isset($response->getHeader('Retry-After')[0]) ? (int) $response->getHeader('Retry-After')[0] : null
            ),
            $statusCode >= 500 => throw new ServerException($message, $statusCode, null, $data),
            default => throw new ShippoException($message, $statusCode, null, $data),
        };
    }
}

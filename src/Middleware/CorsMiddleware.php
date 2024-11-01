<?php
declare(strict_types=1);

namespace Salienture\Cors\Middleware;

use Cake\Core\Configure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Cors middleware
 */
class CorsMiddleware implements MiddlewareInterface
{
    /**
     * Main process method for handling CORS.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        // Check if CORS needs to be applied (e.g., check route or domain)
        if (!$this->isCorsApplicable($request)) {
            return $response;
        }

        // Apply CORS headers based on config
        $response = $this->applyCorsHeaders($request, $response);

        // Handle preflight requests
        if ($request->getMethod() === 'OPTIONS') {
            return $this->handlePreflightRequest($response);
        }

        return $response;
    }

    /**
     * Check if CORS should be applied to the current request.
     * 
     * @param ServerRequestInterface $request
     * @return bool
     */
    protected function isCorsApplicable(ServerRequestInterface $request): bool
    {
        // CORS is applied to all requests.
        return true;
    }

    /**
     * Apply CORS headers to the response.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    protected function applyCorsHeaders(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $corsConfig = Configure::read('Cors');

        $response = $this->setAllowOriginHeader($request, $response, $corsConfig);
        $response = $this->setAllowMethodsHeader($response, $corsConfig);
        $response = $this->setAllowHeadersHeader($response, $corsConfig);
        $response = $this->setExposeHeadersHeader($response, $corsConfig);
        $response = $this->setCredentialsHeader($response, $corsConfig);
        $response = $this->setMaxAgeHeader($response, $corsConfig);

        return $response;
    }

    /**
     * Handle preflight (OPTIONS) requests.
     *
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    protected function handlePreflightRequest(ResponseInterface $response): ResponseInterface
    {
        // Return a successful preflight response with status 200
        return $response->withStatus(200);
    }

    /**
     * Set the Access-Control-Allow-Origin header.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $corsConfig
     * @return ResponseInterface
     */
    protected function setAllowOriginHeader(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $corsConfig): ResponseInterface
    {
        $allowedOrigins = $corsConfig['allowOrigin'] ?? ['*'];

        // If allowOrigin is a boolean
        if (is_bool($allowedOrigins)) {
            if ($allowedOrigins) {
                return $response->withHeader('Access-Control-Allow-Origin', '*'); // Allow all origins
            } else {
                return $response->withHeader('Access-Control-Allow-Origin', ''); // Disallow all origins
            }
        }

        // If allowOrigin is an array, apply the appropriate logic
        $origin = $request->getHeaderLine('Origin');
        if (in_array('*', $allowedOrigins) || in_array($origin, $allowedOrigins)) {
            return $response->withHeader('Access-Control-Allow-Origin', $origin);
        }

        return $response->withHeader('Access-Control-Allow-Origin', '');
    }

    /**
     * Set the Access-Control-Allow-Methods header.
     *
     * @param ResponseInterface $response
     * @param array $corsConfig
     * @return ResponseInterface
     */
    protected function setAllowMethodsHeader(ResponseInterface $response, array $corsConfig): ResponseInterface
    {
        $allowedMethods = $corsConfig['allowMethods'] ?? ['GET', 'POST', 'OPTIONS'];

        return $response->withHeader('Access-Control-Allow-Methods', implode(',', $allowedMethods));
    }

    /**
     * Set the Access-Control-Allow-Headers header.
     * Handle cases where allowHeaders is either an array or boolean.
     *
     * @param ResponseInterface $response
     * @param array $corsConfig
     * @return ResponseInterface
     */
    protected function setAllowHeadersHeader(ResponseInterface $response, array $corsConfig): ResponseInterface
    {
        $allowedHeaders = $corsConfig['allowHeaders'] ?? true;

        // If allowHeaders is true, allow all headers
        if (is_bool($allowedHeaders)) {
            if ($allowedHeaders) {
                return $response->withHeader('Access-Control-Allow-Headers', '*');
            } else {
                return $response->withHeader('Access-Control-Allow-Headers', '');
            }
        }

        // If allowHeaders is an array, allow specific headers
        return $response->withHeader('Access-Control-Allow-Headers', implode(',', $allowedHeaders));
    }

    /**
     * Set the Access-Control-Expose-Headers header.
     * Handle cases where exposeHeaders is either an array or boolean.
     *
     * @param ResponseInterface $response
     * @param array $corsConfig
     * @return ResponseInterface
     */
    protected function setExposeHeadersHeader(ResponseInterface $response, array $corsConfig): ResponseInterface
    {
        $exposeHeaders = $corsConfig['exposeHeaders'] ?? false;

        // If exposeHeaders is true, expose all headers
        if (is_bool($exposeHeaders)) {
            if ($exposeHeaders) {
                return $response->withHeader('Access-Control-Expose-Headers', '*');
            } else {
                return $response->withHeader('Access-Control-Expose-Headers', '');
            }
        }

        // If exposeHeaders is an array, expose specific headers
        return $response->withHeader('Access-Control-Expose-Headers', implode(',', $exposeHeaders));
    }

    /**
     * Set the Access-Control-Allow-Credentials header.
     *
     * @param ResponseInterface $response
     * @param array $corsConfig
     * @return ResponseInterface
     */
    protected function setCredentialsHeader(ResponseInterface $response, array $corsConfig): ResponseInterface
    {
        $credentials = $corsConfig['credentials'] ?? false;

        return $response->withHeader('Access-Control-Allow-Credentials', $credentials ? 'true' : 'false');
    }

    /**
     * Set the Access-Control-Max-Age header.
     *
     * @param ResponseInterface $response
     * @param array $corsConfig
     * @return ResponseInterface
     */
    protected function setMaxAgeHeader(ResponseInterface $response, array $corsConfig): ResponseInterface
    {
        $maxAge = $corsConfig['maxAge'] ?? 3600;

        return $response->withHeader('Access-Control-Max-Age', (string)$maxAge);
    }
}

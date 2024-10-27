<?php
declare(strict_types=1);

/**
 * CORS configuration settings.
 * 
 * This configuration controls the behavior of Cross-Origin Resource Sharing (CORS) 
 * in the application. It defines the allowed origins, methods, headers, and other
 * related settings for managing CORS requests.
 *
 * @return array The CORS configuration array.
 * 
 * - 'allowOrigin': array|string
 *      Defines the origins that are allowed to access the server's resources. 
 *      Can be a specific domain (e.g., 'https://example.com') or '*' to allow all origins.
 * 
 * - 'allowMethods': array
 *      Lists the HTTP methods that are allowed for cross-origin requests.
 *      Common methods include 'GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'.
 * 
 * - 'allowHeaders': array
 *      Specifies which headers can be included in the CORS request. For example, 
 *      'Authorization' and 'Content-Type' are commonly used headers in API requests.
 * 
 * - 'exposeHeaders': array
 *      Lists the headers that are safe to expose to the client (browser). These headers
 *      can be made visible via JavaScript on the client side.
 * 
 * - 'maxAge': int
 *      Indicates how long the results of a preflight request can be cached by the client, 
 *      in seconds. A value of 3600 seconds (1 hour) is typically used.
 * 
 * - 'credentials': bool
 *      A boolean value that indicates whether credentials (such as cookies, 
 *      authorization headers, or TLS client certificates) are allowed in cross-origin requests.
 */
return [
    'Cors' => [
        'allowOrigin' => ['*'], // Array of allowed origins, or '*' for all origins.
        'allowMethods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'], // HTTP methods to allow.
        'allowHeaders' => ['Authorization', 'Content-Type'], // Headers allowed in the request.
        'exposeHeaders' => ['Link'], // Headers that can be exposed to the browser.
        'maxAge' => 3600, // Time in seconds for how long the results of a preflight request can be cached.
        'credentials' => true, // Whether to allow cookies and credentials.
    ],
];

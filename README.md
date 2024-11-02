
# Salienture CakePHP 5 CORS Plugin

**Salienture CakePHP 5 CORS Plugin** provides a simple and configurable way to handle Cross-Origin Resource Sharing (CORS) in CakePHP 5 applications. This plugin allows you to manage CORS policies through middleware and configuration, ensuring that your API or web application can securely handle cross-origin requests.

## Features

- Easily configurable CORS settings are available through the CakePHP configuration files.
- Full control over allowed origins, headers, and methods.
- Middleware-based implementation to handle CORS headers at the request level.
- Support for preflight (OPTIONS) requests.
- Enable or disable CORS on a per-route or global basis.
- Lightweight and easy to integrate.

## Installation

### 1. Install the Plugin via Composer

You can install the plugin using Composer by running the following command at the root of your CakePHP 5 project:

```bash
composer require salienture/salienture-cakephp-cors-plugin
```

### 2. Load the Plugin

After installation, load the plugin:

```bash
bin/cake plugin load Salienture/Cors
```

### 3. Configure CORS Settings

You can define the CORS configuration in your `config/app_local.php` file. Here's an example configuration:

```php
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
'Cors' => [
    'allowOrigin' => ['http://localhost:3000'], // Array of allowed origins, or '*' for all origins.
    'allowMethods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'], // HTTP methods to allow.
    'allowHeaders' => ['X-Requested-With', 'Content-Type', 'Authorization'], // Headers allowed in the request. 'true' for all headers. e.g. ['Authorization', 'Content-Type']
    'exposeHeaders' => ['Link'], // Headers that can be exposed to the browser.
    'maxAge' => 3600, // Time in seconds for how long the results of a preflight request can be cached.
    'credentials' => true, // Whether to allow cookies and credentials.
],
```

### 4. Enable or Disable CORS for Specific Routes (Optional)

If you want to enable or disable CORS on specific routes, you can configure it in the `routes.php` file using route scoping:

```php
use Salienture\Cors\Middleware\CorsMiddleware;

$routes->scope('/api', function (RouteBuilder $routes) {
    // Set the default content type to JSON for the /api routes
    $builder->setExtensions(['json']);

    // Add CORS Middleware for the /api routes
    $routes->registerMiddleware('cors', new CorsMiddleware());
    $routes->applyMiddleware('cors');

    // Define your API routes here
    $routes->connect('/posts', ['controller' => 'Posts', 'action' => 'index']);
});
```

## Usage

Once installed and configured, the **Salienture CakePHP 5 CORS Plugin** automatically adds the necessary CORS headers to incoming requests. It handles both simple and preflight (OPTIONS) requests.

### Example: Handling CORS in an API

For example, if you have an API endpoint `/api/posts`, the plugin will:

1. Automatically add CORS headers (`Access-Control-Allow-Origin`, `Access-Control-Allow-Methods`, etc.) to responses.
2. Handle OPTIONS requests for preflight checks.
3. Restrict access based on your configured CORS policy.

### Example CORS Response Headers

If a request is made to your API, the response might include headers like:

```
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Allow-Headers: Authorization, Content-Type
Access-Control-Expose-Headers: Link
Access-Control-Max-Age: 3600
Access-Control-Allow-Credentials: true
```

## Customizing Middleware

If you need more control over how CORS is handled (e.g., conditional behavior for different routes), you can modify the `CorsMiddleware` class to suit your needs.

## Testing

To verify that CORS functions correctly, make cross-origin HTTP requests using a client such as `Postman`, or make AJAX requests from a browser in a different domain. Ensure that the appropriate CORS headers are present in the response.

## License

This plugin is licensed under the MIT License.

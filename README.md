
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

After installation, load the plugin by adding the following line in your `Application.php` file (`src/Application.php`):

```php
public function bootstrap(): void
{
    parent::bootstrap();
    
    // Load the Salienture CakePHP 5 CORS Plugin
    $this->addPlugin('Salienture/Cors');
}
```

### 3. Add Middleware

Add the middleware to your CakePHP 5 application's middleware stack to enable CORS functionality. You can do this in the `Application.php` file:

```php
use Salienture\Cors\Middleware\CorsMiddleware;

public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
{
    $middlewareQueue
        // Add the CORS Middleware
        ->add(new CorsMiddleware());

    return $middlewareQueue;
}
```

By default it's added in **middleware()** to the **CorsPlugin.php** file. 

### 4. Configure CORS Settings

You can define the CORS configuration in your `config/app.php` or `config/cors.php` file. Here's an example configuration:

```php
return [
    'Cors' => [
        'allowOrigin' => ['*'], // Array of allowed origins, or '*' for all origins.
        'allowMethods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'], // HTTP methods to allow.
        'allowHeaders' => ['Authorization', 'X-Requested-With', 'Origin', 'Content-Type', 'Accept'], // Headers allowed in the request.
        'exposeHeaders' => ['Link'], // Headers that can be exposed to the browser.
        'maxAge' => 3600, // Time in seconds for how long the results of a preflight request can be cached.
        'credentials' => true, // Whether to allow cookies and credentials.
    ],
];
```

### 5. Enable or Disable CORS for Specific Routes (Optional)

If you want to enable or disable CORS on specific routes, you can configure it in the `routes.php` file using route scoping:

```php
use Salienture\Cors\Middleware\CorsMiddleware;

$routes->scope('/api', function (RouteBuilder $routes) {
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

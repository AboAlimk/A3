<?php
/**
 *
 *   A3 Framework
 *   Version: 1.2
 *   Date: 04-2023
 *   Author: Abdulsattar Alkhalaf
 *   AboAlimk@gmail.com
 *
 */

namespace A3App\A3Request;

use A3Error;

class A3RequestObject
{

    private string $name;
    private string $base_route;
    private string|array $methods = '';
    private array $data = [];
    private array $requestRouteItems = [];
    private bool $error = false;

    public function __construct($name, $baseRoute, $methods)
    {
        $this->name = $name;
        $this->base_route = $baseRoute;
        if (!is_array($methods)) {
            $methods = [$methods];
        }
        $this->methods = $methods;
    }

    private function repairLink($link)
    {
        if (str_contains(A3_DOMAIN . $link, A3_ROOT)) {
            return str_replace(A3_ROOT, '', A3_DOMAIN . $link);
        }
        return $link;
    }

    private function removeRouteSlashes($route)
    {
        if (str_starts_with($route, '/')) {
            $route = substr($route, 1);
        }
        if (str_ends_with($route, '/')) {
            $route = substr($route, 0, strlen($route) - 1);
        }
        return $route;
    }

    public function setError($error = true): void
    {
        $this->error = (boolean)$error;
    }

    public function setData($data): void
    {
        $this->data = $data;
    }

    public function setRequestRouteItems($data): void
    {
        $this->requestRouteItems = $data;
    }

    public function getMethod(): string
    {
        return strtoupper(A3GetPathValue($_SERVER, 'REQUEST_METHOD', ''));
    }

    public function getRoute(): string
    {
        return $this->repairLink(A3GetPathValue($_SERVER, 'REDIRECT_URL', '/'));
    }

    public function getRouteItems($index = null): mixed
    {
        $route = $this->removeRouteSlashes($this->getRoute());
        if (!$route) {
            return [];
        }
        $route_items = explode('/', $route);
        if ($index === null) {
            return $route_items;
        }
        return A3GetPathValue($route_items, $index, '');
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getError(): bool
    {
        return $this->error;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function get($name = null, $def = false): mixed
    {
        if ($name) {
            return A3GetPathValue($_GET, $name, $def);
        }
        return $_GET;
    }

    public function post($name = null, $def = false): mixed
    {
        if ($name) {
            return A3GetPathValue($_POST, $name, $def);
        }
        return $_POST;
    }

    public function put($name = null)
    {
        if (in_array("PUT", $this->methods)) {
            parse_str(file_get_contents("php://input"), $data);
            if ($name) {
                return A3GetPathValue($data, $name);
            }
            return $data;
        }
        return [];
    }

    public function delete($name = null)
    {
        if (in_array("DELETE", $this->methods)) {
            parse_str(file_get_contents("php://input"), $data);
            if ($name) {
                return A3GetPathValue($data, $name);
            }
            return $data;
        }
        return [];
    }

    public function files($name = null): mixed
    {
        if ($name) {
            return A3GetPathValue($_FILES, $name);
        }
        return $_FILES;
    }

    public function getUserIp()
    {
        return A3GetPathValue($_SERVER, 'REMOTE_ADDR', '');
    }

    public function getReferer(): string
    {
        return A3GetPathValue($_SERVER, 'HTTP_REFERER', '');
    }

    public function getAuth()
    {
        return A3GetPathValue($_SERVER, 'HTTP_AUTHORIZATION', '');
    }

    public function getBaseRoute(): string
    {
        return $this->base_route;
    }

    public function isSecure(): bool
    {
        return A3GetPathValue($_SERVER, 'HTTPS', '') == 'on';
    }

    public function getUri(): string
    {
        return $this->repairLink(A3GetPathValue($_SERVER, 'REQUEST_URI', '/'));
    }

    public function getQuery(): string
    {
        return A3GetPathValue($_SERVER, 'QUERY_STRING', '');
    }

    public function getRequestRouteItems($key = null): mixed
    {
        if ($key === null) {
            return $this->requestRouteItems;
        }
        return A3GetPathValue($this->requestRouteItems, $key, '');
    }

    public function getLink($replace = []): string
    {
        if ($replace && is_array($replace)) {
            return A3RequestRoute::parse($this->getBaseRoute(), $replace);
        }
        return $this->getRoute();
    }

    public function getFullLink(): string
    {
        return A3_ROOT . $this->getRoute();
    }

    public function getFullRequestData(): array
    {
        return $_SERVER;
    }

    public function getAll(): array
    {
        return [
            'name' => $this->getName(),
            'method' => $this->getMethod(),
            'data' => $this->getData(),
            'get' => $this->get(),
            'post' => $this->post(),
            'put' => $this->put(),
            'delete' => $this->delete(),
            'files' => $this->files(),
            'user_ip' => $this->getUserIp(),
            'referer' => $this->getReferer(),
            'auth' => $this->getAuth(),
            'base_route' => $this->getBaseRoute(),
            'secure' => $this->isSecure(),
            'uri' => $this->getUri(),
            'query' => $this->getQuery(),
            'route' => $this->getRoute(),
            'route_items' => $this->getRouteItems(),
            'request_route_items' => $this->getRequestRouteItems(),
            'link' => $this->getLink(),
            'full_link' => $this->getFullLink(),
            'full_request_data' => $this->getFullRequestData(),
        ];
    }

    public function __call($method, $parameters)
    {
        self::__error('A3RequestObject::' . $method, __FUNCTION__);
    }

    private static function __error($replace, $a3Function): void
    {
        A3Error::errorTrigger([
            'text' => 'undefined_method',
            'replace' => [$replace],
            'a3Class' => 'A3RequestObject',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace(),
        ]);
    }

}
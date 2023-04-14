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

namespace A3App;

class A3Redirect
{

    private string $url;
    private bool $isA3Request = false;
    private int $code;
    private mixed $a3Request = '';
    private array $a3RequestReplace = [];

    public function __construct($url = '', $code = 302)
    {
        $this->url = $url;
        $this->code = $code;
    }

    public function a3Request($name, $replace = []): A3Redirect
    {
        $this->isA3Request = true;
        $this->a3Request = $name;
        $this->a3RequestReplace = $replace;
        return $this;
    }

    public function code($code): A3Redirect
    {
        $this->code = $code;
        return $this;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getUrl(): string
    {
        if ($this->isA3Request) {
            return A3RequestFull($this->a3Request, $this->a3RequestReplace);
        } else {
            return $this->parseUrl();
        }
    }

    private function parseUrl(): string
    {
        if (str_starts_with($this->url, '/')) {
            return A3_ROOT . $this->url;
        }
        return $this->url;
    }

    public function redirect(): void
    {
        header("Location: " . $this->getUrl(), true, $this->getCode());
    }

    public static function redirectToSecure(): void
    {
        if (!A3::isSecure()) {
            $redirect = new A3Redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301);
            $redirect->redirect();
        }
    }

}
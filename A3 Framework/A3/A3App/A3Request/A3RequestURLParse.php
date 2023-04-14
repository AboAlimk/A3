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

class A3RequestURLParse
{

    private string $scheme = '';
    private string $host = '';
    private string $domain = '';
    private string $subDomain = '';
    private string $port = '';
    private string $path = '';
    private string $query = '';
    private string $fragment = '';

    public function __construct($url)
    {
        $this->parseUrl($url);
    }

    private function parseUrl($url): void
    {
        if (!str_starts_with($url, 'http')) {
            $url = 'http://' . $url;
        }
        $urlObject = parse_url($url);
        $this->scheme = strtolower(A3GetPathValue($urlObject, 'scheme', ''));
        $this->host = strtolower(A3GetPathValue($urlObject, 'host'));
        $isIp = (boolean)ip2long($this->host);
        $this->domain = $this->getDomain($this->host, $isIp);
        $this->subDomain = $this->getSubDomain($this->host, $isIp);
        $this->port = (string)A3GetPathValue($urlObject, 'port', '');
        $this->path = strtolower(A3GetPathValue($urlObject, 'path', ''));
        $this->query = strtolower(A3GetPathValue($urlObject, 'query', ''));
        $this->fragment = strtolower(A3GetPathValue($urlObject, 'fragment', ''));
    }

    private function getDomain($host, $isIp): string
    {
        if ($host) {
            if ($isIp) {
                return $host;
            }
            if (!str_contains($host, '.')) {
                return $host;
            }
            $host = explode('.', $host);
            $host = array_slice($host, -2, 2);
            return implode('.', $host);
        }
        return '';
    }

    private function getSubDomain($host, $isIp)
    {
        if ($host && !$isIp) {
            if (!str_contains($host, '.')) {
                return $host;
            }
            $host = explode('.', $host);
            $host = array_slice($host, 0, -2);
            return implode('.', $host);
        }
        return '';
    }

    public static function parse($url): A3RequestURLParse
    {
        return new A3RequestURLParse($url);
    }

    public static function match($subDomain, $www): bool
    {
        $baseUrlParser = new A3RequestURLParse(A3_DOMAIN);
        $currentSubDomain = $baseUrlParser->subDomain();
        $subDomain = $subDomain ?: '';
        $currentSubDomain = $currentSubDomain ?: '';
        $subDomain = strtolower($subDomain);
        $currentSubDomain = strtolower($currentSubDomain);
        if (!$www) {
            if ($currentSubDomain === 'www') {
                $currentSubDomain = '';
            }
            if (str_starts_with($currentSubDomain, 'www.')) {
                $currentSubDomain = substr($currentSubDomain, 4);
            }
        }
        return $subDomain === $currentSubDomain;
    }

    public function subDomain(): string
    {
        return $this->subDomain;
    }

    public function isSecure(): bool
    {
        return $this->scheme == 'https';
    }

    public function scheme(): string
    {
        return $this->scheme;
    }

    public function host(): string
    {
        return $this->host;
    }

    public function domain(): string
    {
        return $this->domain;
    }

    public function port(): string
    {
        return $this->port;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function query(): string
    {
        return $this->query;
    }

    public function fragment(): string
    {
        return $this->fragment;
    }

}
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

class A3Response
{

    private string $content;
    private array $headers;

    public function __construct($content = '', $headers = [])
    {
        $this->content = $content;
        if (!is_array($headers)) {
            $headers = [$headers];
        }
        $this->headers = $headers;
    }

    public function header($header): A3Response
    {
        if (is_string($header)) {
            $this->headers[] = $header;
        }
        return $this;
    }

    public function content($content): A3Response
    {
        $this->content = $content;
        return $this;
    }

    public function addContent($content): A3Response
    {
        $this->content .= $content;
        return $this;
    }

    public function render(): void
    {
        $this->content = $this->content ?: '';
        if (count($this->headers)) {
            foreach ($this->headers as $header) {
                header($header);
            }
        }
        print_r($this->content);
    }

}
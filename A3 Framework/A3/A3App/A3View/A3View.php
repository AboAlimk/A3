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

namespace A3App\A3View;

use A3String;
use A3Error;

class A3View
{

    private const VIEWS_URI = '/A3User/A3Views/';
    private const RENDERED_VIEWS_URI = '/A3User/A3Storage/A3Views';
    private const VIEW_PREFIX = 'A3View_';
    private const VIEW_EXT = '.php';
    private string $a3ViewBaseUri;
    private string $a3ViewUri;
    private array $a3ViewData;
    private array $a3ViewHeaders = [];
    private string $mainExtends = '';
    private bool $isError = false;

    public function __construct($uri, $data = [])
    {
        $this->a3ViewBaseUri = $uri;
        $this->a3ViewUri = $this->getViewUri($uri);
        if (!is_array($data)) {
            $data = [$data];
        }
        $this->a3ViewData = $data;
    }

    public function with($key = null, $value = null): A3View
    {
        if (is_null($key) || is_null($value)) {
            self::__error('error_parameters_count', [__FUNCTION__, '2'], __FUNCTION__);
        }
        if (!A3String::is($key) || $key === '') {
            self::__error('error_parameter_type', ['key', 'string'], __FUNCTION__);
        }
        $this->a3ViewData[$key] = $value;
        return $this;
    }

    public function header($header): A3View
    {
        if (A3String::is($header)) {
            $this->a3ViewHeaders[] = $header;
        }
        return $this;
    }

    private function getViewUri($uri): string
    {
        return A3_ROOT_DIR . self::VIEWS_URI . A3String::getViewPath($uri) . self::VIEW_EXT;
    }

    public function renderView(): void
    {
        if (self::isValidView($this->a3ViewUri, true) === false) {
            self::__error('view_not_found', [$this->a3ViewUri], __FUNCTION__, true);
        } else {
            $a3ViewHash = $this->getViewHash($this->a3ViewUri);
            $renderedView = $this->getRenderedViewUri($a3ViewHash);
            $renderedViewTs = is_file($renderedView) && file_exists($renderedView) ? filemtime($renderedView) : 0;
            $a3ViewTs = filemtime($this->a3ViewUri);
            $__A3ViewExtends = new A3ViewExtends();
            if (array_key_exists('__A3ViewExtends', $this->a3ViewData)) {
                $___A3ViewExtends = A3GetPathValue($this->a3ViewData, '__A3ViewExtends');
                if ($___A3ViewExtends instanceof A3ViewExtends) {
                    $__A3ViewExtends = $___A3ViewExtends;
                }
            }
            if (!$renderedViewTs || $renderedViewTs < $a3ViewTs) {
                if (!is_dir(A3_ROOT_DIR . self::RENDERED_VIEWS_URI)) {
                    mkdir(A3_ROOT_DIR . self::RENDERED_VIEWS_URI);
                }
                $a3ViewCode = file_get_contents($this->a3ViewUri);
                $a3ViewCode = $this->replace($a3ViewCode);
                if ($this->mainExtends !== '') {
                    $a3ViewCode .= "\n" . '<?php A3View' . $this->mainExtends . '->with(\'__A3ViewExtends\',$__A3ViewExtends)->renderView(); ' . "?>\n";
                }
                $a3ViewCode .= "\n<?php /*" . $this->a3ViewUri . "*/ ?>";
                file_put_contents($renderedView, $a3ViewCode);
            }
            if (count($this->a3ViewHeaders)) {
                foreach ($this->a3ViewHeaders as $header) {
                    header($header);
                }
            }
            if ($this->a3ViewBaseUri === A3Settings('error_page')) {
                $this->addError();
            }
            extract($this->a3ViewData, EXTR_SKIP);
            include $renderedView;
        }
    }

    private function addError(): void
    {
        if (!$this->isError) {
            $this->isError = true;
            header(A3VH_E404);
        }
    }

    private function getViewHash($uri): string
    {
        return self::VIEW_PREFIX . md5($uri);
    }

    private function getRenderedViewUri($uri): string
    {
        return A3_ROOT_DIR . self::RENDERED_VIEWS_URI . '/' . $uri . self::VIEW_EXT;
    }

    private function replace($code): string
    {
        $code = preg_replace($this->getPattern(['if', 'elseif', 'switch', 'case', 'for', 'foreach']), "<?php $1$2: ?>", $code);
        $code = preg_replace($this->getPattern(['isset', 'empty']), "<?php if($1$2): ?>", $code);
        $code = preg_replace($this->getPattern(['else', 'default'], 1), "<?php $1: ?>", $code);
        $code = preg_replace($this->getPattern(['endif', 'endfor', 'endforeach', 'endswitch', 'continue', 'break'], 1), "<?php $1; ?>", $code);
        $code = preg_replace($this->getPattern(['endisset', 'endempty'], 1), "<?php endif; ?>", $code);
        $code = preg_replace($this->getPattern(['A3', 'A3L', 'A3Words', 'A3Data', 'A3Date', 'A3GRS', 'A3Settings', 'A3Assets', 'A3Request', 'A3RequestFull']), "<?php print_r($1$2); ?>", $code);
        $code = preg_replace($this->getPattern('A3nl2br'), "<?php echo nl2br(A3$2); ?>", $code);
        $code = preg_replace($this->getPattern('A3Root', 1), "<?php echo A3_ROOT; ?>", $code);
        $code = preg_replace($this->getPattern('A3Domain', 1), "<?php echo A3_DOMAIN; ?>", $code);
        $code = preg_replace($this->getPattern('upper'), "<?php echo strtoupper$2; ?>", $code);
        $code = preg_replace($this->getPattern('lower'), "<?php echo strtolower$2; ?>", $code);
        $code = preg_replace($this->getPattern('php', 1), "<?php ", $code);
        $code = preg_replace($this->getPattern('endphp', 1), " ?>", $code);
        $code = preg_replace($this->getPattern('include'), "<?php A3View$2->renderView(); ?>", $code);
        $code = preg_replace('/{{{\s*(.+?)\s*}}}/', "<?php echo $1; ?>", $code);
        $code = preg_replace('/{{\s*(.+?)\s*}}/', "<?php echo htmlspecialchars($1); ?>", $code);
        $code = preg_replace_callback($this->getPattern('replace'), function ($matches) {
            if ($matches && !empty($matches[2])) {
                return '<?php $__A3ViewExtends->getReplace' . $matches[2] . "; ?>";
            }
            return '';
        }, $code);
        $code = preg_replace_callback($this->getPattern('extends'), function ($matches) {
            if ($matches && !empty($matches[2])) {
                $this->mainExtends = $matches[2];
            }
            return '';
        }, $code);
        $code = preg_replace_callback($this->getPattern('section'), function ($matches) {
            if ($matches && !empty($matches[2])) {
                return '<?php $__A3ViewExtends->sectionStart' . $matches[2] . "; ?>";
            }
            return '';
        }, $code);
        $code = preg_replace_callback($this->getPattern('endsection', 1), function ($matches) {
            if ($matches && !empty($matches[1])) {
                return '<?php $__A3ViewExtends->sectionEnd();' . " ?>";
            }
            return '';
        }, $code);
        return trim($code);
    }

    private function getPattern($search, $isEnd = false): string
    {
        if (is_array($search)) {
            $search = implode('|', $search);
        }
        return '/@(' . $search . ($isEnd ? ')\b/' : ')(\s*\(((?:[^\(\)]++|(?2))*)\))/');
    }

    public static function isValidView($uri, $fullUri = false): bool
    {
        $file = A3_ROOT_DIR . self::VIEWS_URI . A3String::getViewPath($uri) . self::VIEW_EXT;
        if ($fullUri) {
            $file = $uri;
        }
        if ($file && is_file($file) && file_exists($file)) {
            return true;
        }
        return false;
    }

    public static function publishHeader($header): void
    {
        if (A3String::is($header)) {
            header($header);
        }
    }

    private static function __error($text, $replace, $a3Function, $skip = false): void
    {
        A3Error::errorTrigger([
            'text' => $text,
            'replace' => $replace,
            'a3Class' => 'A3View',
            'a3Function' => $a3Function,
            'debugData' => debug_backtrace(),
            'skip' => $skip,
        ]);
    }

}
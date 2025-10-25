<?php

use LaravLite\Facades\Collection;
use LaravLite\Facades\View;
use LaravLite\Http\{Response, Request};


if (!function_exists('response')) {
    function response()
    {
        return new class {
            public function json($data, $status = 200): Response
            {
                return (new Response())->json($data, $status);
            }

            //public function view($template, $data = []): \LaravLite\Response
            //{
            //    return (new \LaravLite\Response())->view($template, $data);
            //}

            //public function redirect($url, $status = 302): \LaravLite\Response
            //{
            //    return (new \LaravLite\Response())->redirect($url, $status);
            //}
        };
    }
}

if (!function_exists('request')) {
    function request()
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new Request();
        }
        return $instance;
    }
}

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        echo '<!DOCTYPE html>';
        echo '<html><head><meta charset="utf-8"><title>Dump & Die</title>';
        echo '<style>
            main { background: #1e1e2f; color: #f8f8f2; font-family: monospace; padding: 20px; }
            pre { white-space: pre-wrap; word-wrap: break-word; }
            .dd-key { color: #8be9fd; }
            .dd-string { color: #f1fa8c; }
            .dd-int { color: #50fa7b; }
            .dd-float { color: #bd93f9; }
            .dd-bool { color: #ff79c6; }
            .dd-null { color: #6272a4; font-style: italic; }
            .dd-toggle { cursor: pointer; }
            .dd-content { display: none; margin-left: 20px; }
        </style>';
        echo '<script>
            function toggleDD(el) {
                const content = el.nextElementSibling;
                if(content.style.display === "block") content.style.display = "none";
                else content.style.display = "block";
            }
        </script>';
        echo '</head><body><main>';

        $renderVar = function ($var) use (&$renderVar) {
            if (is_array($var)) {
                echo '<div class="dd-toggle" onclick="toggleDD(this)">▶ array[' . count($var) . ']</div>';
                echo '<div class="dd-content">';
                foreach ($var as $k => $v) {
                    echo '<span class="dd-key">' . htmlspecialchars($k) . '</span> => ';
                    $renderVar($v);
                    echo '<br>';
                }
                echo '</div>';
            } elseif (is_object($var)) {
                $class = get_class($var);
                echo '<div class="dd-toggle" onclick="toggleDD(this)">▶ object(' . $class . ') {}</div>';
                echo '<div class="dd-content">';

                if ($var instanceof \JsonSerializable) {
                    $var = $var->jsonSerialize();
                    $renderVar($var);
                } elseif (method_exists($var, '__debugInfo')) {
                    $var = $var->__debugInfo();
                    $renderVar($var);
                } else {
                    foreach (get_object_vars($var) as $k => $v) {
                        echo '<span class="dd-key">' . htmlspecialchars($k) . '</span> => ';
                        $renderVar($v);
                        echo '<br>';
                    }
                }
                echo '</div>';
            } elseif (is_string($var)) {
                echo '<span class="dd-string">"' . htmlspecialchars($var) . '"</span>';
            } elseif (is_int($var)) {
                echo '<span class="dd-int">' . $var . '</span>';
            } elseif (is_float($var)) {
                echo '<span class="dd-float">' . $var . '</span>';
            } elseif (is_bool($var)) {
                echo '<span class="dd-bool">' . ($var ? "true" : "false") . '</span>';
            } elseif (is_null($var)) {
                echo '<span class="dd-null">null</span>';
            } else {
                echo htmlspecialchars((string)$var);
            }
        };

        foreach ($vars as $var) {
            $renderVar($var);
            echo '<br>';
        }

        echo '</main></body></html>';
        exit;
    }
}

// -------------------- ENV --------------------
if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        static $loaded = false;
        if (!$loaded) {
            if (file_exists(__DIR__ . '/../.env')) {
                foreach (file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                    if (str_starts_with(trim($line), '#')) continue;
                    [$name, $value] = explode('=', $line, 2) + [null, null];
                    if ($name) putenv("$name=$value");
                }
            }
            $loaded = true;
        }
        $value = getenv($key);
        return $value === false ? $default : $value;
    }
}

// -------------------- PATH HELPERS --------------------
if (!function_exists('storage_path')) {
    function storage_path(string $path = ''): string
    {
        return rtrim(__DIR__ . '/../storage', '/') . ($path ? '/' . ltrim($path, '/') : '');
    }
}

if (!function_exists('public_path')) {
    function public_path(string $path = ''): string
    {
        return rtrim(__DIR__ . '/../public', '/') . ($path ? '/' . ltrim($path, '/') : '');
    }
}

if (!function_exists('database_path')) {
    function database_path(string $path = ''): string
    {
        return rtrim(__DIR__ . '/../database', '/') . ($path ? '/' . ltrim($path, '/') : '');
    }
}

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        return rtrim(__DIR__ . '/..', '/') . ($path ? '/' . ltrim($path, '/') : '');
    }
}
// -------------------- ABORT --------------------
if (!function_exists('abort')) {
    function abort(int $code = 500, string $message = ''): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode(['error' => $message ?: http_response_code()]);
        exit;
    }
}


// -------------------- NOW --------------------
if (!function_exists('now')) {
    function now(): string
    {
        return date('Y-m-d H:i:s');
    }
}

// -------------------- COLLECT HELPER --------------------
if (!function_exists('collect')) {
    function collect($items = []): Collection
    {
        return $items instanceof Collection ? $items : new Collection(is_array($items) ? $items : [$items]);
    }
}

if (!function_exists('config')) {
    function config(string $key, $default = null)
    {
        return \LaravLite\Config::get($key, $default);
    }
}

if (!function_exists('view')) {
    function view($route, $data = [])
    {
        echo View::render($route, $data);
    }
}

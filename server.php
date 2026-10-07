<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

$publicPath = __DIR__.'/public';

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server.
// On Windows, PHP built-in server returns 403 Forbidden for symlinks/junctions pointing outside public.
// Therefore, /storage/ requests should be passed to Laravel rather than served directly by PHP built-in server.
if ($uri !== '/' && !str_starts_with($uri, '/storage/') && file_exists($publicPath.$uri)) {
    return false;
}

require_once $publicPath.'/index.php';

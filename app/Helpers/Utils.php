<?php

if (!function_exists('docs_path')) {
    function docs_path(string|null $path = null): string {
        $docs_path = base_path('resources/docs');

        return $docs_path . ($path ? '/' . $path : '');
    }
}

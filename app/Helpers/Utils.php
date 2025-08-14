<?php

if (!function_exists('docs_path')) {
    /**
     * Get the absolute path to the `resources/docs` directory.
     *
     * This helper is useful for accessing project documentation files
     * stored under the `resources/docs` directory. Optionally, you can
     * provide a relative file path to append to the base documentation
     * path.
     *
     * Example usage:
     * ```php
     * $path = docs_path(); // /var/www/project/resources/docs
     * $file = docs_path('user_guide.md'); // /var/www/project/resources/docs/user_guide.md
     * ```
     *
     * @param  string|null  $path  Optional relative path inside `resources/docs`
     * @return string  The absolute path to the docs directory or a specific file within it.
     */
    function docs_path(string|null $path = null): string {
        $docs_path = base_path('resources/docs');

        return $docs_path . ($path ? '/' . $path : '');
    }
}

if (! function_exists('normalizeArray')) {
    /**
     * Recursively normalize arrays (and objects) for stable comparisons.
     *
     * This function is designed for use in tests or equality checks
     * where two complex nested data structures need to be compared
     * without being affected by:
     *  - Different associative key ordering
     *  - Presence of stdClass objects instead of arrays
     *
     * Behavior:
     *  1. Converts any stdClass instance to an associative array.
     *  2. Recursively processes nested arrays/objects.
     *  3. Sorts associative arrays by their keys for stable ordering.
     *     (Indexed arrays preserve their original order.)
     *
     * Example:
     * ```php
     * $a = ['b' => 1, 'a' => 2];
     * $b = (object) ['a' => 2, 'b' => 1];
     *
     * normalizeArray($a) === normalizeArray($b); // true
     * ```
     *
     * This is especially useful in unit tests where the expected and
     * actual arrays are semantically identical but may differ in key
     * order or object/array representation.
     *
     * @param  mixed  $value  The value to normalize (array, object, scalar).
     * @return mixed  The normalized value (arrays sorted, objects converted).
     */
    function normalizeArray(mixed $value): mixed
    {
        if ($value instanceof \stdClass) {
            $value = (array) $value;
        }
        if (! is_array($value)) {
            return $value;
        }

        foreach ($value as $k => $v) {
            // Convert nested stdClass to array before recursing
            if ($v instanceof \stdClass) {
                $v = (array) $v;
            }
            $value[$k] = normalizeArray($v);
        }

        $isAssoc = array_keys($value) !== range(0, count($value) - 1);
        if ($isAssoc) {
            ksort($value);
        }

        return $value;
    }
}

if (! function_exists('b64urlDecode')) {
    /**
     * Decode a Base64 URL-safe encoded string.
     *
     * This helper function is a variant of base64 decoding
     * designed for strings encoded using the URL- and
     * filename-safe alphabet, replacing `+` with `-` and `/` with `_`.
     *
     * It also adds necessary padding characters (`=`) if the length of
     * the input string is not a multiple of 4, ensuring compatibility
     * with PHP's `base64_decode()` function.
     *
     * Example:
     * ```php
     * $decoded = b64urlDecode('SGVsbG8td29ybGQ_');
     * // returns: "Hello-world?"
     * ```
     *
     * @param  string  $txt  The Base64 URL-safe encoded string to decode.
     * @return string  The decoded string, or an empty string on failure.
     */
    function b64urlDecode(string $txt): string {
        $pad = strlen($txt) % 4;
        if ($pad) $txt .= str_repeat('=', 4 - $pad);
        return base64_decode(strtr($txt, '-_', '+/')) ?: '';
    }
}

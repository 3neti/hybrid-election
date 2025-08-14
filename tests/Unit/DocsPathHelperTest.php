<?php

use function PHPUnit\Framework\assertTrue;

it('returns the base docs directory path', function () {
    expect(function_exists('docs_path'))->toBeTrue();

    $expected = base_path('resources/docs');
    expect(docs_path())->toBe($expected);
});

it('appends a relative path when provided', function () {
    $expected = base_path('resources/docs') . '/user_guide.md';
    expect(docs_path('user_guide.md'))->toBe($expected);
});

// (Optional) sanity check: result is a string path (directory may or may not exist)
it('returns a string path', function () {
    $path = docs_path('nested/example.txt');
    expect($path)->toBeString();
});

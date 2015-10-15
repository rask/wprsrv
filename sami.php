<?php

namespace Wprsrv;

use Sami\Sami;
use Symfony\Component\Finder\Finder;

/**
 * Sami PHP documentation run config.
 */

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('vendor')
    ->exclude('node_modules')
    ->exclude('docs')
    ->exclude('docs-cache')
    ->exclude('tests')
    ->in(__DIR__);

return new Sami($iterator, [
    'title'                => 'wprsrv PHP Documentation',
    'build_dir'            => __DIR__.'/docs',
    'cache_dir'            => __DIR__.'/docs-cache',
    'default_opened_level' => 2,
]);

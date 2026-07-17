<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Ziggy Route Filtering
    |--------------------------------------------------------------------------
    |
    | Configure which routes are included in the Ziggy output. You can either
    | specify an array of route names to include ('only'), or an array of
    | route names to exclude ('except'). Using wildcards (*) is supported.
    |
    | Note: You must choose either 'only' or 'except'. Setting both will
    | disable filtering and return all routes.
    |
    */

    'only' => ['*'],
    'except' => [],

    /*
    |--------------------------------------------------------------------------
    | Route Groups
    |--------------------------------------------------------------------------
    |
    | Define groups of routes that can be conditionally exposed to different
    | parts of your application via the @routes Blade directive.
    |
    */

    'groups' => [],

    /*
    |--------------------------------------------------------------------------
    | Middleware Filtering
    |--------------------------------------------------------------------------
    |
    | If specified, only routes matching the given middleware will be included.
    | Set to false or null to include all routes regardless of middleware.
    |
    */

    'middleware' => false,

    /*
    |--------------------------------------------------------------------------
    | Output Configuration
    |--------------------------------------------------------------------------
    |
    | Customize how Ziggy's output is generated.
    |
    */

    'output' => [
        'path' => 'resources/js/ziggy.js',
        'types-path' => 'resources/js/ziggy.d.ts',
        'file' => \Tighten\Ziggy\Output\File::class,
        'types' => \Tighten\Ziggy\Output\Types::class,
        'json' => \Tighten\Ziggy\Output\Json::class,
        'script' => \Tighten\Ziggy\Output\Script::class,
        'merge_script' => \Tighten\Ziggy\Output\MergeScript::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Skip Route Function
    |--------------------------------------------------------------------------
    |
    | If true, the route() JavaScript function will not be included in the
    | @routes output. Useful if you only want the route configuration.
    |
    */

    'skip-route-function' => false,
];
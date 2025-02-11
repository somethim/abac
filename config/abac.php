<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ABAC Cache Configuration
    |--------------------------------------------------------------------------
    |
    | This section defines the settings for the ABAC cache, including whether
    | caching is enabled, the cache store to use, the time-to-live (TTL) for
    | cache entries, and cache warming options.
    |
    */

    'cache' => [
        'enabled' => env('ABAC_CACHE_ENABLED', true),
        'store' => env('ABAC_CACHE_STORE', 'database'),
        'ttl' => env('ABAC_CACHE_TTL', 3600),
        'prefix' => env('ABAC_CACHE_PREFIX', 'abac_'),
        'warming' => [
            'enabled' => env('ABAC_CACHE_WARMING_ENABLED', true),
            'schedule' => env('ABAC_CACHE_WARMING_SCHEDULE', 'hourly'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | ABAC Evaluation Configuration
    |--------------------------------------------------------------------------
    |
    | This section defines the settings for ABAC evaluation, including whether
    | strict validation is enabled.
    |
    */

    'evaluation' => [
        'strict_validation' => env('ABAC_STRICT_VALIDATION', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | ABAC Monitoring Configuration
    |--------------------------------------------------------------------------
    |
    | This section defines the settings for ABAC monitoring, including logging
    | options, performance monitoring, and event handling.
    |
    */

    'monitoring' => [
        'logging' => [
            'enabled' => env('ABAC_LOGGING_ENABLED', true),
            'channel' => env('ABAC_LOG_CHANNEL', 'abac'),
            'detailed' => env('ABAC_DETAILED_LOGGING', false),
        ],
        'performance' => [
            'enabled' => env('ABAC_PERFORMANCE_LOGGING_ENABLED', true),
            'slow_threshold' => env('ABAC_SLOW_EVALUATION_THRESHOLD', 100),
        ],
        'events' => [
            'enabled' => env('ABAC_EVENTS_ENABLED', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | ABAC Operators Configuration
    |--------------------------------------------------------------------------
    |
    | This section defines the settings for ABAC operators, including any
    | disabled or custom operators.
    |
    */

    'operators' => [
        'disabled' => [], // key => class name in the format of 'zennit\ABAC\Operators\OperatorName'
        'custom' => [], // key => class name in the format of 'zennit\ABAC\Operators\OperatorName'
    ],

    /*
    |--------------------------------------------------------------------------
    | ABAC Database Configuration
    |--------------------------------------------------------------------------
    |
    | This section defines the settings for the ABAC database, including the
    | user attribute subject type.
    |
    */

    'database' => [
        'object_additional_attributes' => env('ABAC_OBJECT_ADDITIONAL_ATTRIBUTES', 'App\Models\User'),
        'soft_deletes_column' => 'deleted_at',
    ],

    /*
    |--------------------------------------------------------------------------
    | ABAC Seeders Configuration
    |--------------------------------------------------------------------------
    |
    | This section defines the paths to the json files used by the ABAC seeders.
    | These paths are relative to the resources' directory.
    */
    'seeders' => [
        'object_attribute_path' => 'stubs/abac/object_attribute_path.json',
        'subject_attribute_path' => 'stubs/abac/subject_attribute_path.json',
        'policy_file_path' => 'stubs/abac/abac_policy_file_path.json',
    ],

    /*
    |--------------------------------------------------------------------------
    | ABAC Middleware Configuration
    |--------------------------------------------------------------------------
    |
    | This section defines the settings for ABAC middleware, including the
    | method to retrieve the subject and any excluded routes.
    |
    */

    'middleware' => [
        'object_method' => env('ABAC_MIDDLEWARE_OBJECT_METHOD', 'user'),
        'excluded_routes' => [],
        'path_patterns' => [], // key => resource path eg.
    ],
];

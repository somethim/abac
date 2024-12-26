# ABAC (Attribute-Based Access Control) for Laravel

A flexible and powerful ABAC implementation for Laravel applications.

---

## Table of Contents

1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Quick Start](#quick-start)
4. [Basic Usage](#basic-usage)
5. [Commands](#commands)
6. [Configuration](#configuration)
7. [Defining Policies](#defining-policies)
8. [Operators](#operators)
9. [Middleware](#middleware)
10. [Events](#events)
11. [Advanced Usage](#advanced-usage)
12. [Caching](#caching)
13. [Requirements](#requirements)
14. [Contributing](#contributing)
15. [Security](#security)
16. [License](#license)

---

## Introduction

ABAC provides fine-grained access control by evaluating attributes of users, resources, and the context of a request.

This package integrates seamlessly with Laravel to offer powerful access control mechanisms.

---

## Installation

Install the package via Composer:

```bash
composer require zennit/abac
```

Add the service provider to your `bootstrap/providers.php`:

```php
return [
    // ... other providers
    zennit\ABAC\Providers\AbacServiceProvider::class,
];
```

---

## Quick Start

1. Publish configuration and migrations:
   ```bash
   php artisan zennit_abac:publish
   ```

2. Run migrations:
   ```bash
   php artisan migrate
   ```

---

## Basic Usage

Here's an example of how to perform access control checks:

```php
use zennit\ABAC\DTO\AccessContext;
use zennit\ABAC\Facades\Abac;

// Create an access context
$context = new AccessContext(
    subject: $user,
    resource: 'posts',
    operation: 'update',
    resourceIds: [$postId]
);

// Simple check
if (Abac::can($context)) {
    // Allow action
}

// Detailed evaluation
$result = Abac::evaluate($context);
```

---

## Commands

### Publishing Commands

```bash
# Publish all ABAC files (config, migrations, and env variables)
php artisan zennit_abac:publish

# Individual publishing commands
php artisan zennit_abac:publish-config    # Publish configuration file only
php artisan zennit_abac:publish-migration # Publish migration files only
php artisan zennit_abac:publish-env       # Publish environment variables only
```

### Cache Management

```bash
# Warm the entire policy cache
php artisan zennit_abac:cache-warm

# Warm cache for specific resource
php artisan zennit_abac:cache-warm posts

# Invalidate cache
php artisan zennit_abac:cache-invalidate

# Clear cache
php artisan zennit_abac:cache-clear
```

### Environment Setup

```bash
# Add required environment variables to .env file
php artisan zennit_abac:publish-env

# Available environment variables:
ABAC_CACHE_ENABLED=true
ABAC_CACHE_TTL=3600
ABAC_CACHE_WARMING_ENABLED=true
ABAC_CACHE_WARMING_CHUNK_SIZE=100
ABAC_PARALLEL_EVALUATION=false
ABAC_BATCH_SIZE=1000
ABAC_BATCH_CHUNK_SIZE=100
ABAC_STRICT_VALIDATION=true
ABAC_LOGGING_ENABLED=true
ABAC_LOG_CHANNEL=abac
ABAC_DETAILED_LOGGING=false
ABAC_PERFORMANCE_LOGGING=true
ABAC_SLOW_EVALUATION_THRESHOLD=100
ABAC_EVENTS_ENABLED=true
ABAC_ASYNC_EVENTS=false
ABAC_USER_ATTRIBUTE_SUBJECT_TYPE="App\Models\User"
```

### Force Options

All commands support the `--force` option to skip confirmations:

```bash
php artisan zennit_abac:publish --force
php artisan zennit_abac:publish-config --force
php artisan zennit_abac:publish-migration --force
php artisan zennit_abac:publish-env --force
```

### Testing

```bash
# Run tests with coverage report
composer test

# Version management
composer version-patch  # Increment patch version
composer version-minor  # Increment minor version
composer version-major  # Increment major version
```

---

## Configuration

### Environment Variables

```bash
# ABAC Cache Configuration
ZENNIT_ABAC_CACHE_ENABLED=true
ZENNIT_ABAC_CACHE_STORE=${CACHE_STORE}
ZENNIT_ABAC_CACHE_TTL=${SESSION_LIFETIME}
ZENNIT_ABAC_CACHE_PREFIX=zennit_abac_
ZENNIT_ABAC_CACHE_WARMING_ENABLED=true
ZENNIT_ABAC_CACHE_WARMING_SCHEDULE=hourly

# ABAC Validation Configuration
ZENNIT_ABAC_STRICT_VALIDATION=true

# ABAC Logging Configuration
ZENNIT_ABAC_LOGGING_ENABLED=true
ZENNIT_ABAC_LOG_CHANNEL=${LOG_CHANNEL}
ZENNIT_ABAC_DETAILED_LOGGING=false
ZENNIT_ABAC_PERFORMANCE_LOGGING_ENABLED=true
ZENNIT_ABAC_SLOW_EVALUATION_THRESHOLD=100

# ABAC Events Configuration
ZENNIT_ABAC_EVENTS_ENABLED=true

# ABAC Model Configuration
ZENNIT_ABAC_USER_ATTRIBUTE_SUBJECT_TYPE=App\Models\User
```

### Full Configuration Options

```php
return [
    'cache' => [
        'enabled' => env('ZENNIT_ABAC_CACHE_ENABLED', true),
        'store' => env('ZENNIT_ABAC_CACHE_STORE', 'database'),
        'ttl' => env('ZENNIT_ABAC_CACHE_TTL', 3600),
        'prefix' => env('ZENNIT_ABAC_CACHE_PREFIX', 'zennit_abac_'),
        'warming' => [
            'enabled' => env('ZENNIT_ABAC_CACHE_WARMING_ENABLED', true),
            'schedule' => env('ZENNIT_ABAC_CACHE_WARMING_SCHEDULE', 'hourly'),
        ],
    ],
    'evaluation' => [
        'strict_validation' => env('ZENNIT_ABAC_STRICT_VALIDATION', true),
    ],
    'monitoring' => [
        'logging' => [
            'enabled' => env('ZENNIT_ABAC_LOGGING_ENABLED', true),
            'channel' => env('ZENNIT_ABAC_LOG_CHANNEL', 'stderr'),
            'detailed' => env('ZENNIT_ABAC_DETAILED_LOGGING', false),
        ],
        'performance' => [
            'enabled' => env('ZENNIT_ABAC_PERFORMANCE_LOGGING_ENABLED', true),
            'slow_threshold' => env('ZENNIT_ABAC_SLOW_EVALUATION_THRESHOLD', 100),
        ],
        'events' => [
            'enabled' => env('ZENNIT_ABAC_EVENTS_ENABLED', true),
        ],
    ],
    'database' => [
        'user_attribute_subject_type' => env('ZENNIT_ABAC_USER_ATTRIBUTE_SUBJECT_TYPE', 'App\\Models\\User'),
    ],
];
```

## Database Schema

The package creates the following tables:

### Permissions
- `id` - Primary key
- `resource` - Resource identifier
- `operation` - Operation name
- Unique constraint on `[resource, operation]`

### Policies
- `id` - Primary key
- `name` - Policy name
- `permission_id` - Foreign key to permissions table

### Policy Collections
- `id` - Primary key
- `operator` - Logical operator (AND, OR)
- `policy_id` - Foreign key to policies table

### Policy Conditions
- `id` - Primary key
- `operator` - Logical operator
- `policy_collection_id` - Foreign key to policy_collections table

### Policy Condition Attributes
- `id` - Primary key
- `policy_condition_id` - Foreign key to policy_conditions table
- `operator` - Comparison operator
- `attribute_name` - Name of the attribute to compare
- `attribute_value` - Value to compare against

### Resource Attributes
- `id` - Primary key
- `resource` - Resource identifier
- `attribute_name` - Name of the attribute
- `attribute_value` - Value of the attribute
- Index on `[resource, attribute_name]`

### User Attributes
- `id` - Primary key
- `subject_type` - Morphable type (default: App\Models\User)
- `subject_id` - Subject ID
- `attribute_name` - Name of the attribute
- `attribute_value` - Value of the attribute
- Unique constraint on `[subject_type, subject_id, attribute_name]`

## Models

### Permission
```php
use zennit\ABAC\Models\Permission;

$permission = Permission::create([
    'resource' => 'posts',
    'operation' => 'update'
]);

// Relationships
$permission->policies(); // HasMany Policy
```

### Policy
```php
use zennit\ABAC\Models\Policy;

$policy = Policy::create([
    'name' => 'Edit Own Posts',
    'permission_id' => $permissionId
]);

// Relationships
$policy->permission(); // BelongsTo Permission
$policy->collections(); // HasMany PolicyCollection
```

### PolicyCollection
```php
use zennit\ABAC\Models\PolicyCollection;

$collection = PolicyCollection::create([
    'operator' => 'AND',
    'policy_id' => $policyId
]);

// Relationships
$collection->policy(); // BelongsTo Policy
$collection->conditions(); // HasMany PolicyCondition
```

### PolicyCondition
```php
use zennit\ABAC\Models\PolicyCondition;

$condition = PolicyCondition::create([
    'operator' => 'AND',
    'policy_collection_id' => $collectionId
]);

// Relationships
$condition->collection(); // BelongsTo PolicyCollection
$condition->attributes(); // HasMany PolicyConditionAttribute
```

### PolicyConditionAttribute
```php
use zennit\ABAC\Models\PolicyConditionAttribute;

$attribute = PolicyConditionAttribute::create([
    'policy_condition_id' => $conditionId,
    'attribute_name' => 'owner_id',
    'attribute_value' => '$subject.id',
    'operator' => 'EQUALS'
]);

// Relationships
$attribute->condition(); // BelongsTo PolicyCondition
```

### ResourceAttribute
```php
use zennit\ABAC\Models\ResourceAttribute;

$attribute = ResourceAttribute::create([
    'resource' => 'posts',
    'attribute_name' => 'status',
    'attribute_value' => 'published'
]);
```

### UserAttribute
```php
use zennit\ABAC\Models\UserAttribute;

$attribute = UserAttribute::create([
    'subject_type' => 'App\\Models\\User',
    'subject_id' => $userId,
    'attribute_name' => 'role',
    'attribute_value' => 'admin'
]);

// Relationships
$attribute->subject(); // MorphTo
```

---

## Operators

Available operators:

- `EQUALS`
- `NOT_EQUALS`
- `GREATER_THAN`
- `LESS_THAN`
- `GREATER_THAN_EQUALS`
- `LESS_THAN_EQUALS`
- `IN`
- `NOT_IN`
- `CONTAINS`
- `NOT_CONTAINS`
- `STARTS_WITH`
- `NOT_STARTS_WITH`
- `ENDS_WITH`
- `NOT_ENDS_WITH`
- `AND`
- `OR`
- `NOT`

---

## Middleware

Protect your routes with ABAC middleware:

```php
// In RouteServiceProvider
Route::middleware(['zennit.abac.permissions'])
    ->group(function () {
        // Protected routes
    });
```

---

## Events

The following events are dispatched:

- `CacheWarmed` - When cache warming completes with:
    - Policy count
    - Duration
    - Next warming schedule
    - Resource information

---

## Advanced Usage

### Subject Attributes

```php
use zennit\ABAC\Models\UserAttribute;

// Add attributes to a subject
UserAttribute::create([
    'subject_type' => get_class($subject),
    'subject_id' => $subject->id,
    'attribute_name' => 'role',
    'attribute_value' => 'admin'
]);
```

### Resource Attributes

```php
use zennit\ABAC\Models\ResourceAttribute;

// Add attributes to a resource
ResourceAttribute::create([
    'resource' => 'posts',
    'attribute_name' => 'status',
    'attribute_value' => 'published'
]);
```

### Batch Processing

```php
use zennit\ABAC\Jobs\PolicyCacheJob;

// Warm cache for all policies
PolicyCacheJob::dispatch('warm');

// Warm cache for specific resource
PolicyCacheJob::dispatch('warm', 'posts');

// Invalidate cache
PolicyCacheJob::dispatch('invalidate');
```

---

## Caching

### Cache System

The package includes a comprehensive caching system that caches:

- Policy definitions and conditions
- User attributes
- Resource attributes
- Policy evaluation results
- Attribute collections

### Cache Management

```php
use zennit\ABAC\Jobs\PolicyCacheJob;

// Invalidate all caches
PolicyCacheJob::dispatch('invalidate');

// Invalidate specific resource
PolicyCacheJob::dispatch('invalidate', 'posts');

// Warm all caches
PolicyCacheJob::dispatch('warm');

// Warm specific resource
PolicyCacheJob::dispatch('warm', 'posts');
```

### Automatic Cache Invalidation

The cache automatically invalidates when:

- Policies are modified
- User attributes change
- Resource attributes change
- Permissions are updated

### Cache Events

```php
use zennit\ABAC\Events\CacheWarmed;

Event::listen(CacheWarmed::class, function (CacheWarmed $event) {
    Log::info("Cache warmed: {$event->policiesCount} policies");
    Log::info("Next warming: {$event->getNextWarming()}");
});
```

---

## Requirements

- PHP ^8.2
- Laravel ^11.2

---

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

---

## Security

If you discover any security-related issues, please email contact@zennit.dev.

---

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

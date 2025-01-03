<?php

namespace zennit\ABAC\Database\Seeders;

use Illuminate\Database\Seeder;
use zennit\ABAC\Enums\PermissionOperations;
use zennit\ABAC\Models\Permission;
use zennit\ABAC\Models\Policy;

class PolicySeeder extends Seeder
{
    public function run(): void
    {
        $policies = [
            'System Admin Full Access' => ['*', '*'],
            'Organization Admin Access' => ['organizations', '*'],
            'Team Member Task Access' => ['tasks', '*'],
            'Senior Team Member Task Deletion' => ['tasks', PermissionOperations::DELETE],
            'Project View Access' => ['projects', PermissionOperations::SHOW],
        ];

        foreach ($policies as $name => [$resource, $operation]) {
            $permission = Permission::where('resource', $resource)
                ->where('operation', $operation)
                ->first();

            if ($permission) {
                Policy::firstOrCreate([
                    'name' => $name,
                    'permission_id' => $permission->id,
                ]);
            }
        }
    }
}

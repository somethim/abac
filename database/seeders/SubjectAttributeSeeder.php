<?php

namespace zennit\ABAC\Database\Seeders;

use Illuminate\Database\Seeder;
use zennit\ABAC\Models\AbacSubjectAdditionalAttribute;

class SubjectAttributeSeeder extends Seeder
{
    public function run(): void
    {
        $resourcePath = resource_path(config('abac.seeders.subject_attribute_path'));

        if (!file_exists($resourcePath)) {
            $this->command->error("Resource attribute file not found at path: $resourcePath");

            return;
        }

        $resourceAttributes = json_decode(file_get_contents($resourcePath), true);

        if (!is_array($resourceAttributes)) {
            $this->command->error('Invalid JSON structure in resource attribute file.');

            return;
        }

        foreach ($resourceAttributes as $attr) {
            AbacSubjectAdditionalAttribute::create($attr);
        }
    }
}

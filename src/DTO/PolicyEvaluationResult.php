<?php

namespace zennit\ABAC\DTO;

readonly class PolicyEvaluationResult
{
    public function __construct(
        public bool $granted,
        public string $reason,
        public array $context = [],
        public array $matchedPolicies = []
    ) {
    }
}

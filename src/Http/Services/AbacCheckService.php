<?php

namespace zennit\ABAC\Http\Services;

use Throwable;
use zennit\ABAC\Models\AbacCheck;

readonly class AbacCheckService
{
    public function index(int $condition): array
    {
        return AbacCheck::where('chain_id', $condition)->get()->toArray();
    }

    public function store(array $data, int $chainId): array
    {
        return AbacCheck::create([...$data, 'chain_id' => $chainId])->toArray();
    }

    public function show(int $condition): AbacCheck
    {
        return AbacCheck::findOrFail($condition);
    }

    /**
     * @throws Throwable
     */
    public function update(array $data, int $condition): AbacCheck
    {
        $condition = AbacCheck::findOrFail($condition);
        $condition->updateOrFail($data);

        return $condition;
    }

    /**
     * @throws Throwable
     */
    public function destroy(int $condition): void
    {
        AbacCheck::findOrFail($condition)->deleteOrFail();
    }
}

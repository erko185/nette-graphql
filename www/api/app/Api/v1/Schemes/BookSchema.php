<?php

declare(strict_types=1);

namespace App\Api\Schemes;

final class BookSchema
{
    /**
     * @return array<string, array<int|string, array<string, string>|string>|string|false>
     */
    public function schema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'id' => ['type' => 'integer'],
                'title' => ['type' => 'string'],
                'author' => ['type' => 'string'],
                'genre' => ['type' => 'string'],
                'year_publication' => ['type' => 'integer'],
                'updated_at' => ['type' => 'string'],
                'created_at' => ['type' => 'string'],
            ],
            'required' => [
                'id',
            ],
            'additionalProperties' => false,
        ];
    }
}

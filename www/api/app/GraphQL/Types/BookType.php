<?php
declare(strict_types=1);

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\NullableType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class BookType extends ObjectType
{
    /**
     * @var array<string, NonNull|(NullableType&Type)>
     */
    private array $getMainFields = [];

    public function __construct()
    {
        $this->getMainFields = [
            'id' => Type::getNullableType(Type::int()),
            'title' => Type::nonNull(Type::string()),
            'author' => Type::nonNull(Type::string()),
            'year_publication' => Type::nonNull(Type::int()),
            'description' => Type::nonNull(Type::string()),
            'genre' => Type::nonNull(Type::string()),
            'created_at' => Type::getNullableType(Type::string()),
            'updated_at' => Type::getNullableType(Type::string()),
        ];
        $config = [
            'name' => 'Book',
            'fields' => $this->getMainFields,
        ];
        parent::__construct($config);
    }

    /**
     * @return array<string, NonNull|(NullableType&Type)>
     */
    public function getMainFields(): array
    {
        return $this->getMainFields;
    }
}

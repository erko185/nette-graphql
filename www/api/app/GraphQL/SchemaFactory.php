<?php

declare(strict_types=1);

namespace App\GraphQL;

// pokud máte dotazy
use App\GraphQL\Types\BookType;
use App\GraphQL\Types\RootMutationType;
use App\GraphQL\Types\RootQueryType;
use GraphQL\Type\Schema;

final class SchemaFactory
{
    public function __construct(private readonly BookType $bookType)
    {
    }

    public function create(): Schema
    {
        return new Schema([
            'query' => new RootQueryType($this->bookType),
            'mutation' => new RootMutationType($this->bookType),
        ]);
    }
}

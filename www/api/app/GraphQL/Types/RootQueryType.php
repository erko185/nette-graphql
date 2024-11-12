<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\GraphQL\Types\BookType;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Nette\Database\Table\ActiveRow;

final class RootQueryType extends ObjectType
{
    public function __construct(private readonly BookType $bookType)
    {
        $config = [
            'name' => 'Query',
            'fields' => [
                'books' => [
                    'type' => Type::listOf($this->bookType),
                    'resolve' => function ($root, $args, $context) {
                        return array_map(fn($data)=> $data->toArray(), $context['model']->getAllBooks());
                    },
                ],
                'book' => [
                    'type' => $this->bookType,
                    'args' => [
                        'id' => Type::nonNull(Type::int()),
                    ],
                    'resolve' => function ($root, $args, $context) {
                        $book = $context['model']->getBookById($args['id']);
                        if(!$book instanceof ActiveRow){
                            throw new UserError('Book not found');
                        }
                        return $book;
                    },
                ],
            ],
        ];

        parent::__construct($config);
    }
}

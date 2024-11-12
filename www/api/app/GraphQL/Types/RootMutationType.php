<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\GraphQL\Definitions\JsonType;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Nette\Database\Table\ActiveRow;

final class RootMutationType extends ObjectType
{
    public function __construct(private readonly BookType $bookType)
    {
        $config =
            [
                'name' => 'Mutation',
                'fields' => [
                    'createBook' => [
                        'type' => $this->bookType,
                        'args' => $this->bookType->getMainFields(),
                        'resolve' => function ($root, $args, $context) {
                            $book = $context['model']->createBook($args);
                            if (!$book instanceof ActiveRow) {
                                throw new UserError('Book not created');
                            }
                            return $book->toArray();
                        }, ],
                    'updateBook' => [
                        'type' => $this->bookType,
                        'args' => [
                            'id' => Type::nonNull(Type::int()),
                            'input' => Type::nonNull(new JsonType()),
                        ],
                        'resolve' => function ($root, $args, $context) {
                            $book = $context['model']->updateBook($args['id'], $args['input']);
                            if (!$book instanceof ActiveRow) {
                                throw new UserError('Book not updated');
                            }
                            return $book->toArray();
                        },
                    ],
                    'deleteBook' => [
                        'type' => Type::boolean(),
                        'args' => [
                            'id' => Type::nonNull(Type::int()),
                        ],
                        'resolve' => function ($root, $args, $context) {
                            return $context['model']->deleteBook($args['id']);
                        },
                    ],
                ],
            ];
        parent::__construct($config);
    }
}

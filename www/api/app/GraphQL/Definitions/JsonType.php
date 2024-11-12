<?php

namespace App\GraphQL\Definitions;

use Error;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use InvalidArgumentException;

class JsonType extends ScalarType
{
    public string $name = 'Json';

    public function serialize($value): false|string
    {
        if (!is_string($value) && !is_array($value) && !is_object($value)) {
            throw new InvalidArgumentException('Expected value to be string, array or object');
        }

        return json_encode($value);
    }

    public function parseValue($value)
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('Expected string value for JsonType.');
        }

        return json_decode($value, true);
    }

    public function parseLiteral(Node $valueNode, ?array $variables = null)
    {
        if ($valueNode instanceof StringValueNode) {
            return json_decode($valueNode->value, true);
        }

        throw new Error('Expected a string value for JsonType.');
    }
}

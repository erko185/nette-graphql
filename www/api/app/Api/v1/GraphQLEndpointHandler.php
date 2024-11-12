<?php

declare(strict_types=1);

namespace App\Api\v1;

use App\Api\Schemes\BookSchema;
use App\GraphQL\SchemaFactory;
use App\Model\BookModel;
use GraphQL\Error\InvariantViolation;
use GraphQL\GraphQL;
use Nette\Http\Response;
use Nette\Utils\Json;
use Tomaj\NetteApi\Handlers\BaseHandler;
use Tomaj\NetteApi\Output\JsonOutput;
use Tomaj\NetteApi\Params\GetInputParam;
use Tomaj\NetteApi\Params\RawInputParam;
use Tomaj\NetteApi\Response\JsonApiResponse;
use Tomaj\NetteApi\Response\ResponseInterface;

class GraphQLEndpointHandler extends BaseHandler
{
    public function __construct(
        private readonly SchemaFactory $schema,
        private readonly BookModel $bookModel,
        private readonly BookSchema $bookSchema,
    ) {
        parent::__construct();
    }

    public function summary(): string
    {
        return 'Graphql endpoint';
    }

    public function description(): string
    {
        return 'Endpoint for graphql request for working (password for testing: dasfoihwet90hidsg) <br>
<div>
<h3>Create book: </h3>
Required:
<ul> 
Args : title(string), description(string), genre(string), author(string), year_publication(int)
<li>Fields: id(int)</li>
<li>Optional fields: title(string), description(string), genre(string), author(string), year_publication(int), created_at(string), updated_at(string)</li>
</ul>
Example: <br>
{
   "query": "mutation { createBook(title: \"Narnia\", description: \"Pekna rozpravka\", genre: \"akcna\", author: \"Janko Mrkvicka\", year_publication : 2024) { id title author year_publication updated_at } }"
}
</div>


<div>
<h3>Update book:</h3>
Description: Not all fields need to be included in the json file, but only those that can be updated <br>
Required:
<ul>
<li> 
	Args: id(int), input(json)
<li>
<li>Fields: id(int)</li>
<li>Optional fields: title(string), description(string), genre(string), author(string), year_publication(int), created_at(string), updated_at(string)</li>
</ul>
Example: <br>
{
  "query": "mutation { updateBook(id: 2, input: \"{&bsol;&bsol;&bsol;"title&bsol;&bsol;&bsol;": &bsol;&bsol;&bsol;"Update Title&bsol;&bsol;&bsol;"}\") { id title author year_publication updated_at } }"
}

</div>


<div>
<h3>Delete book :</h3>
Required  :
<ul>
<li>Args :  id(int)</li>
</ul>
Example:  <br>
{
  "query": "mutation { deleteBook(id: 3)}"
}
</div>

<div>
<h3>Get book:</h3>
Required :
<ul>
<li>Args :  id(int)</li>
<li>Fields: id(int)</li>
<li>Optional fields: title(string), description(string), genre(string), author(string), year_publication(int), created_at(string), updated_at(string)</li>
</ul>
Example: <br>
{
  "query": "{ book(id: 1) { id }}"
}
</div>

<div>
<h3>Get list books:</h3>
Required:
<ul>
<li>Fields: id(int)</li>
<li>Optional fields: title(string), description(string), genre(string), author(string), year_publication(int), created_at(string), updated_at(string)</li>
</ul>
Example: <br>
{
  "query": "{ books { id }}"
}
</div>
 ';
    }

    public function params(): array
    {
        return [
            new GetInputParam('queryHash'),
            (new RawInputParam('query'))
                ->setRequired()
                ->setExample(
                    '{"query { __schema { types { name } } }"}'
                ),
        ];
    }

    /**
     * @param array<string, mixed> $params
     */
    public function handle(array $params): ResponseInterface
    {
        $query = $params['query'] ?? '';
        $query = json_decode($query, true);
        $source = $query['query'] ?? null;

        try {
            $schema = $this->schema->create();
            $schema->assertValid();
        } catch (InvariantViolation $e) {
            return new JsonApiResponse(Response::S500_InternalServerError, ['status' => 'error', 'message' => $e->getMessage()]);
        }

        $result = GraphQL::executeQuery(
            $this->schema->create(),
            $source,
            contextValue: ['model' => $this->bookModel],
        );

        $data = $result->toArray();
        if (isset($data['errors'])) {
            return new JsonApiResponse(Response::S500_InternalServerError, ['status' => 'error', 'message' => $data['errors'][0]['message']]);
        }

        return new JsonApiResponse(Response::S200_OK, array_merge(['status' => 'success'], $data));
    }

    public function outputs(): array
    {
        return [
            new JsonOutput(
                Response::S200_OK,
                Json::encode([
                    'type' => 'object',
                    'properties' => [
                        'status' => [
                            'type' => 'string',
                            'enum' => ['success']
                        ],
                        'data' => [
                            'type' => 'object',
                            'properties' => [
                                'updateBook' => $this->bookSchema->schema(),
                                'createBook' => $this->bookSchema->schema(),
                                'book' => $this->bookSchema->schema(),
                                'deleteBook' => ['type' => 'boolean'],
                                'books' => [
                                    'type' => 'array',
                                    'items' => $this->bookSchema->schema(),
                                ],
                            ],
                            'additionalProperties' => false,
                        ],
                    ],
                ]),
                'Success request of updateBook'
            ),
            new JsonOutput(
                Response::S500_InternalServerError,
                Json::encode([
                    'type' => 'object',
                    'properties' => [
                        'status' => [
                            'type' => 'string',
                            'enum' => ['error']
                        ],
                        'message' => ['type' => 'string'],
                    ],
                    'required' => [
                        'message',
                        'status',
                    ],
                    'additionalProperties' => false,
                ]),
                'Error request of updateBook'
            ),
        ];
    }
}

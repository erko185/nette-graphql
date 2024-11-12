<?php

declare(strict_types=1);

namespace App\Model;

use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Traversable;

final class BookModel
{
    private Explorer $database;

    protected string $tableName = 'books';

    public function __construct(Explorer $database)
    {
        $this->database = $database;
    }


    /**
     * @return array<string, mixed>
     */
    public function getAllBooks(): array
    {
        return $this->database->table($this->tableName)->fetchAll();
    }

    public function getBookById(int $id): ?ActiveRow
    {
        return $this->database->table($this->tableName)->get($id);
    }

    /**
     * @param array<string, int|string>  $insertData
     * @return array<string, mixed>|bool|int|ActiveRow
     */
    public function createBook(array $insertData)
    {
        $now = date('Y-m-d h:i:s');
        return $this->database->table($this->tableName)->insert(array_merge(['created_at' => $now, 'updated_at' => $now], $insertData));
    }

    /**
     * @param array<string, int|string>  $updateData
     * @return ActiveRow|null
     */
    public function updateBook(int $id, array $updateData): ?ActiveRow
    {

        $now = date('Y-m-d h:i:s');
        $book = $this->getBookById($id);
        if ($book) {
            $book->update(array_merge($book->toArray(), $updateData, ['updated_at' => $now]));
        }

        return $book;
    }

    public function deleteBook(int $id): bool
    {
        $book = $this->getBookById($id);
        if ($book) {
            return (bool)$book->delete();
        }
        return false;
    }
}

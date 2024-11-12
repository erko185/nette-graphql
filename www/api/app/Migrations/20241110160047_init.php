<?php

declare(strict_types=1);

use Phoenix\Migration\AbstractMigration;

final class Init extends AbstractMigration
{
    protected function up(): void
    {
        $this->table('books')
            ->addColumn('title', 'string')
            ->addColumn('author', 'string')
            ->addColumn('year_publication', 'integer')
            ->addColumn('genre', 'string')
            ->addColumn('description', 'text')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->create();
    }

    protected function down(): void
    {
        $this->table('books')->drop();
    }
}

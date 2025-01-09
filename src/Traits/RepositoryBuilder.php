<?php

namespace Endrika73\LaravelRepository\Traits;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;

trait RepositoryBuilder
{
    /**
     * Query builder instance
     *
     * @var Builder $builder
     */
    protected Builder $builder;

    /**
     * Current database connection name used by this repository
     *
     * @var string $connection_name
     */
    protected string $connection_name;

    /**
     * Current connection instance used by this repository
     *
     * @var Connection $connection
     */
    protected Connection $connection;

    /**
     * Current table name used by this repository
     *
     * @var string $table_name
     */
    protected string $table_name;

    /**
     * Builder instance getter
     *
     * @return Builder|null
     */
    public function builder(): ?Builder
    {
        return $this->builder ?? null;
    }

    /**
     * Create query builder
     *
     * @param string|null $table_name
     * @param string|null $connection_name
     * @return static
     */
    public function createBuilder(?string $table_name = null, ?string $connection_name = null): static
    {
        if (!$connection_name && !$this->getConnection()) {
            if (!$this->getConnectionName()) {
                $this->connection($this->getDefaultConnectionName());
            }
            $this->createConnection();
        } else {
            $this->connection($connection_name)->createConnection();
        }

        if ($table_name) $this->table($table_name);
        if (!$this->getTableName()) $this->table($this->getDefaultTableName());

        $this->builder = $this->getConnection()->table($this->getTableName());

        return $this;
    }

    /**
     * Get current connection instance
     *
     * @return Connection|null
     */
    public function getConnection(): ?Connection
    {
        return $this->connection ?? null;
    }

    /**
     * Get current connection name
     *
     * @param mixed|null $fallback
     * @return string|null
     */
    public function getConnectionName(mixed $fallback = null): ?string
    {
        return $this->connection_name ?? $fallback;
    }

    /**
     * Set new connection
     *
     * @param string $connection_name
     * @return static
     */
    public function connection(string $connection_name): static
    {
        $this->connection_name = $connection_name;
        $this->createConnection();

        return $this;
    }

    /**
     * Create new connection instance
     *
     * @return static
     */
    public function createConnection(): static
    {
        $this->connection = DB::connection($this->connection_name);

        return $this;
    }

    /**
     * Get default connection name for repository
     *
     * @return string
     */
    public function getDefaultConnectionName(): string
    {
        return config('database.default');
    }

    /**
     * Table name setter
     *
     * @param string $table_name
     * @return static
     */
    public function table(string $table_name): static
    {
        $this->table_name = $table_name;

        return $this;
    }


    /**
     * Table name getter
     *
     * @param string|null $fallback_result
     * @return string|null
     */
    public function getTableName(?string $fallback_result = null): ?string
    {
        return $this->table_name ?? $fallback_result;
    }

    /**
     * Get default table name for repository
     *
     * @return string|null
     */
    public function getDefaultTableName(): ?string
    {
        // Todo: Implement on how default table name defined
    }
}
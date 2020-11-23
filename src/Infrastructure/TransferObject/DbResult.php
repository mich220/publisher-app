<?php

namespace App\Infrastructure\TransferObject;

class DbResult
{
    private array $data;
    private array $fields;
    /**
     * @var DbResult[]
     */
    private array $relations;

    public function __construct(array $data, array $fields, array $relations = [])
    {
        $this->data = $data;
        $this->relations = $relations;
        $this->validateFields($fields);
    }

    public function first(): array
    {
        $key = array_key_first($this->data);
        return $key !== null ? $this->data[$key] : [];
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getRelation(string $relationName): ?DbResult
    {
        return $this->hasRelation($relationName) ? $this->relations[$relationName] : null;
    }

    public function setRelation(string $key, DbResult $relation): void
    {
        $this->relations[$key] = $relation;
    }

    public function hasRelation(string $relationName)
    {
        return array_key_exists($relationName, $this->relations);
    }

    private function validateFields(array $fields): void
    {
        if (empty($data)) {
            return;
        };

        $data = $this->data;
        $keys = array_keys(array_pop($data));

        if (empty($keys)) {
            throw new \InvalidArgumentException('Data contains empty rows');
        }

        foreach ($fields as $field) {
            if (!in_array($field, $keys)) {
                throw new \InvalidArgumentException(sprintf(
                    'Incompatible columns with specified fields. Current: %s, Given: %s',
                    json_encode($keys),
                    json_encode($fields)
                ));
            }
        }
    }

    public static function createFromArray(array $data, array $fields, $relations = []): self
    {
        return new self($data, $fields, $relations);
    }
}

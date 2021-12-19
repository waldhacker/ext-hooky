<?php

declare(strict_types = 1);

namespace Waldhacker\Hooky\Events;

class RecordCreatedEvent implements \JsonSerializable
{
    public function __construct(public string $table, public int $id, public array $fields)
    {
    }

    public function jsonSerialize(): array
    {
       return [
           'action' => 'create',
           'id' => $this->id,
           'table' => $this->table,
           'fields' => $this->fields
       ];
    }
}

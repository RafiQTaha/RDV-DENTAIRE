<?php

namespace App\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
class EntityType
{
    public string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }
}

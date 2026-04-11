<?php
namespace App\DTOs;

class WarehouseData
{
    public function __construct(
        public string $name,
        public ?string $location,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            name: $request->name,
            location: $request->location,
        );
    }
}

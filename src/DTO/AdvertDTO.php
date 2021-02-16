<?php

declare(strict_types=1);

namespace App\DTO;

final class AdvertDTO
{
    private string $title;

    private string $description;

    private int $price;

    private array $photoUrls;

    public function __construct(string $title, string $description, int $price, array $photoUrls)
    {
        $this->title       = $title;
        $this->description = $description;
        $this->price       = $price;
        $this->photoUrls   = $photoUrls;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getPhotoUrls(): array
    {
        return $this->photoUrls;
    }
}

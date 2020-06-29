<?php

declare(strict_types=1);

namespace App\DTO;

final class AdvertDTO
{
    /** @var string $title */
    private $title;
    /** @var string $description */
    private $description;
    /** @var int $price */
    private $price;
    /** var string[] $photoUrls */
    private $photoUrls;

    /**
     * @param string $title
     * @param string $description
     * @param int $price
     * @param string[] $photoUrls
     */
    public function __construct(string $title, string $description, int $price, array $photoUrls)
    {
        $this->title       = $title;
        $this->description = $description;
        $this->price       = $price;
        $this->photoUrls   = $photoUrls;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return string[]
     */
    public function getPhotoUrls(): array
    {
        return $this->photoUrls;
    }
}

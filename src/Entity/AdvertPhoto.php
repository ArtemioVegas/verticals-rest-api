<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class AdvertPhoto
{
    /**
     * Many photos have one advert. This is the owning side.
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Advert", inversedBy="photos")
     * @ORM\JoinColumn(name="advert_id", referencedColumnName="id")
     */
    private Advert $advert;

    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private string $url;

    public function __construct(Advert $advert, string $url)
    {
        $this->advert = $advert;
        $this->url    = $url;
    }

    public function isUrlEqual(string $url): bool
    {
        return $this->url === $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}

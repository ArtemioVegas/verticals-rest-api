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
     * @var Advert
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Advert", inversedBy="photos")
     * @ORM\JoinColumn(name="advert_id", referencedColumnName="id")
     */
    private $advert;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @param Advert $advert
     * @param string $url
     */
    public function __construct(Advert $advert, string $url)
    {
        $this->advert = $advert;
        $this->url    = $url;
    }

    /**
     * @param string $url
     * @return bool
     */
    public function isUrlEqual(string $url): bool
    {
        return $this->url === $url;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}

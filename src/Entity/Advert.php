<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 */
class Advert
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private string $title;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private string $description;

    /**
     * @ORM\Column(type="integer")
     */
    private int $price;

    /**
     * @ORM\Column(type="datetime", options={"default" : "CURRENT_TIMESTAMP"})
     */
    private DateTime $created;

    /**
     * One advert has many photos. This is the inverse side.
     * @var ArrayCollection|AdvertPhoto[]
     * @ORM\OneToMany(targetEntity="AdvertPhoto", mappedBy="advert", cascade={"all"})
     */
    private ArrayCollection $photos;

    /**
     * @param string   $title
     * @param string   $description
     * @param int      $price
     * @param string[] $urls
     * @throws Exception
     */
    public function __construct(string $title, string $description, int $price, array $urls)
    {
        $this->id          = Uuid::uuid4();
        $this->title       = $title;
        $this->description = $description;
        $this->price       = $price;
        $this->created     = new DateTime;
        $this->photos      = new ArrayCollection();

        foreach ($urls as $url) {
            $this->addPhoto($url);
        }
    }

    /**
     * @return AdvertPhoto[]
     */
    public function getPhotos(): array
    {
        return $this->photos;
    }

    public function addPhoto(string $url): void
    {
        foreach ($this->photos as $photo) {
            if ($photo->isUrlEqual($url)) {
                throw new \DomainException('URL already exists.');
            }
        }
        $this->photos->add(new AdvertPhoto($this, $url));
    }

    public function getId(): string
    {
        return (string)$this->id;
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

    public function getMainPhoto(): AdvertPhoto
    {
        return $this->photos->first();
    }
}

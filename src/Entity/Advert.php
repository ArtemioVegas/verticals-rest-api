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
     * @var UuidInterface
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=200)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="string", length=1000)
     */
    private $description;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", options={"default" : "CURRENT_TIMESTAMP"})
     */
    private $created;

    /**
     * One advert has many photos. This is the inverse side.
     * @var ArrayCollection|AdvertPhoto[]
     * @ORM\OneToMany(targetEntity="AdvertPhoto", mappedBy="advert", cascade={"all"})
     */
    private $photos;

    /**
     * @param string $title
     * @param string $description
     * @param int $price
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

	/**
	 * @param string $url
	 */
    public function addPhoto(string $url): void
    {
        foreach ($this->photos as $photo) {
            if ($photo->isUrlEqual($url)) {
                throw new \DomainException('URL already exists.');
            }
        }
        $this->photos->add(new AdvertPhoto($this, $url));
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return (string)$this->id;
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
     * @return int Возвращает цену.
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return AdvertPhoto
     */
    public function getMainPhoto(): AdvertPhoto
    {
        return $this->photos->first();
    }
}

<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\AdvertDTO;
use App\Entity\Advert;
use App\Repository\AdvertRepository;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Knp\Component\Pager\PaginatorInterface;

final class AdvertService
{
    private AdvertRepository $advertRepository;

    private PaginatorInterface $paginator;

    public function __construct(AdvertRepository $advertRepository, PaginatorInterface $paginator)
    {
        $this->advertRepository = $advertRepository;
        $this->paginator        = $paginator;
    }

    /**
     * Создать объявление
     *
     * @param AdvertDTO $advertDTO
     * @return Advert
     * @throws Exception
     */
    public function addAdvert(AdvertDTO $advertDTO): Advert
    {
        $advert = new Advert($advertDTO->getTitle(), $advertDTO->getDescription(), $advertDTO->getPrice(), $advertDTO->getPhotoUrls());
        $this->advertRepository->save($advert);

        return $advert;
    }

    /**
     * Получить объявление по идентификатору
     *
     * @param string $advertId
     * @return Advert
     * @throws EntityNotFoundException
     */
    public function getAdvert(string $advertId): Advert
    {
        $advert = $this->advertRepository->findById($advertId);

        if (!$advert) {
            throw new EntityNotFoundException('Advert with id '.$advertId.' does not exist!');
        }

        return $advert;
    }

    /**
     * Получить список объявлений
     *
     * @param int $page
     * @param int $limit
     * @return Advert[]|array
     */
    public function getAdvertsByLimit(int $page, int $limit): array
    {
        $qb = $this->advertRepository->createFindAllAdvertsQuery();

        return (array)$this->paginator->paginate(
            $qb->getQuery(),
            $page,
            $limit
        )->getItems();
    }
}
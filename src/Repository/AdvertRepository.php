<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Advert;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class AdvertRepository
{
    private EntityManagerInterface $em;

    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em   = $em;
        $this->repo = $em->getRepository(Advert::class);
    }

    public function save(Advert $advert): void
    {
        $this->em->persist($advert);
        $this->em->flush();
    }

    public function findById(string $advertId): ?Advert
    {
        return $this->repo->find($advertId);
    }

    public function createFindAllAdvertsQuery(): QueryBuilder
    {
        return $this->repo
            ->createQueryBuilder('adv')
            ->addSelect('adv.price as HIDDEN price')
            ->addSelect('adv.created as HIDDEN created')
        ;
    }
}

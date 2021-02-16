<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\AdvertDTO;
use App\Service\AdvertService;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class AdvertController extends AbstractFOSRestController
{
    public const EXTRA_FIELDS_PARAM_NAME      = 'fields';
	public const EXTRA_FIELD_NAME_DESCRIPTION = 'description';
	public const EXTRA_FIELD_NAME_PHOTO_URLS  = 'photoUrls';

    /** Сервис для работы с объявлениями */
    private AdvertService $advertService;

    /**
     * @param AdvertService $advertService
     */
    public function __construct(AdvertService $advertService)
    {
        $this->advertService = $advertService;
    }

    /**
     * Экшн создания объявления.
     *
     * @Rest\Post("/advert")
     * @ParamConverter("advertDTO", converter="fos_rest.request_body")
     * @param AdvertDTO $advertDTO
     * @param ConstraintViolationListInterface $validationErrors
     * @return View
     * @throws Exception
     */
    public function postAdvert(AdvertDTO $advertDTO, ConstraintViolationListInterface $validationErrors): View
    {
        if (count($validationErrors) > 0) {
            return View::create($validationErrors, Response::HTTP_BAD_REQUEST);
        }
        $advert = $this->advertService->addAdvert($advertDTO);

        return View::create($advert->getId(), Response::HTTP_CREATED);
    }

    /**
     * Получить объявление по идентификатору
     *
     * @Rest\Get("/advert/{advertId}")
     * @Rest\QueryParam(map=true, name="fields", requirements="[a-z]+", default={}, description="List of extra fields.")
     * @param string $advertId
     * @param ParamFetcher $paramFetcher
     * @return View
     * @throws EntityNotFoundException
     */
    public function getAdvert(string $advertId, ParamFetcher $paramFetcher): View
    {
       $advert = $this->advertService->getAdvert($advertId);
       $fields = $paramFetcher->get('fields');

       $v = View::create($advert, Response::HTTP_OK);
       $v->setContext((new Context)->setAttribute(self::EXTRA_FIELDS_PARAM_NAME, $fields));

       return $v;
    }

    /**
     * Экшн получения списка объявлений.
     *
     * @Rest\Get("/advert")
     * @param Request $request
     * @return View
     */
    public function getAdverts(Request $request): View
    {
        $adverts = $this->advertService->getAdvertsByLimit($request->query->getInt('page', 1), $request->query->getInt('limit', 10));

        return View::create($adverts, Response::HTTP_OK);
    }
}

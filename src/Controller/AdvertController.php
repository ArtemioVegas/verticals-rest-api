<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\AdvertDTO;
use App\Service\AdvertService;
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
    const EXTRA_FIELDS_PARAM_NAME      = 'fields';
    const EXTRA_FIELD_NAME_DESCRIPTION = 'description';
    const EXTRA_FIELD_NAME_PHOTO_URLS  = 'photoUrls';

    /** @var AdvertService */
    private $advertService;

    /**
     * @param AdvertService $advertService
     */
    public function __construct(AdvertService $advertService)
    {
        $this->advertService = $advertService;
    }

    /**
     * Creates an Advert resource
     * @Rest\Post("/advert")
     * @ParamConverter("advertDTO", converter="fos_rest.request_body")
     * @param AdvertDTO $advertDTO
     * @param ConstraintViolationListInterface $validationErrors
     * @return View
     * @throws \Exception
     */
    public function postAdvert(AdvertDTO $advertDTO, ConstraintViolationListInterface $validationErrors): View
    {
        if (\count($validationErrors) > 0) {
            return View::create($validationErrors, Response::HTTP_BAD_REQUEST);
        }
        $advert = $this->advertService->addAdvert($advertDTO);

        // In case our POST was a success we need to return a 201 HTTP CREATED response with the created object
        return View::create($advert->getId(), Response::HTTP_CREATED);
    }

    /**
     * Retrieves an Advert resource
     * @Rest\Get("/advert/{advertId}")
     * @Rest\QueryParam(map=true, name="fields", requirements="[a-z]+", default={}, description="List of extra fields.")
     * @param string $advertId
     * @param ParamFetcher $paramFetcher
     * @return View
     * @throws \Doctrine\ORM\EntityNotFoundException
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
     * Retrieves a collection of Advert resource
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

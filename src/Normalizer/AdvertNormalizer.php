<?php

namespace App\Normalizer;

use App\Controller\AdvertController;
use App\Entity\Advert;
use App\Entity\AdvertPhoto;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AdvertNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        /** @var Advert $object */
        $result = [
            'title'     => $object->getTitle(),
            'price'     => $object->getPrice(),
            'mainPhoto' => $object->getMainPhoto()->getUrl(),
        ];

        if (isset($context[AdvertController::EXTRA_FIELDS_PARAM_NAME]) && is_array($context[AdvertController::EXTRA_FIELDS_PARAM_NAME])) {
            foreach ($context[AdvertController::EXTRA_FIELDS_PARAM_NAME] as $field) {
                switch ($field) {
                    case AdvertController::EXTRA_FIELD_NAME_DESCRIPTION:
                        $result[AdvertController::EXTRA_FIELD_NAME_DESCRIPTION] = $object->getDescription();
                        break;
                    case AdvertController::EXTRA_FIELD_NAME_PHOTO_URLS:
                        $result[AdvertController::EXTRA_FIELD_NAME_PHOTO_URLS] = array_map(
                            function (AdvertPhoto $photo) {
                                return $photo->getUrl();
                            },
                            $object->getPhotos()
                        );
                        break;
                }
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Advert;
    }
}

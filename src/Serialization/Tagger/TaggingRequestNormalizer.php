<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 15.05.2017
 * Time: 20:37
 */

namespace Szyku\NLTK\Serialization\Tagger;


use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Szyku\NLTK\Request\Tagger\TaggingRequest;

final class TaggingRequestNormalizer implements NormalizerInterface
{

    /**
     * @inheritdoc
     */
    public function normalize($object, $format = null, array $context = array())
    {
        /** @var $object TaggingRequest */
        return $object->sentences();
    }

    /**
     * @inheritdoc
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof TaggingRequest && 'json' === $format;
    }
}

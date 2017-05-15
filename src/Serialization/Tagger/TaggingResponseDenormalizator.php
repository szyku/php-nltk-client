<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 15.05.2017
 * Time: 22:40
 */

namespace Szyku\NLTK\Serialization\Tagger;


use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Szyku\NLTK\Assertion\Assertion;
use Szyku\NLTK\Exception\UnexpectedResponseFormatException;
use Szyku\NLTK\Response\Tagger\PartOfSpeech;

final class TaggingResponseDenormalizator implements DenormalizerInterface
{

    /**
     * @inheritdoc
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        if (!isset($data['input'], $data['results'], $data['time'])) {
            throw new UnexpectedResponseFormatException('The response\'s root misses some fields.');
        }

        Assertion::allString($data['input'], 'Property `input` has unexpected data.');
        Assertion::float($data['time'], 'Property `time` should be of type float.');

        $elements = [];
        foreach ($data['results'] as $result) {
            $elements[] = $this->hydrateElement($result);
        }

        return new TaggingResponse($data['input'], $elements, $data['time']);
    }

    /**
     * @inheritdoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return TaggingResponse::class === $type && 'json' === $format;

    }

    private function hydrateElement(array $result)
    {
        if (!isset($result['item'], $result['partOfSpeech'])) {
            throw new UnexpectedResponseFormatException('The response\'s sentence item misses some fields.');
        }

        $pos = PartOfSpeech;

        return new Part($result['item'], $result['partOfSpeech']);
    }
}

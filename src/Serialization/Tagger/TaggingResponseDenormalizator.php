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
use Szyku\NLTK\Response\Tagger\ExtendedPartOfSpeech;
use Szyku\NLTK\Response\Tagger\Part;
use Szyku\NLTK\Response\Tagger\TaggingResponse;

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

        $collection = [];
        foreach ($data['results'] as $resultSet) {
            $sentenceElements = [];
            foreach ($resultSet as $result) {
                $sentenceElements[] = $this->hydrateElement($result);
            }
            $collection[] = $sentenceElements;
        }

        return new TaggingResponse($data['input'], $collection, $data['time']);
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

        $pos = ExtendedPartOfSpeech::createFromKey($result['partOfSpeech']);

        return new Part($result['item'], $pos);
    }
}

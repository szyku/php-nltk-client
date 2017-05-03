<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 03.05.2017
 * Time: 15:51
 */

namespace Szyku\NLTK\Serialization\Dictionary;


use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Szyku\NLTK\Common\PartOfSpeech;
use Szyku\NLTK\Exception\UnexpectedResponseFormatException;
use Szyku\NLTK\Response\Dictionary\Definition;
use Szyku\NLTK\Response\Dictionary\WordLookupResponse;

class WordLookupResponseDenormalizer implements DenormalizerInterface
{

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        if (!isset($data['results'], $data['time'], $data['word'])) {
            throw new UnexpectedResponseFormatException("The response's root misses some fields.");
        }

        $definitions = $this->processDefinitions($data);
        $queriedPhrase = $data['word'];
        $time = $data['time'];

        return new WordLookupResponse($queriedPhrase, $definitions, $time);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return WordLookupResponse::class === $type && 'json' === $format;
    }

    /**
     * @param $data
     * @return array
     */
    private function processDefinitions($data)
    {
        $definitions = [];
        foreach ($data['results'] as $result) {
            if (!isset($result['definition'], $result['word'], $result['partOfSpeech'])) {
                throw new UnexpectedResponseFormatException("A definition entry does not have all the required fields.");
            }

            if (!PartOfSpeech::has($result['partOfSpeech'])) {
                throw new UnexpectedValueException('Definition\'s partOfSpeech attribute has an unrecognized value.');
            }

            $definitions[] = new Definition(
                $result['definition'],
                $result['word'],
                PartOfSpeech::get($result['partOfSpeech'])
            );
        }

        return $definitions;
    }
}

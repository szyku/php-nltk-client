<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 03.05.2017
 * Time: 12:27
 */

namespace Szyku\NLTK\Serialization;


use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Szyku\NLTK\Exception\UnexpectedResponseFormatException;
use Szyku\NLTK\Serialization\Dictionary\WordLookupResponseDenormalizer;
use Szyku\NLTK\Serialization\Lemma\LemmatizationRequestNormalizer;
use Szyku\NLTK\Serialization\Lemma\LemmatizationResponseDenormalizator;

class JsonSerializer
{
    const JSON = 'json';
    /** @var SerializerInterface */
    protected $serializer;

    /**
     * JsonSerializer constructor.
     * @param SerializerInterface $serialized
     */
    public function __construct()
    {
        $this->serializer = new Serializer(
            [
                new LemmatizationRequestNormalizer(),
                new LemmatizationResponseDenormalizator(),
                new WordLookupResponseDenormalizer()
            ],
            [new JsonEncoder()]
        );
    }

    /**
     * Serialized objects to JSON string.
     * @param $object mixed Object to serialize.
     * @return string JSON representation of the $object.
     */
    public function serialize($object)
    {
        return $this->serializer->serialize($object, self::JSON);
    }

    /**
     * Deserializes JSON string to object of class $class.
     * @param $data string JSON data string.
     * @param $class string Fully qualified class name.
     * @return object Object of class $class.
     * @throws UnexpectedResponseFormatException When the $data is incorrect and the serialization cannot continue.
     */
    public function deserialize($data, $class)
    {
        return $this->serializer->deserialize(
            $data,
            $class,
            self::JSON
        );
    }
}

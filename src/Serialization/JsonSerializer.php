<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 03.05.2017
 * Time: 12:27
 */

namespace Szyku\NLTK\Serialization;


use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
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
            [new LemmatizationRequestNormalizer(), new LemmatizationResponseDenormalizator()],
            [new JsonEncoder()]
        );
    }

    public function serialize($object)
    {
        return $this->serializer->serialize($object, self::JSON);
    }

    public function deserialize($data, $class)
    {
        return $this->serializer->deserialize(
            $data,
            $class,
            self::JSON
        );
    }
}

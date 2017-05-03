<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 03.05.2017
 * Time: 20:41
 */

namespace Tests\Szyku\NLTK\Serialization\Lemma;

use Tests\Szyku\NLTK\Serialization\Lemma\LemmatizationResponseDenormalizatorTest as BaseTest;

class LemmatizationResponseDenormalizatorExceptionsTest extends BaseTest
{
    /**
     * @expectedException Szyku\NLTK\Exception\UnexpectedResponseFormatException
     * @expectedExceptionMessage The response's root misses some fields.
     */
    public function testDenormalizationOnFaultyDataInRoot()
    {
        self::$denormalizer->denormalize(['root' => []], 'json');
    }

    /**
     * @expectedException Szyku\NLTK\Exception\UnexpectedResponseFormatException
     * @expectedExceptionMessage A query entry misses some fields.
     */
    public function testDenormalizationOnFaultyDataInQueryPart()
    {
        $faultyData = $this->getNormalizedData();
        unset($faultyData['query'][0]['partOfSpeech']);

        self::$denormalizer->denormalize($faultyData, 'json');
    }

    /**
     * @expectedException Szyku\NLTK\Exception\UnexpectedResponseFormatException
     * @expectedExceptionMessage Lemma response item misses some fields.
     */
    public function testDenormalizationOnFaultyDataInResult()
    {
        $faultyData = $this->getNormalizedData();
        unset($faultyData['results'][0]['word']);

        self::$denormalizer->denormalize($faultyData, 'json');
    }

    /**
     * @expectedException Szyku\NLTK\Exception\UnexpectedResponseFormatException
     * @expectedExceptionMessage Lemma item misses some fields.
     */
    public function testDenormalizationOnFaultyDataInLemma()
    {
        $faultyData = $this->getNormalizedData();
        unset($faultyData['results'][0]['result']['lemma']);

        self::$denormalizer->denormalize($faultyData, 'json');
    }
}

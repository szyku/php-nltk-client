<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 03.05.2017
 * Time: 19:49
 */

namespace Tests\Szyku\NLTK\Serialization\Dictionary;

use Szyku\NLTK\Common\PartOfSpeech;
use Szyku\NLTK\Response\Dictionary\Definition;
use Szyku\NLTK\Response\Dictionary\WordLookupResponse;
use Szyku\NLTK\Serialization\Dictionary\WordLookupResponseDenormalizer;

class WordLookupResponseDenormalizerTest extends \PHPUnit_Framework_TestCase
{
    /** @var WordLookupResponseDenormalizer */
    private static $denormalizer;

    public static function setUpBeforeClass()
    {
        self::$denormalizer = new WordLookupResponseDenormalizer();
    }

    public function testDenormalization()
    {
        $actual = self::$denormalizer->denormalize($this->getNormalizedData(), WordLookupResponse::class);

        $this->assertInstanceOf(WordLookupResponse::class, $actual);
        foreach ($actual->results() as $result) {
            $this->assertInstanceOf(Definition::class, $result);
            $this->assertInternalType('string', $result->phrase());
            $this->assertInternalType('string', $result->definition());
            $this->assertInstanceOf(PartOfSpeech::class, $result->partOfSpeech());
            $this->assertTrue($result->partOfSpeech()->is(PartOfSpeech::NOUN()));
        }

        $this->assertSame('word', $actual->queriedPhrase());
        $this->assertNotEmpty($actual->lookupTime());
    }


    private function getNormalizedData()
    {
        return [
            'results' => [
                ['word' => 'word1', 'partOfSpeech' => 'noun', 'definition' => 'A word'],
                ['word' => 'word2', 'partOfSpeech' => 'noun', 'definition' => 'A word 2'],
            ],
            'word'    => 'word',
            'time'    => 0.1
        ];
    }

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
     * @expectedExceptionMessage A definition entry does not have all the required fields.
     */
    public function testDenormalizationOnFaultyDataInDefinition()
    {
        $faultyData = $this->getNormalizedData();
        unset ($faultyData['results'][0]['word']);

        self::$denormalizer->denormalize($faultyData, 'json');
    }
}

<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 16.05.2017
 * Time: 18:56
 */

namespace Tests\Szyku\NLTK\Serialization\Tagger;

use Szyku\NLTK\Exception\NltkClientAssertionException;
use Szyku\NLTK\Exception\UnexpectedResponseFormatException;
use Szyku\NLTK\Response\Tagger\TaggingResponse;
use Szyku\NLTK\Serialization\Tagger\TaggingResponseDenormalizator;

class TaggingResponseDenormalizatorTest extends \PHPUnit_Framework_TestCase
{

    /** @var TaggingResponseDenormalizator */
    protected static $denormalizer;

    public static function setUpBeforeClass()
    {
        self::$denormalizer = new TaggingResponseDenormalizator();
    }


    /**
     * @dataProvider decodedDataProvider
     */
    public function testDenormalizationExceptions($data, $expectedException, $expectedExceptionMsg)
    {
        $exception = null;
        try {
            self::$denormalizer->denormalize($data, TaggingResponse::class, 'json');
        } catch (\Exception $e) {
            $exception = $e;
        }

        $this->assertNotNull($exception);
        $this->assertSame($exception->getMessage(), $expectedExceptionMsg);
        $this->assertInstanceOf($expectedException, $exception);
    }

    public function decodedDataProvider()
    {
        return [
            [
                ['input' => ['dddd']],
                UnexpectedResponseFormatException::class,
                'The response\'s root misses some fields.',
            ],
            [
                ['input' => ['dddd', 31231], 'results' => [[['item' => 'ddd', 'partOfSpeech' => 'RB']]], 'time' => 0.0],
                NltkClientAssertionException::class,
                'Property `input` has unexpected data.',
            ],
            [
                ['input' => ['dddd'], 'results' => [[['sdfa' => 'ddd', 'partOfSpeech' => 'RB']]], 'time' => 0.0],
                UnexpectedResponseFormatException::class,
                'The response\'s sentence item misses some fields.',
            ],
            [
                ['input' => ['dddd'], 'results' => [[['item' => 'ddd', 'partOfSpeech' => 'RB']]], 'time' => 'fasdf'],
                NltkClientAssertionException::class,
                'Property `time` should be of type float.',
            ]
        ];
    }

    private function normalizedEntry()
    {
        return ['input' => ['dddd'], 'results' => [[['item' => 'ddd', 'partOfSpeech' => 'RB']]], 'time' => 0.123];

    }

    public function testDenormalizing()
    {
        $data = $this->normalizedEntry();
        $this->assertTrue(self::$denormalizer->supportsDenormalization($data, TaggingResponse::class, 'json'));

        $object = self::$denormalizer->denormalize($data, TaggingResponse::class, 'json');
        $this->assertInstanceOf(TaggingResponse::class, $object, 'json');
    }
}

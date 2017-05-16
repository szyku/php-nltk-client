<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 16.05.2017
 * Time: 18:31
 */

namespace Tests\Szyku\NLTK\Response\Tagger;

use Szyku\NLTK\Exception\NltkClientAssertionException;
use Szyku\NLTK\Response\Tagger\ExtendedPartOfSpeech;
use Szyku\NLTK\Response\Tagger\Part;
use Szyku\NLTK\Response\Tagger\TaggingResponse;

class TaggingResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider exceptionDataProvider
     */
    public function testConstructorExceptions($input, $parts, $time, $expectedExceptionMsg)
    {
        $exception = null;
        try {
            new TaggingResponse($input, $parts, $time);
        } catch (\Exception $e) {
            $exception = $e;
        }

        $this->assertInstanceOf(NltkClientAssertionException::class, $exception);
        $this->assertSame($expectedExceptionMsg, $exception->getMessage());
    }

    public function exceptionDataProvider()
    {
        return [
            [
                [1231231],
                [[new Part('', ExtendedPartOfSpeech::W_ADV())]],
                0,
                'Input must be an array of strings.'
            ],
            [
                ["Adsad"],
                [[new Part('', ExtendedPartOfSpeech::W_ADV()), 3]],
                0,
                'The parts array must contain only objects of class ' . Part::class . '.',
            ],
            [
                ["sadas"],
                [[new Part('', ExtendedPartOfSpeech::W_ADV())]],
                'dddd',
                'Time must be float type',
            ],
        ];
    }


}

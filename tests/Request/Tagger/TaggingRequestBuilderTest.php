<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 15.05.2017
 * Time: 20:54
 */

namespace Tests\Szyku\NLTK\Request\Tagger;

use Szyku\NLTK\Exception\NltkClientAssertionException;
use Szyku\NLTK\Request\Tagger\TaggingRequest;
use Szyku\NLTK\Request\Tagger\TaggingRequestBuilder;

class TaggingRequestBuilderTest extends \PHPUnit_Framework_TestCase
{

    public function testBuilding()
    {
        $builder = new TaggingRequestBuilder();

        $request = $builder
            ->add("This is a sentence.")
            ->addMany(['This is a sentence 2.', 'This is a sentence 3.'])
            ->build();

        $this->assertInstanceOf(TaggingRequest::class, $request);
        $sentences = $request->sentences();
        $this->assertSame("This is a sentence.", $sentences[0]);
        $this->assertSame("This is a sentence 2.", $sentences[1]);
        $this->assertSame("This is a sentence 3.", $sentences[2]);
    }


    /**
     * @dataProvider exceptionTestProvider
     */
    public function testInvalidDataResponse($method, $input, $exceptionMsg)
    {
        $builder = new TaggingRequestBuilder();
        $actual = null;
        try {
            $builder->{$method}($input);
        } catch (\Exception $e) {
            $actual = $e;
        }

        $this->assertNotNull($actual, 'An exception should have been thrown.');
        $this->assertInstanceOf(NltkClientAssertionException::class, $actual);
        $this->assertSame($exceptionMsg, $actual->getMessage());
    }

    public function exceptionTestProvider()
    {
        return [
            ['add', 123,'Sentence must a string.',],
            ['add', '','Sentence cannot be empty.',],
            ['addMany', ['123', '3123 ', 423], 'Provided sentences must be of string type.'],
            ['addMany', ['123', '', '3123'], 'Cannot provide empty string as sentence.'],
        ];
    }
}

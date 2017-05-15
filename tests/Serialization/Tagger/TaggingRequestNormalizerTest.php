<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 15.05.2017
 * Time: 22:27
 */

namespace Tests\Szyku\NLTK\Serialization\Tagger;


use Szyku\NLTK\Request\Tagger\TaggingRequest;
use Szyku\NLTK\Serialization\Tagger\TaggingRequestNormalizer;

class TaggingRequestNormalizerTest extends \PHPUnit_Framework_TestCase
{

    public function testNormalization()
    {
        $expected = ['dddd', '123123', '3123'];
        $request = new TaggingRequest($expected);
        $normalizer = new TaggingRequestNormalizer();

        $this->assertTrue($normalizer->supportsNormalization($request, 'json'));
        $this->assertFalse($normalizer->supportsNormalization($request, 'xml'));

        $this->assertEquals($expected, $request->sentences());
    }
}

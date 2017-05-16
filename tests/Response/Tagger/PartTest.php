<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 16.05.2017
 * Time: 18:24
 */

namespace Tests\Szyku\NLTK\Response\Tagger;

use Szyku\NLTK\Response\Tagger\ExtendedPartOfSpeech;
use Szyku\NLTK\Response\Tagger\Part;

class PartTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedExceptionMessage Item must be a string.
     * @expectedException Szyku\NLTK\Exception\NltkClientAssertionException
     */
    public function testConstructorExceptions()
    {
        new Part(2312, ExtendedPartOfSpeech::W_ADV());
    }
}

<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 15.05.2017
 * Time: 22:14
 */

namespace Tests\Szyku\NLTK\Request\Tagger;

use Szyku\NLTK\Request\Tagger\TaggingRequest;

class TaggingRequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Szyku\NLTK\Exception\NltkClientAssertionException
     */
    public function testExceptions()
    {
        new TaggingRequest(['123123', 123]);
    }
}

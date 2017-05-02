<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 19:55
 */

namespace Tests\Szyku\NLTK\Response;

use Szyku\NLTK\Request\Lemma\LemmaPosFilter;
use Szyku\NLTK\Request\Lemma\WordLemmatization;
use Szyku\NLTK\Response\Lemma\LemmatizationResponse;

class LemmatizationLookupResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException Szyku\NLTK\Exception\NltkClientAssertionException
     */
    public function testConstructingWithWrongQueryElements()
    {
        new LemmatizationResponse(['not good param'], []);
    }

    /**
     * @expectedException Szyku\NLTK\Exception\NltkClientAssertionException
     */
    public function testConstructingWithWrongResultElements()
    {
        new LemmatizationResponse(
            [new WordLemmatization("some str", LemmaPosFilter::NOUN())],
            ["not good result"]
        );
    }

}

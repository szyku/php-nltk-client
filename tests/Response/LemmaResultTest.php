<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 19:28
 */

namespace Tests\Szyku\NLTK\Response;

use Szyku\NLTK\Common\PartOfSpeech;
use Szyku\NLTK\Response\Lemma\Lemma;
use Szyku\NLTK\Response\Lemma\LemmaResult;

class LemmaResultTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException Szyku\NLTK\Exception\NltkClientAssertionException
     */
    public function testConstructingException()
    {
        $incorrectLemmaArray = [
            new Lemma("word", PartOfSpeech::NOUN()),
            'not lemma',
            new Lemma("word2", PartOfSpeech::NOUN()),
        ];

        new LemmaResult("some word", $incorrectLemmaArray);
    }
}

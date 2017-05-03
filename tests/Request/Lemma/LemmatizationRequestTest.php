<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 22:28
 */

namespace Tests\Szyku\NLTK\Request\Lemma;

use Szyku\NLTK\Request\Lemma\LemmaPosFilter;
use Szyku\NLTK\Request\Lemma\LemmatizationRequest;
use Szyku\NLTK\Request\Lemma\WordLemmatization;

class LemmatizationRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testIteration()
    {
        $items = [
            new WordLemmatization("dog", LemmaPosFilter::NOUN()),
            new WordLemmatization("cat", LemmaPosFilter::NOUN())
        ];

        $req = new LemmatizationRequest($items);

        $this->assertTrue($req instanceof \Traversable, 'Cannot iterate LemmatizationRequest.');
    }

    /**
     * @expectedException Szyku\NLTK\Exception\NltkClientAssertionException
     */
    public function testIfAcceptsOnlyWordLemmatizationObjects()
    {
        $items = [
            new WordLemmatization("dog", LemmaPosFilter::NOUN()),
            'I shouldn\'t be here'
        ];

        new LemmatizationRequest($items);
    }


}

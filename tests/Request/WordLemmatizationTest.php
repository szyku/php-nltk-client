<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 20:04
 */

namespace Tests\Szyku\NLTK\Request;

use Szyku\NLTK\Request\Lemma\LemmaPosFilter as POS;
use Szyku\NLTK\Request\Lemma\WordLemmatization;

class WordLemmatizationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Szyku\NLTK\Exception\NltkClientAssertionException
     */
    public function testConstructingException()
    {
        new WordLemmatization('', POS::NOUN());
    }
}

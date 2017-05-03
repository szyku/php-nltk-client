<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 03.05.2017
 * Time: 19:33
 */

namespace Tests\Szyku\NLTK\Serialization\Lemma;

use Szyku\NLTK\Request\Lemma\LemmaPosFilter;
use Szyku\NLTK\Request\Lemma\LemmatizationRequest;
use Szyku\NLTK\Request\Lemma\WordLemmatization;
use Szyku\NLTK\Serialization\Lemma\LemmatizationRequestNormalizer;

class LemmatizationRequestNormalizerTest extends \PHPUnit_Framework_TestCase
{

    public function testNormalization()
    {
        $normalizer = new LemmatizationRequestNormalizer();

        $req = new LemmatizationRequest(
            [
                new WordLemmatization("word1", LemmaPosFilter::NOUN()),
                new WordLemmatization("word2", LemmaPosFilter::VERB())
            ]
        );

        $this->assertTrue($normalizer->supportsNormalization($req, 'json'));
        $actual = $normalizer->normalize($req);

        $this->assertEquals($this->getExpectedNormalizedArray(), $actual);

    }


    private function getExpectedNormalizedArray()
    {
        $word1 = new \stdClass();
        $word1->word = 'word1';
        $word1->partOfSpeech = 'noun';

        $word2 = new \stdClass();
        $word2->word = 'word2';
        $word2->partOfSpeech = 'verb';

        return [$word1, $word2];
    }

    public function testEmptyLemmatizationRequestNormalization()
    {
        $normalizer = new LemmatizationRequestNormalizer();
        $req = new LemmatizationRequest([]);

        $actual = $normalizer->normalize($req, 'json');

        $this->assertEquals([], $actual);
    }
}

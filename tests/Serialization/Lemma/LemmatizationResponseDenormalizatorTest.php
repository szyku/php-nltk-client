<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 03.05.2017
 * Time: 20:01
 */

namespace Tests\Szyku\NLTK\Serialization\Lemma;

use Szyku\NLTK\Common\PartOfSpeech;
use Szyku\NLTK\Request\Lemma\LemmaPosFilter;
use Szyku\NLTK\Request\Lemma\WordLemmatization;
use Szyku\NLTK\Response\Lemma\LemmaResult;
use Szyku\NLTK\Response\Lemma\LemmatizationResponse;
use Szyku\NLTK\Serialization\Lemma\LemmatizationResponseDenormalizator;

class LemmatizationResponseDenormalizatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var LemmatizationResponseDenormalizator */
    protected static $denormalizer;

    public static function setUpBeforeClass()
    {
        self::$denormalizer = new LemmatizationResponseDenormalizator();
    }

    public function testDenormalization()
    {
        $this->assertTrue(self::$denormalizer->supportsDenormalization(
            $this->getNormalizedData(),
            LemmatizationResponse::class,
            'json')
        );

        $actual = self::$denormalizer->denormalize($this->getNormalizedData(), LemmatizationResponse::class);

        $this->assertInstanceOf(LemmatizationResponse::class, $actual);

        $this->checkQueryPart($actual);
        $this->checkResultPart($actual);

        $this->assertGreaterThan(0, $actual->lookupTime());
    }

    protected function getNormalizedData()
    {
        return [
            'query'   => [
                0 => [
                    'partOfSpeech' => 'noun',
                    'word'         => 'word'
                ],
                1 => [
                    'partOfSpeech' => 'all',
                    'word'         => 'all'
                ]
            ],
            'results' => [
                0 => [
                    "word"   => 'word',
                    "result" => [
                        "lemma"        => "word lemma",
                        "partOfSpeech" => "noun",
                    ]
                ],
                1 => [
                    "word"   => 'all',
                    "result" => [
                        0 => [
                            "lemma"        => "noun lemma",
                            "partOfSpeech" => "noun",
                        ],
                        1 => [
                            "lemma"        => "verb lemma",
                            "partOfSpeech" => "verb",
                        ],
                        2 => [
                            "lemma"        => "adjective lemma",
                            "partOfSpeech" => "adjective",
                        ],
                        3 => [
                            "lemma"        => "adverb lemma",
                            "partOfSpeech" => "adverb",
                        ]
                    ]
                ]
            ],
            'time'    => 0.1
        ];
    }

    /**
     * @param $actual
     */
    private function checkQueryPart($actual)
    {
        $queries = $actual->query();
        foreach ($queries as $query) {
            $this->assertInstanceOf(WordLemmatization::class, $query);
        }

        $this->assertInstanceOf(LemmaPosFilter::class, $queries[0]->partOfSpeech());
        $this->assertInstanceOf(LemmaPosFilter::class, $queries[1]->partOfSpeech());
        $this->assertTrue($queries[0]->partOfSpeech()->is(LemmaPosFilter::NOUN()));
        $this->assertTrue($queries[1]->partOfSpeech()->is(LemmaPosFilter::ALL()));
        $this->assertSame('word', $queries[0]->word());
        $this->assertSame('all', $queries[1]->word());
    }

    /**
     * @param $actual
     */
    private function checkResultPart($actual)
    {
        $results = $actual->results();
        foreach ($results as $result) {
            $this->assertInstanceOf(LemmaResult::class, $result);
        }

        $singleResult = $results[0];
        $allResult = $results[1];

        $this->assertSame('word', $singleResult->lemmatizedPhrase());
        $lemmasFromSingle = $singleResult->lemmas();
        $this->assertCount(1, $lemmasFromSingle);
        $this->assertSame('word lemma', $lemmasFromSingle[0]->lemma());
        $this->assertInstanceOf(PartOfSpeech::class, $lemmasFromSingle[0]->partOfSpeech());
        $this->assertTrue($lemmasFromSingle[0]->partOfSpeech()->is(PartOfSpeech::NOUN()));


        $this->assertSame('all', $allResult->lemmatizedPhrase());
        $lemmasFromAll = $allResult->lemmas();
        $this->assertCount(4, $lemmasFromAll);

        foreach (PartOfSpeech::getEnumerators() as $index => $enum) {
            $expectedLemma = $enum->getValue() . ' lemma';
            $this->assertSame($expectedLemma, $lemmasFromAll[$index]->lemma());
            $this->assertInstanceOf(PartOfSpeech::class, $lemmasFromAll[$index]->partOfSpeech());
            $this->assertTrue($lemmasFromAll[$index]->partOfSpeech()->is($enum));
        }
    }

}

<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 03.05.2017
 * Time: 12:43
 */

namespace Tests\Szyku\NLTK\Serialization;

use Szyku\NLTK\Common\PartOfSpeech;
use Szyku\NLTK\Request\Lemma\LemmaPosFilter;
use Szyku\NLTK\Request\Lemma\LemmatizationRequestBuilder;
use Szyku\NLTK\Request\Lemma\WordLemmatization;
use Szyku\NLTK\Response\Lemma\Lemma;
use Szyku\NLTK\Response\Lemma\LemmaResult;
use Szyku\NLTK\Response\Lemma\LemmatizationResponse;
use Szyku\NLTK\Serialization\JsonSerializer;

class JsonSerializerTest extends \PHPUnit_Framework_TestCase
{
    /** @var JsonSerializer */
    private static $serializer;


    public static function setUpBeforeClass()
    {
        static::$serializer = new JsonSerializer();
    }


    public function testLemmatizationRequestSerialization()
    {
        $builder = LemmatizationRequestBuilder::create();
        $request = $builder
            ->adjective('obese')
            ->noun('cat')
            ->verb('rolls')
            ->findAllFor('over')
            ->adverb('reluctantly')
            ->add("than", LemmaPosFilter::ADV())
            ->build();

        $actual = self::$serializer->serialize($request);
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/data/obese_cat_expected.json',
            $actual
        );
    }

    public function testLemmatizationResponseDeserialization()
    {
        $source = file_get_contents(__DIR__ . '/data/lemmatization_api_response.json');
        /** @var LemmatizationResponse $actual */
        $actual = self::$serializer->deserialize($source, LemmatizationResponse::class);

        $this->assertInstanceOf(LemmatizationResponse::class, $actual);
        $this->checkQueryPart($actual);
        $this->checkResultPart($actual);

        $this->assertGreaterThanOrEqual(0, $actual->lookupTime());
    }

    public function testDictionaryResponse()
    {
    }

    /**
     * @param $actual LemmatizationResponse
     */
    private function checkQueryPart(LemmatizationResponse $actual)
    {
        $queryPart = $actual->query();

        foreach ($queryPart as $query) {
            $this->assertInstanceOf(WordLemmatization::class, $query);
        }

        $this->assertCount(3, $queryPart);

        $testedRequest = $queryPart[0];
        $this->assertSame('cupcakes', $testedRequest->word());
        $this->assertInstanceOf(LemmaPosFilter::class, $testedRequest->partOfSpeech());
        $this->assertTrue($queryPart[0]->partOfSpeech()->is(LemmaPosFilter::NOUN()));
    }

    /**
     * @param $actual LemmatizationResponse
     */
    private function checkResultPart(LemmatizationResponse $actual)
    {
        $resultPart = $actual->results();
        /** @var LemmaResult $result */
        foreach ($resultPart as $result) {
            $this->assertInstanceOf(LemmaResult::class, $result);
            $this->assertInternalType('array', $result->lemmas());

        }
        $this->assertCount(3, $resultPart);
        /** @var LemmaResult $testedResult */
        $testedResult = $resultPart[2];
        $this->assertSame('working', $testedResult->lemmatizedPhrase());
        $this->assertCount(4, $testedResult->lemmas());

        foreach ($testedResult->lemmas() as $lemma) {
            $this->assertInstanceOf(Lemma::class, $lemma);
            $this->assertInstanceOf(PartOfSpeech::class, $lemma->partOfSpeech());
            $this->assertNotEmpty($lemma->lemma());
        }
    }


}

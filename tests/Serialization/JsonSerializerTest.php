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
use Szyku\NLTK\Response\Dictionary\Definition;
use Szyku\NLTK\Response\Dictionary\WordLookupResponse;
use Szyku\NLTK\Response\Lemma\Lemma;
use Szyku\NLTK\Response\Lemma\LemmaResult;
use Szyku\NLTK\Response\Lemma\LemmatizationResponse;
use Szyku\NLTK\Serialization\JsonSerializer;

class JsonSerializerTest extends \PHPUnit_Framework_TestCase
{
    use File;
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
            $this->forgeFilePath('obese_cat_expected'),
            $actual
        );
    }

    public function testLemmatizationResponseDeserialization()
    {
        $source = file_get_contents($this->forgeFilePath('lemmatization_api_response'));
        /** @var LemmatizationResponse $actual */
        $actual = self::$serializer->deserialize($source, LemmatizationResponse::class);

        $this->assertInstanceOf(LemmatizationResponse::class, $actual);
        $this->checkQueryPart($actual);
        $this->checkResultPart($actual);

        $this->assertGreaterThanOrEqual(0, $actual->lookupTime());
    }

    public function testDictionaryResponse()
    {
        $source = file_get_contents($this->forgeFilePath('word_lookup_response'));
        /** @var WordLookupResponse $actual */
        $actual = self::$serializer->deserialize($source, WordLookupResponse::class);

        $this->assertInstanceOf(WordLookupResponse::class, $actual);

        $this->assertSame('savage', $actual->queriedPhrase());
        /** @var Definition $result */
        foreach ($actual->results() as $result) {
            $this->assertInstanceOf(Definition::class, $result);
            $this->assertInstanceOf(PartOfSpeech::class, $result->partOfSpeech());
            $this->assertInternalType('string', $result->phrase());
            $this->assertInternalType('string', $result->definition());
            $this->assertNotEmpty($result->phrase());
            $this->assertNotEmpty($result->definition());
        }
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

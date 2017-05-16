<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 03.05.2017
 * Time: 21:23
 */

namespace Tests\Szyku\NLTK;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use Szyku\NLTK\Client;
use Szyku\NLTK\Request\Dictionary\DefinitionLookupRequest;
use Szyku\NLTK\Request\Dictionary\SimilarLookupRequest;
use Szyku\NLTK\Request\Lemma\LemmatizationRequestBuilder;
use Szyku\NLTK\Request\Tagger\TaggingRequest;
use Szyku\NLTK\Response\Dictionary\WordLookupResponse;
use Szyku\NLTK\Response\Lemma\LemmatizationResponse;
use Szyku\NLTK\Response\Tagger\ExtendedPartOfSpeech;
use Szyku\NLTK\Response\Tagger\TaggingResponse;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    const FILE_LEMMA = 'lemma_response';
    const FILE_LOOKUP = 'word_lookup_response';
    const FILE_TAGGER = 'tagger_response';

    protected $history = [];

    protected function setUp()
    {
        $this->history = [];
    }

    public function testTaggerEndpoint()
    {
        $client = $this->createClient($this->happyPathDictionaryHandler(self::FILE_TAGGER));
        $taggingReq = new TaggingRequest(["I yelled at him, but he didn't listen..."]);
        $response = $client->tagging($taggingReq);

        $this->assertInstanceOf(TaggingResponse::class, $response);

        $this->assertSame('I yelled at him, but he didn\'t listen...', $response->input()[0]);
        $segment = $response->parts()[0][0];
        $this->assertSame('I', $segment->item());
        $this->assertInstanceOf(ExtendedPartOfSpeech::class, $segment->partOfSpeech());
        $this->assertTrue($segment->partOfSpeech()->is(ExtendedPartOfSpeech::PRO_PERSON()));
    }

    public function testDictionaryLookupForDefinition()
    {
        $client = $this->createClient($this->happyPathDictionaryHandler());
        $request = DefinitionLookupRequest::noun('dogs');

        $response = $client->dictionary($request);
        $this->assertInstanceOf(WordLookupResponse::class, $response);

        $this->assertSame('dogs', $response->queriedPhrase());
        $this->assertCount(1, $this->history);
        /** @var Request $madeRequest */
        $madeRequest = $this->history[0]['request'];

        $this->assertSame('GET', $madeRequest->getMethod());
        $reqUri = $madeRequest->getUri();
        $this->assertSame('localhost', $reqUri->getHost());
        $this->assertSame(5000, $reqUri->getPort());
        $this->assertSame('/definition/dogs/noun', $reqUri->getPath());
    }

    public function testDictionaryLookupForSimilar()
    {
        $client = $this->createClient($this->happyPathDictionaryHandler());
        $request = SimilarLookupRequest::noun('dogs');

        $response = $client->dictionary($request);
        $this->assertInstanceOf(WordLookupResponse::class, $response);

        $this->assertSame('dogs', $response->queriedPhrase());
        $this->assertCount(1, $this->history);
        /** @var Request $madeRequest */
        $madeRequest = $this->history[0]['request'];

        $this->assertSame('GET', $madeRequest->getMethod());
        $reqUri = $madeRequest->getUri();
        $this->assertSame('localhost', $reqUri->getHost());
        $this->assertSame(5000, $reqUri->getPort());
        $this->assertSame('/similar/dogs/noun', $reqUri->getPath());
    }

    public function testLemmaLookUp()
    {
        $request = LemmatizationRequestBuilder::create()
            ->findAllFor('working')
            ->build();

        $client = $this->createClient($this->happyPathDictionaryHandler(self::FILE_LEMMA));

        $response = $client->lemmatization($request);
        $this->assertInstanceOf(LemmatizationResponse::class, $response);
        $query = $response->query();
        $this->assertSame('working', $query[0]->word());

        $this->assertCount(1, $this->history);
        /** @var Request $madeRequest */
        $madeRequest = $this->history[0]['request'];
        $this->assertSame('POST', $madeRequest->getMethod());
        $this->assertEquals('application/json', $madeRequest->getHeaderLine('Content-Type'));
        $reqContent = $madeRequest->getBody()->getContents();
        $this->assertNotEmpty($reqContent);
        $this->assertJsonStringEqualsJsonFile(__DIR__ . '/data/expected_lemma_request_body.json', $reqContent);

        $reqUri = $madeRequest->getUri();
        $this->assertSame('localhost', $reqUri->getHost());
        $this->assertSame(5000, $reqUri->getPort());
        $this->assertSame('/lemma', $reqUri->getPath());
    }


    private function happyPathDictionaryHandler($type = self::FILE_LOOKUP)
    {
        $responseBody = file_get_contents(__DIR__ . "/data/$type.json");

        return MockHandler::createWithMiddleware([
            new Response(200, ['Content-Type' => 'application/json'], $responseBody)
        ]);
    }

    protected function createClient($handler)
    {
        $stack = new HandlerStack();
        $stack->setHandler($handler);
        $stack->push(Middleware::history($this->history));
        $uri = new Uri('http://localhost:5000');
        $guzzleClient = new \GuzzleHttp\Client(['handler' => $stack, 'base_uri' => $uri]);

        return new Client($guzzleClient);
    }

}

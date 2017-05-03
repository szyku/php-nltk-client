<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 03.05.2017
 * Time: 23:25
 */

namespace Tests\Szyku\NLTK;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Szyku\NLTK\Exception\NltkClientExceptionInterface;
use Szyku\NLTK\Exception\NltkClientServiceException;
use Szyku\NLTK\Exception\UnexpectedResponseFormatException;
use Szyku\NLTK\Request\Dictionary\DefinitionLookupRequest;
use Szyku\NLTK\Request\Lemma\LemmatizationRequestBuilder;
use Tests\Szyku\NLTK\ClientTest as BaseTest;

class ClientExceptionTest extends BaseTest
{
    /**
     * @dataProvider serverInteractionProvider
     */
    public function testConnectionDictionaryErrors(
        $primaryExpectedException,
        $expectedPreviousException,
        $response,
        $assertionMsg
    ) {
        $client = $this->createClient(MockHandler::createWithMiddleware([$response]));

        $exception = null;
        try {
            $client->dictionary(DefinitionLookupRequest::phrase('error'));
        } catch (\Exception $e) {
            $exception = $e;
        }
        $this->assertInstanceOf($primaryExpectedException, $exception);
        $this->assertNotNull($exception, $assertionMsg);
        $this->assertInstanceOf($expectedPreviousException, $exception->getPrevious(), $assertionMsg);
    }

    /**
     * @dataProvider serverInteractionProvider
     */
    public function testConnectionLemmaErrors(
        $primaryExpectedException,
        $expectedPreviousException,
        $response,
        $assertionMsg
    ) {
        $client = $this->createClient(MockHandler::createWithMiddleware([$response]));

        $exception = null;
        try {
            $req = LemmatizationRequestBuilder::create()->noun('error')->build();
            $client->lemmatization($req);
        } catch (\Exception $e) {
            $exception = $e;
        }
        $this->assertInstanceOf($primaryExpectedException, $exception);
        $this->assertNotNull($exception, $assertionMsg);
        $this->assertInstanceOf($expectedPreviousException, $exception->getPrevious(), $assertionMsg);
    }

    public function serverInteractionProvider()
    {
        return [
            [
                NltkClientServiceException::class,
                ServerException::class,
                new Response(500),
                "Server errors not handled properly."
            ],
            [
                NltkClientServiceException::class,
                ClientException::class,
                new Response(404),
                "Client errors not handled properly."
            ],
            [
                NltkClientServiceException::class,
                ClientException::class,
                new Response(400),
                "Client errors not handled properly."
            ],
            [
                NltkClientServiceException::class,
                UnexpectedResponseFormatException::class,
                new Response(200, ['Content-Type' => 'application/json'], '{"notgood":"dddd"}'),
                "Faulty response body not handled properly."
            ],
            [
                NltkClientServiceException::class,
                UnexpectedValueException::class,
                new Response(200, ['Content-Type' => 'application/json'], '{"this_is_very_faulty"}'),
                "Broken JSON response not handled properly."
            ],
        ];
    }
}

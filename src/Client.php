<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 22:38
 */

namespace Szyku\NLTK;


use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\StreamInterface;
use Szyku\NLTK\Exception\UnsupportedRequestException;
use Szyku\NLTK\Request\Dictionary\DefinitionLookupRequest;
use Szyku\NLTK\Request\Dictionary\SimilarLookupRequest;
use Szyku\NLTK\Request\Dictionary\WordLookupRequest;
use Szyku\NLTK\Request\Lemma\LemmatizationRequest;
use Szyku\NLTK\Response\Dictionary\WordLookupResponse;
use Szyku\NLTK\Response\Lemma\LemmatizationResponse;
use Szyku\NLTK\Util\PhraseNormalization;

class Client implements NltkClient
{
    /** @var ClientInterface */
    private $httpClient;

    /** @var string */
    private $apiHost;

    /**
     * {@inheritdoc}
     */
    public function dictionary(WordLookupRequest $request)
    {
        $uri = $this->forgeDictionaryUri($request);
        $response = $this->httpClient->request('GET', $uri);
        // @todo denormalize response
    }

    /**
     * {@inheritdoc}
     */
    public function lemmatization(LemmatizationRequest $request)
    {
        /** @var StreamInterface $outputStream */
        $outputStream = null;
        // @todo implement normalized payload stream using LemmatizationRequest
        $httpRequest = new Request('POST', '/lemma');
        $httpRequest
            ->withHeader('Content-Type', 'application/json')
            ->withBody($outputStream);
        $response = $this->httpClient->send($httpRequest);
        // @todo denormalize response
    }

    /**
     * @param WordLookupRequest $request
     * @return string
     */
    private function forgeDictionaryUri(WordLookupRequest $request)
    {
        $fragments = [$this->apiHost];
        if ($request instanceof DefinitionLookupRequest) {
            $fragments[] = 'definition';
        } elseif ($request instanceof SimilarLookupRequest) {
            $fragments = 'similar';
        } else {
            throw new UnsupportedRequestException(sprintf(
                    "This client does not know how to handle class %s", get_class($request))
            );
        }

        $fragments[] = PhraseNormalization::normalizeForApi($request->word());
        if ($request->isNarrowedByPos()) {
            $pos = $request->partOfSpeech();
            $fragments[] = $pos->getValue();
        }

        $uri = implode('/', $fragments);
        return $uri;
    }


}

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
use Szyku\NLTK\Exception\NltkClientServiceException;
use Szyku\NLTK\Exception\UnsupportedRequestException;
use Szyku\NLTK\Request\Dictionary\DefinitionLookupRequest;
use Szyku\NLTK\Request\Dictionary\SimilarLookupRequest;
use Szyku\NLTK\Request\Dictionary\WordLookupRequest;
use Szyku\NLTK\Request\Lemma\LemmatizationRequest;
use Szyku\NLTK\Request\Tagger\TaggingRequest;
use Szyku\NLTK\Response\Dictionary\WordLookupResponse;
use Szyku\NLTK\Response\Lemma\LemmatizationResponse;
use Szyku\NLTK\Response\Tagger\TaggingResponse;
use Szyku\NLTK\Serialization\JsonSerializer;
use Szyku\NLTK\Util\PhraseNormalization;
use function GuzzleHttp\Psr7\stream_for as Stream;

class Client implements NltkClient
{
    const PATH_LEMMA = '/lemma';
    const PATH_TAGGER = '/tagger';
    /** @var ClientInterface */
    private $httpClient;

    /** @var JsonSerializer */
    protected $serializer;

    /**
     * Client constructor.
     * @param ClientInterface $httpClient
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->serializer = new JsonSerializer();
    }


    /**
     * {@inheritdoc}
     */
    public function dictionary(WordLookupRequest $request)
    {
        try {
            $uri = $this->forgeDictionaryPathFragment($request);
            $response = $this->httpClient->request('GET', $uri);

            return $this->serializer->deserialize($response->getBody()->getContents(), WordLookupResponse::class);
        } catch (\Exception $e) {
            $this->throwServiceException($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function lemmatization(LemmatizationRequest $request)
    {
        try {
            $response = $this->sendRequestToPath($request, self::PATH_LEMMA);

            return $this->serializer->deserialize($response->getBody()->getContents(), LemmatizationResponse::class);
        } catch (\Exception $e) {
            $this->throwServiceException($e);
        }
    }


    /**
     * {@inheritdoc}
     */
    public function tagging(TaggingRequest $request)
    {
        try {
            $response = $this->sendRequestToPath($request, self::PATH_TAGGER);

            return $this->serializer->deserialize($response->getBody()->getContents(), TaggingResponse::class);
        } catch (\Exception $e) {
            $this->throwServiceException($e);
        }
    }

    /**
     * @param WordLookupRequest $request
     * @return string
     */
    private function forgeDictionaryPathFragment(WordLookupRequest $request)
    {
        $fragments = [];
        if ($request instanceof DefinitionLookupRequest) {
            $fragments[] = 'definition';
        } elseif ($request instanceof SimilarLookupRequest) {
            $fragments[] = 'similar';
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

    /**
     * @param $e
     */
    private function throwServiceException($e)
    {
        throw new NltkClientServiceException(
            "Couldn't complete request. More details in the exception trace.",
            $e
        );
    }

    /**
     * @param TaggingRequest $request
     */
    private function sendRequestToPath($request, $path)
    {
        /** @var StreamInterface $outputStream */
        $outputStream = Stream($this->serializer->serialize($request));
        $httpRequest = new Request('POST', $path);
        $httpRequest = $httpRequest
            ->withHeader('Content-Type', 'application/json')
            ->withBody($outputStream);

        return $this->httpClient->send($httpRequest);
    }
}

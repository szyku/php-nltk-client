<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 03.05.2017
 * Time: 14:47
 */

namespace Szyku\NLTK\Serialization\Lemma;


use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Szyku\NLTK\Common\PartOfSpeech;
use Szyku\NLTK\Request\Lemma\LemmaPosFilter;
use Szyku\NLTK\Request\Lemma\WordLemmatization;
use Szyku\NLTK\Response\Lemma\Lemma;
use Szyku\NLTK\Response\Lemma\LemmaResult;
use Szyku\NLTK\Response\Lemma\LemmatizationResponse;

final class LemmatizationResponseDenormalizator implements DenormalizerInterface
{

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {

        $queryPart = $this->processQueryPart($data);
        $resultPart = $this->processResultPart($data);
        $time = $data['time'];

        return new LemmatizationResponse($queryPart, $resultPart, $time);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return LemmatizationResponse::class === $type && 'json' === $format;
    }

    /**
     * @param $data
     */
    private function processQueryPart($data)
    {
        $queryPart = [];
        foreach ($data['query'] as $queryReq) {
            if (!LemmaPosFilter::has($queryReq['partOfSpeech'])) {
                throw new UnexpectedValueException(sprintf(
                    'Query\'s partOfSpeech attribute has an unrecognized value (%s).', $queryPart['partOfSpeech']
                ));
            }

            $queryPart[] = new WordLemmatization($queryReq['word'], LemmaPosFilter::get($queryReq['partOfSpeech']));
        }

        return $queryPart;
    }

    private function processResultPart($data)
    {
        $resultPart = [];
        foreach ($data['results'] as $result) {
            $lemmas = $this->extractLemmas($result['result']);
            $word = $result['word'];
            $resultPart[] = new LemmaResult($word, $lemmas);
        }

        return $resultPart;
    }

    private function extractLemmas($result)
    {
        if ($this->isSinglePhraseResult($result)) {
            return [$this->forgeLemma($result)];
        }

        $manyLemmas = [];
        foreach ($result as $item) {
            $manyLemmas[] = $this->forgeLemma($item);
        }

        return $manyLemmas;
    }

    private function isSinglePhraseResult($result)
    {
        return isset($result['lemma'], $result['partOfSpeech']);
    }

    private function forgeLemma($data)
    {
        if (!PartOfSpeech::has($data['partOfSpeech'])) {
            throw new UnexpectedValueException('Lemma\'s partOfSpeech attribute has an unrecognized value.');
        }

        return new Lemma($data['lemma'], PartOfSpeech::get($data['partOfSpeech']));
    }
}

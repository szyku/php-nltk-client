<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 03.05.2017
 * Time: 13:26
 */

namespace Szyku\NLTK\Serialization\Lemma;


use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Szyku\NLTK\Request\Lemma\LemmatizationRequest;
use Szyku\NLTK\Request\Lemma\WordLemmatization;
use Szyku\NLTK\Util\PhraseNormalization;

final class LemmatizationRequestNormalizer implements NormalizerInterface
{

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        /** @var $object LemmatizationRequest */
        $data = [];
        /** @var WordLemmatization $wordLemmatization */
        foreach ($object as $wordLemmatization) {
            $entry = new \stdClass();
            $entry->word = PhraseNormalization::normalizeForApi($wordLemmatization->word());
            $entry->partOfSpeech = $wordLemmatization->partOfSpeech()->getValue();
            $data[] = $entry;
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof LemmatizationRequest && 'json' === $format;
    }


}

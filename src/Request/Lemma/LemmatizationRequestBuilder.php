<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 21:17
 */

namespace Szyku\NLTK\Request\Lemma;


use Szyku\NLTK\Assertion\Assertion;
use Szyku\NLTK\Exception\NltkClientAssertionException;

class LemmatizationRequestBuilder
{
    /** @var array WordLemmatization[] */
    private $lemmaRequests = [];

    const KEY_WORD = 'word';
    const KEY_POS = 'pos';

    private function __construct()
    {
        $this->lemmaRequests = [];
    }

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param $phrase
     * @return
     * @throws NltkClientAssertionException
     */
    public function findAllFor($phrase)
    {
        $this->lemmaRequests[] = new WordLemmatization($phrase, LemmaPosFilter::ALL());

        return $this;
    }

    /**
     * @param $phrase
     * @return $this
     * @throws NltkClientAssertionException
     */
    public function noun($phrase)
    {
        $this->lemmaRequests[] = new WordLemmatization($phrase, LemmaPosFilter::NOUN());

        return $this;
    }

    /**
     * @param $phrase
     * @return $this
     * @throws NltkClientAssertionException
     */
    public function verb($phrase)
    {
        $this->lemmaRequests[] = new WordLemmatization($phrase, LemmaPosFilter::VERB());

        return $this;
    }

    /**
     * @param $phrase
     * @return $this
     * @throws NltkClientAssertionException
     */
    public function adjective($phrase)
    {
        $this->lemmaRequests[] = new WordLemmatization($phrase, LemmaPosFilter::ADJ());

        return $this;
    }

    /**
     * @param $phrase
     * @return $this
     * @throws NltkClientAssertionException
     */
    public function adverb($phrase)
    {
        $this->lemmaRequests[] = new WordLemmatization($phrase, LemmaPosFilter::ADV());

        return $this;
    }

    /**
     * @param $phrase
     * @param $filter LemmaPosFilter
     * @return $this
     * @throws NltkClientAssertionException
     */
    public function add($phrase, LemmaPosFilter $filter)
    {
        $this->lemmaRequests[] = new WordLemmatization($phrase, $filter);

        return $this;
    }

    /**
     * @return LemmatizationRequest
     */
    public function build()
    {
        Assertion::notNull(
            $this->lemmaRequests,
            "Builder cannot build more than once using the same builder. Create a new builder object."
        );
        Assertion::notEmpty($this->lemmaRequests, "Cannot build request. No phrases has been added.");

        $object = new LemmatizationRequest($this->lemmaRequests);
        $this->lemmaRequests = null;

        return $object;
    }
}

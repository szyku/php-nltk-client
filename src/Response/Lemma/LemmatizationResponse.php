<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 18:03
 */

namespace Szyku\NLTK\Response\Lemma;


use Szyku\NLTK\Assertion\Assertion;
use Szyku\NLTK\Request\Lemma\WordLemmatization;
use Szyku\NLTK\Response\NltkResponse;

class LemmatizationResponse extends NltkResponse implements \IteratorAggregate
{
    /** @var WordLemmatization[] */
    private $query;
    /** @var LemmaResult[] */
    private $results;

    /**
     * LemmatizationResponse constructor.
     * @param WordLemmatization[] $query
     * @param LemmaResult[] $results
     */
    public function __construct(array $query, array $results)
    {
        Assertion::allIsInstanceOf($query, WordLemmatization::class, sprintf(
            "Query should contain only %s objects.", WordLemmatization::class
        ));
        Assertion::allIsInstanceOf($results, LemmaResult::class, sprintf(
            "Results should contain only %s objects.", LemmaResult::class
        ));
        $this->query = $query;
        $this->results = $results;
    }

    /**
     * @return WordLemmatization[]
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return LemmaResult[]
     */
    public function getResults()
    {
        return $this->results;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->results);
    }
}

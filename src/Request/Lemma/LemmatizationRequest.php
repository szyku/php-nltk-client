<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 21:40
 */

namespace Szyku\NLTK\Request\Lemma;


use Szyku\NLTK\Assertion\Assertion;

final class LemmatizationRequest implements \IteratorAggregate
{
    /** @var WordLemmatization[] */
    private $requests = [];

    /**
     * LemmatizationRequest constructor.
     * @param array $requests
     */
    public function __construct(array $requests)
    {
        Assertion::allIsInstanceOf($requests, WordLemmatization::class, sprintf(
            "Requests must contain only %s objects.", WordLemmatization::class
        ));
        $this->requests = $requests;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->requests);
    }

}

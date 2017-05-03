<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 17:55
 */

namespace Szyku\NLTK\Response\Dictionary;



use Szyku\NLTK\Response\NltkResponse;

class WordLookupResponse extends NltkResponse
{

    /** @var string */
    private $word;

    /** @var Definition[] */
    private $results = [];

    /**
     * WordLookupResponse constructor.
     * @param string $queriedPhrase
     * @param Definition[] $results
     */
    public function __construct($queriedPhrase, array $results, $time)
    {
        parent::__construct($time);
        $this->word = $queriedPhrase;
        $this->results = $results;
    }


    /**
     * @return string
     */
    public function queriedPhrase()
    {
        return $this->word;
    }


}

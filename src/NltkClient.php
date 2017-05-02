<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 22:39
 */

namespace Szyku\NLTK;


use Szyku\NLTK\Request\Dictionary\WordLookupRequest;
use Szyku\NLTK\Request\Lemma\LemmatizationRequest;
use Szyku\NLTK\Response\Dictionary\WordLookupResponse;
use Szyku\NLTK\Response\Lemma\LemmatizationResponse;

interface NltkClient
{
    /**
     * Issues a request to the API to find definitions or synonyms
     * depending on the type of the request object.
     *
     * @param WordLookupRequest $request
     * @return WordLookupResponse
     */
    public function dictionary(WordLookupRequest $request);

    /**
     * Issues a request to the API to find lemmas for the requested phrases.
     *
     * @param LemmatizationRequest $request
     * @return LemmatizationResponse
     */
    public function lemmatization(LemmatizationRequest $request);
}

# PHP NLTK API Client [![Build Status](https://travis-ci.org/szyku/php-nltk-client.svg?branch=master)](https://travis-ci.org/szyku/php-nltk-client)

This is a PHP client for [szyku/nltk-api](https://github.com/szyku/nltk-api) which exposes python's [NLTK resources](http://www.nltk.org/) and [WordNet database](https://wordnet.princeton.edu/) through a friendly API.

The aim of this client is to provide a comfortable and intuitive way of interacting with the NLTK API.

## Installation

Coming soon...

## How to use it

Easy to setup:

```php
require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Psr7\Uri;
use Szyku\NLTK\Client;
use GuzzleHttp\Client as Guzzle;

$uri = new Uri("http://localhost:5000");
$guzzle = new Guzzle(['base_uri' => $uri]);
// or just $guzzle = new Guzzle(['base_uri' => 'http://localhost:5000']);

$nltkClient = new Client($guzzle);
```

How to query definitions with handy methods:

```php
use Szyku\NLTK\Request\Dictionary\DefinitionLookupRequest;
use Szyku\NLTK\Request\Dictionary\SimilarLookupRequest;
use Szyku\NLTK\Request\Lemma\LemmatizationRequestBuilder as Builder;

$similarWordsToCastle = SimilarLookupRequest::noun('castle');
$definitionsForRA = DefinitionLookupRequest::noun('rheumatoid arthritis');

$lemmatizeSentence = Builder::create()
    ->adjective('biggest')
    ->noun('dogs')
    ->verb('fought')
    ->adverb('loudly')
    ->findAllFor('at')
    ->build();

// results are hydrated to objects like WordLookupRequest or LemmatizationResponse
$castleResult = $nltkClient->dictionary($similarWordsToCastle);
$raResult = $nltkClient->dictionary($definitionsForRA);
$lemmatizationResult = $nltkClient->lemmatization($lemmatizeSentence);
// easy to consume
echo "Time taken in seconds: " . $castleResult->lookupTime();
echo "Searched for: " . $castleResult->queriedPhrase();

foreach ($castleResult->results() as $result) {
    echo $result->phrase() . ": " . $result->definition();
}
// prints "palace: A large and stately mansion"
```

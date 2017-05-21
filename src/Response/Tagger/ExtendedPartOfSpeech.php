<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 15.05.2017
 * Time: 22:38
 */

namespace Szyku\NLTK\Response\Tagger;


use MabeEnum\Enum;
use Szyku\NLTK\Assertion\Assertion;
use Szyku\NLTK\Exception\NltkClientServiceException;

/**
 * Represents Penn's parts of speech
 * @link https://www.ling.upenn.edu/courses/Fall_2003/ling001/penn_treebank_pos.html Penn Treebank
 * Class ExtendedPartOfSpeech
 * @package Szyku\NLTK\Response\Tagger
 */
class ExtendedPartOfSpeech extends Enum
{
    const DOLLAR = '$';
    const QUOTE_CLOSE = "''";
    const PAR_OPEN = '(';
    const PAR_CLOSE = ')';
    const COMMA = ',';
    const DASH = '--';
    const TERMINATOR = '.';
    const COLON_ELLIPS = ':';
    const CONJ_CORD = 'CC';
    const NUM_CARD = 'CD';
    const DETERMINER = 'DT';
    const EXIST_THERE = 'EX';
    const FOREIGN_WORD = 'FW';
    const PREP_CONJ_SUB = 'IN';
    const ADJ_NUM_ORD = 'JJ';
    const ADJ_COMP = 'JJR';
    const ADJ_SUPER = 'JJS';
    const LIST_MRK = 'LS';
    const MODAL_AUX = 'MD';
    const NOUN_SING_MASS = 'NN';
    const NOUN_PROP_SING = 'NNP';
    const NOUN_PROP_PL = 'NNPS';
    const NOUN_COMM_PL = 'NNS';
    const PRE_DET = 'PDT';
    const GENITIVE_MRK = 'POS';
    const PRO_PERSON = 'PRP';
    const PRO_POSSESS = 'PRP$';
    const ADV = 'RB';
    const ADV_COMP = 'RBR';
    const ADV_SUPER = 'RBS';
    const PARTICLE = 'RP';
    const SYMBOL = 'SYM';
    const TO_INF = 'TO';
    const INTERJECT = 'UH';
    const VERB_BASE = 'VB';
    const VERB_PAST = 'VBD';
    const VERB_PRESENT = 'VBG';
    const VERB_PAST_PART = 'VBN';
    const VERB_PRES_NOT_3RD = 'VBP';
    const VERB_PRES_3RD = 'VBZ';
    const W_DET = 'WDT';
    const W_PRO = 'WP';
    const W_PRO_POSSESS = 'WP$';
    const W_ADV = 'WRB';
    const QUOTE_OPEN = '``';

    private static $map = [
        self::DOLLAR            => 'dollar',
        self::QUOTE_CLOSE       => 'quote_close',
        self::PAR_OPEN          => 'parenthesis_open',
        self::PAR_CLOSE         => 'parenthesis_close',
        self::COMMA             => 'comma',
        self::DASH              => 'dash',
        self::TERMINATOR        => 'terminator',
        self::COLON_ELLIPS      => 'colon_ellipsis',
        self::CONJ_CORD         => 'conj_coordinating',
        self::NUM_CARD          => 'numeral_cardinal',
        self::DETERMINER        => 'determiner',
        self::EXIST_THERE       => 'existential_there',
        self::FOREIGN_WORD      => 'foreign word',
        self::PREP_CONJ_SUB     => 'prep_or_conj_subordinating',
        self::ADJ_NUM_ORD       => 'adj_or_numeral_ordinal',
        self::ADJ_COMP          => 'adj_comparative',
        self::ADJ_SUPER         => 'adj_superlative',
        self::LIST_MRK          => 'list_item_marker',
        self::MODAL_AUX         => 'modal_aux',
        self::NOUN_SING_MASS    => 'noun_comm_sing_or_mass',
        self::NOUN_PROP_SING    => 'noun_proper_sing',
        self::NOUN_PROP_PL      => 'noun_proper_pl',
        self::NOUN_COMM_PL      => 'noun_comm_pl',
        self::PRE_DET           => 'pre-determiner',
        self::GENITIVE_MRK      => 'genitive_marker',
        self::PRO_PERSON        => 'pronoun_personal',
        self::PRO_POSSESS       => 'pronoun_possessive',
        self::ADV               => 'adverb',
        self::ADV_COMP          => 'adverb_comparative',
        self::ADV_SUPER         => 'adverb_superlative',
        self::PARTICLE          => 'particle',
        self::SYMBOL            => 'symbol',
        self::TO_INF            => 'to_infi_pre',
        self::INTERJECT         => 'interjection',
        self::VERB_BASE         => 'verb_base',
        self::VERB_PAST         => 'verb_past',
        self::VERB_PRESENT      => 'verb_present',
        self::VERB_PAST_PART    => 'verb_past_parti',
        self::VERB_PRES_NOT_3RD => 'verb_present_not_3rd_person',
        self::VERB_PRES_3RD     => 'verb_present_3rd_person',
        self::W_DET             => 'wh-determiner',
        self::W_PRO             => 'wh-pronoun',
        self::W_PRO_POSSESS     => 'wh-pronoun_possessive',
        self::W_ADV             => 'wh-adverb',
        self::QUOTE_OPEN        => 'quote_open',
    ];

    /**
     * @param $symbol
     * @return static
     */
    final public static function fromSymbol($symbol)
    {
        Assertion::keyExists(self::$map, $symbol, 'Symbol not recognized.');

        return self::byValue($symbol);
    }

    /**
     * @param $symbol
     * @return static
     */
    final public static function fromDescription($description)
    {
        Assertion::inArray($description, self::$map, 'Description not recognized.');
        $symbolKey = array_search($description, self::$map);

        return self::byValue($symbolKey);
    }

    /**
     * @param $key
     * @return bool
     */
    final public static function isSymbolicKey($key)
    {
        return array_key_exists($key, self::$map);
    }

    /**
     * @param $key
     * @return bool
     */
    final public static function isDescriptiveKey($key)
    {
        return in_array($key, self::$map);
    }

    /**
     * @param $key
     */
    public static function createFromKey($key)
    {
        Assertion::string($key, "The key must be string type");
        if (self::isSymbolicKey($key)) {
            return self::fromSymbol($key);
        }

        if (self::isDescriptiveKey($key)) {
            return self::fromDescription($key);
        }

        throw new NltkClientServiceException(sprintf("Key %s not recognized.", $key));
    }

    public function token()
    {
        return $this->getValue();
    }

    public function humanizedToken()
    {
        return self::$map[$this->getValue()];
    }
}

<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 18:26
 */

namespace Szyku\NLTK\Request\Dictionary;


use MabeEnum\Enum;
use Szyku\NLTK\Common\PartOfSpeech;

class PosFilter extends Enum
{
    const NOUN = PartOfSpeech::NOUN;
    const VERB = PartOfSpeech::VERB;
    const ADJ = PartOfSpeech::ADJ;
    const ADV = PartOfSpeech::ADV;
}

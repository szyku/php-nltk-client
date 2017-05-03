<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 17:42
 */

namespace Szyku\NLTK\Common;


use MabeEnum\Enum;

final class PartOfSpeech extends Enum
{
    const NOUN = 'noun';
    const VERB = 'verb';
    const ADJ = 'adjective';
    const ADV = 'adverb';

}

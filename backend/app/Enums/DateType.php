<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static January()
 * @method static February()
 * @method static March()
 * @method static April()
 * @method static May()
 * @method static June()
 * @method static July()
 * @method static August()
 * @method static September()
 * @method static October()
 * @method static November()
 * @method static December()
 */
final class DateType extends Enum implements LocalizedEnum
{
    const JANUARY = 'January';
    const FEBRUARY = 'February';
    const MARCH = 'March';
    const APRIL = 'April';
    const MAY = 'May';
    const JUNE = 'June';
    const JULY = 'July';
    const AUGUST = 'August';
    const SEPTEMBER = 'September';
    const OCTOBER = 'October';
    const NOVEMBER = 'November';
    const DECEMBER = 'December';
}

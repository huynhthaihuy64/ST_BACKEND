<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static public()
 * @method static private()
 * @method static close()
 */
final class CampaignStatusType extends Enum
{
    const PUBLIC = 'public';
    const PRIVATE = 'private';
    const CLOSE = 'close';
}

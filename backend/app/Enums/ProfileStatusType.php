<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static new()
 * @method static test()
 * @method static interview()
 * @method static confirm()
 * @method static consider()
 * @method static employee()
 * @method static reject()
 */
final class ProfileStatusType extends Enum
{
    const NEWS = 'new';
    const TEST = 'test';
    const INTERVIEW = 'interview';
    const CONFIRM = 'confirm';
    const CONSIDER = 'consider';
    const EMPLOYEE = 'employee';
    const REJECT = 'reject';
}

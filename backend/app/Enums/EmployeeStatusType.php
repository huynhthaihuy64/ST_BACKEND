<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static active()
 * @method static inactive()
 */
final class EmployeeStatusType extends Enum
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
}

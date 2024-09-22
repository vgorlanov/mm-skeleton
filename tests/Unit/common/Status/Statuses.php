<?php

declare(strict_types=1);

namespace Tests\Unit\common\Status;

use Common\EnumTitle\GetsTitle;
use Common\EnumTitle\HasTitle;
use Common\EnumTitle\Title;

enum Statuses: string implements HasTitle
{
    use GetsTitle;

    #[Title('В процессе')]
    case EXISTS = 'exists';
    case NOT = 'not';

}

<?php

declare(strict_types=1);

namespace Modules\Company\Domain;

use Common\Title\GetsTitle;
use Common\Title\HasTitle;
use Common\Title\Title;

enum Status: string implements HasTitle
{
    use GetsTitle;

    #[Title('Новая')]
    case NEW = 'new';
    #[Title('Активная')]
    case ACTIVE = 'active';
    #[Title('Заблокирована')]
    case BLOCK = 'block';
    #[Title('Удалена')]
    case DELETE = 'delete';
}

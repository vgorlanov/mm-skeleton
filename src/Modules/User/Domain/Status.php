<?php

declare(strict_types=1);

namespace Modules\User\Domain;

use Common\Title\GetsTitle;
use Common\Title\HasTitle;
use Common\Title\Title;

enum Status: string implements HasTitle
{
    use GetsTitle;

    #[Title('Новый')]
    case NEW = 'new';
    #[Title('Активный')]
    case ACTIVE = 'active';
    #[Title('Заблокирован')]
    case BLOCK = 'block';
    #[Title('Удалён')]
    case DELETE = 'delete';
}

<?php

declare(strict_types=1);

namespace Modules\Order\Domain;

use Common\Title\GetsTitle;
use Common\Title\HasTitle;
use Common\Title\Title;

enum Status: string implements HasTitle
{
    use GetsTitle;

    #[Title('Новый')]
    case NEW = 'new';
    #[Title('В процессе')]
    case ACTIVE = 'active';
    #[Title('Отменён')]
    case CANCEL = 'cancel';
    #[Title('Завершён')]
    case COMPLETE = 'complete';
}

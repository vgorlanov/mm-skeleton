<?php

declare(strict_types=1);

namespace Modules\User\Domain;

use Common\Title\GetsTitle;
use Common\Title\HasTitle;
use Common\Title\Title;

enum Gender: string implements HasTitle
{
    use GetsTitle;

    #[Title('Мужской')]
    case MALE = 'male';
    #[Title('Женский')]
    case FEMALE = 'female';
}

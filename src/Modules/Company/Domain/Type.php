<?php

declare(strict_types=1);

namespace Modules\Company\Domain;

use Common\Title\GetsTitle;
use Common\Title\HasTitle;
use Common\Title\Title;

enum Type: string implements HasTitle
{
    use GetsTitle;

    #[Title('Организация')]
    case ORGANIZATION = 'organization';
    #[Title('Индивидуальный предприниматель')]
    case PRIVATE = 'private';
    #[Title('Иностранная компанию')]
    case FOREIGN = 'foreign';
    #[Title('Самозанятый')]
    case SELF = 'self';
}

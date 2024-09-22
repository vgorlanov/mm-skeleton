<?php

declare(strict_types=1);

namespace Common\Queue\RabbitMQ;

/**
 * Routing Key
 */
enum Keys: string
{
    case COMPANY_SAVE = 'CompanySave.RK';

    case COMPANY_DELETE = 'CompanyDelete.RK';

    case REVIEW_CANCELED = 'ReviewSave.RK';

    case PRODUCT_SAVE = 'ProductSave.RK';

    case PRODUCT_DELETE = 'ProductDelete.RK';
}

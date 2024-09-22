<?php

declare(strict_types=1);

namespace Common\Title;

use Common\Status\Exceptions\TitleNotFoundException;
use ReflectionClassConstant;

trait GetsTitle
{
    public function title(): string
    {
        $ref = new ReflectionClassConstant($this::class, $this->name);
        $classAttributes = $ref->getAttributes(Title::class);

        if (count($classAttributes)) {
            return $classAttributes[0]->newInstance()->title;
        }

        throw new TitleNotFoundException('У данного значения нет заголовка: ' . $this::class . '::' . $this->name);
    }
}

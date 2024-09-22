<?php

namespace Common\Title;

/**
 * @property string name
 * @property string value
 */
interface HasTitle
{
    /**
     * @return string
     */
    public function title(): string;
}

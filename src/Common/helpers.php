<?php

use Illuminate\Database\Eloquent\Builder;

if (!function_exists('randomPhone')) {
    /**
     * Рандомный телефон
     *
     * @return int
     * @throws \Exception
     */
    function randomPhone(): int
    {
        return (int) (random_int(1, 9) . random_int(0000000000, 9999999999));
    }
}


if (!function_exists('getQuery')) {
    /**
     * Выводит подготовленный запрос
     *
     * @param Builder $sql
     * @return string
     */
    function getQuery(Builder $sql): string // @phpstan-ignore-line
    {
        $query = str_replace(['?'], ['\'%s\''], $sql->toSql());
        return vsprintf($query, $sql->getBindings());
    }
}

<?php


namespace Sfneal\ViewModels\Interfaces;


interface SuccessFailure
{
    /**
     * Promo block to display when request is successful
     *
     * @return array
     */
    public static function success(): array;

    /**
     * Promo block to display when request has failed
     *
     * @return array
     */
    public static function failure(): array;
}

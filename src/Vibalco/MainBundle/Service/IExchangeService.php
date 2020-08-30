<?php


namespace Vibalco\MainBundle\Service;


interface IExchangeService
{
    public function getLatestCurrency();

    public function getDebugCurrency();
}
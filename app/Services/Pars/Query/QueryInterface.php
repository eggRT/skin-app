<?php

namespace App\Services\Pars\Query;

interface QueryInterface {

    public function getOptions() : array;

    public function getOptionsQuery() : array;

    public function getQueryParams() : array;

    public function getResponseParam() : string;

    public function getMethod() : string;
}
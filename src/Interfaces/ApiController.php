<?php

namespace Usoft\Ufit\Interfaces;

interface ApiController
{
    public function created();

    public function accepted();

    public function singleItem();

    public function paginate();

    public function noContent();

    public function error($message, $status_code);

    public function errorNotFound($message);

    public function errorBadRequest($message);

    public function errorForbidden($message);

    public function errorUnauthorized($message);
}

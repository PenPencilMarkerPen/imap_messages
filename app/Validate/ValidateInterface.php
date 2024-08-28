<?php

namespace App\Validate;

interface ValidateInterface{
    public function validateNotEmpty(array $data):bool;
}
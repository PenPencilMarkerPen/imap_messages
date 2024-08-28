<?php

namespace App\Validate;

require_once('ValidateInterface.php');

use App\Validate\ValidateInterface;

class Validate implements ValidateInterface {

    public function validateNotEmpty(array $data): bool
    {
        $filteredData = array_filter($data, function($value) {
            return !empty($value);
        });
        return count($filteredData) === count($data);
    }
}

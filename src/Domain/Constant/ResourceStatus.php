<?php

namespace App\Domain\Constant;

class ResourceStatus
{
    // @todo add more types?
    const SUCCESS = 0;
    const PROCESSING = 1;
    const NOT_FOUND = 2;
    const FAILED = 3;
}
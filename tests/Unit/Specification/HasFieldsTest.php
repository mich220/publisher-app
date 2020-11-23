<?php

namespace Unit\Specification;

use App\Domain\Specification\HasFields;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class HasFieldsTest extends TestCase
{
    /**
     * @test
     */
    public function returns_true_if_request_has_fields()
    {
        $hasSpec = new HasFields(['field']);

        $request = new Request([], ['field' => 'asd'], [], [], [], [], '');

        $this->assertEquals(true, $hasSpec->isSatisfiedBy($request));
    }
}
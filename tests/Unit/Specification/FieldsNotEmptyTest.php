<?php

namespace Unit\Specification;

use App\Domain\Specification\FieldsNotEmpty as FieldsNotEmptySpec;
use App\Domain\Specification\HasFields;
use App\Domain\Specification\OrSpecification as OrSpec;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class FieldsNotEmptyTest extends TestCase
{
    /**
     * @test
     */
    public function returns_true_if_one_of_condition_satisfied()
    {
        $fieldSpec = new FieldsNotEmptySpec(['field']);

        // todo create request validator, or validate arrays
        $request = new Request([], ['field' => null], [], [], [], [], '');

        $this->assertEquals(true, $fieldSpec->isSatisfiedBy($request));
    }
}
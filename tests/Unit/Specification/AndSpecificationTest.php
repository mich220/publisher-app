<?php

namespace Unit\Specification;

use App\Domain\Specification\AndSpecification;
use App\Domain\Specification\HasFields;
use PHPUnit\Framework\TestCase;
use App\Domain\Specification\FieldsNotEmpty as FieldsNotEmptySpec;
use Symfony\Component\HttpFoundation\Request;

class AndSpecificationTest extends TestCase
{
    /**
     * @test
     */
    public function it_validates_when_x_true_conditions_satisfied()
    {
        $andSpec = new AndSpecification(
            new FieldsNotEmptySpec(['field']),
            new HasFields(['field'])
        );

        // todo create request validator, or validate arrays
        $request = new Request([], ['field' => 'notemptyandnotnull'], [], [], [], [], '');

        $this->assertEquals(true, $andSpec->isSatisfiedBy($request));
    }

    /**
     * @test
     */
    public function it_throw_error_when_one_of_conditions_is_not_satisfied()
    {
        $andSpec = new AndSpecification(
            new FieldsNotEmptySpec(['field']),
            new HasFields(['field'])
        );

        // todo create request validator, or validate arrays
        $request = new Request([], ['field' => null], [], [], [], [], '');

        $this->assertEquals(false, $andSpec->isSatisfiedBy($request));
    }
}
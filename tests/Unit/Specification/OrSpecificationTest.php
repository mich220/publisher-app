<?php

namespace Unit\Specification;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use App\Domain\Specification\OrSpecification as OrSpec;
use App\Domain\Specification\HasFields;
use App\Domain\Specification\FieldsNotEmpty as FieldsNotEmptySpec;

class OrSpecificationTest extends TestCase
{

    /**
     * @test
     */
    public function returns_false_if_no_conditions_satisfied()
    {
        $orSpec = new OrSpec(
            new HasFields(['field']),
            new HasFields(['field'])
        );

        // todo create request validator, or validate arrays
        $request = new Request([], [], ['somefield' => 'somevalue'], [], [], [], '');

        $this->assertEquals(false, $orSpec->isSatisfiedBy($request));
    }

    /**
     * @test
     */
    public function returns_true_if_one_of_condition_satisfied()
    {
        $orSpec = new OrSpec(
            new FieldsNotEmptySpec(['field']),
            new HasFields(['field'])
        );

        // todo create request validator, or validate arrays
        $request = new Request([], ['field' => null], [], [], [], [], '');

        $this->assertEquals(true, $orSpec->isSatisfiedBy($request));
    }
}
<?php

namespace JasminWeb\Test\Command\Validator;

use JasminWeb\Jasmin\Command\AddValidator;
use PHPUnit\Framework\TestCase;

class AddValidatorTest extends TestCase
{
    public function testCheckRequiredAttributes()
    {
        $validator = new class extends AddValidator {
            /**
             * @return array
             */
            public function getRequiredAttributes(): array
            {
                return ['one', 'two', 'three', 'four'];
            }
        };

        $this->assertFalse($validator->checkRequiredAttributes([
            'one' => 1,
            'two' => 2,
            'three' => 3
        ]));

        $this->assertCount(1, $validator->getErrors());
        $this->assertArrayHasKey('four', $validator->getErrors());
    }
}

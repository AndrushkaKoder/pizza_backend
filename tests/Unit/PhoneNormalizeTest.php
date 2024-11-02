<?php

namespace Tests\Unit;

use App\Helpers\InputDataHandlerTrait;
use Tests\TestCase;

class PhoneNormalizeTest extends TestCase
{
    use InputDataHandlerTrait;

    public function test_that_phone_format_correct(): void
    {
        $this->assertEquals('88005553535', $this->normalizePhoneNumber('<script>alert(+-___xxx88005553535)</script>'));
    }

}

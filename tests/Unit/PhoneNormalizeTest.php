<?php

namespace Tests\Unit;

use App\Helpers\PhoneNumberHandler;
use Tests\TestCase;

class PhoneNormalizeTest extends TestCase
{

    public function test_that_phone_format_correct(): void
    {
        $phoneHandler = new PhoneNumberHandler('<script>alert(+-___xxx88005553535)</script>');
        $this->assertEquals('88005553535', $phoneHandler->normalizeFormat());
    }

}

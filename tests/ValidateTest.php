<?php

namespace Test;

use ReflectionMethod;
use PHPUnit\Framework\TestCase;
use ReallySimpleJWT\Validate;
use ReallySimpleJWT\Token;
use ReallySimpleJWT\Helper\Signature;
use Carbon\Carbon;

class ValidateTest extends TestCase
{
    public function testValidate()
    {
        $validate = new Validate();

        $this->assertInstanceOf(Validate::class, $validate);
    }

    public function testValidateStructure()
    {
        $validate = new Validate();

        $this->assertTrue($validate->structure('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvZSBCbG9ncyIsImlhdCI6MTUxNjIzOTAyMn0.-wvw8Qad0enQkwNhG2j-GCT-7PbrMN_gtUwOKZTu54M'));
    }

    public function testValidateStructureWithRSJWT()
    {
        $token = Token::getToken(1, 'foo1234He$$llo56', Carbon::now()->addMinutes(5)->toDateTimeString(), '127.0.0.1');

        $validate = new Validate();

        $this->assertTrue($validate->structure($token));
    }

    public function testValidateStructureInvalid()
    {
        $validate = new Validate();

        $this->assertFalse($validate->structure('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9'));
    }

    public function testValidateExpiration()
    {
        $validate = new Validate();

        $this->assertTrue($validate->expiration(time() + 10));
    }

    public function testValidateExpirationOld()
    {
        $validate = new Validate();

        $this->assertFalse($validate->expiration(time() - 10));
    }

    public function testValidateSignature()
    {
        $validate = new Validate();

        $header = json_encode(json_decode('{"alg": "HS256", "typ": "JWT"}'));
        $payload = json_encode(json_decode('{"sub": "1234567890", "name": "John Doe", "iat": 1516239022}'));

        $signature = new Signature($header, $payload, 'foo1234He$$llo56', 'sha256');

        $this->assertTrue($validate->signature($signature, 'tsVs-jHudH5hV3nNZxGDBe3YRPeH871_Cjs-h23jbTI'));
    }
}

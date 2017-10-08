<?php

namespace Omnipay\Dummy\Message;

use Omnipay\Dummy\Gateway;
use Omnipay\Tests\TestCase;

class CardReferenceRequestTest extends TestCase
{
    public function setUp()
    {
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGetData()
    {
        $request = new CardReferenceRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array(
            'cardReference' => 'pass123'
        ));
        $data = $request->getData();
        $this->assertSame('pass123', $data['cardReference']);
    }

    public function testSuccess()
    {
        // reference ids which are even should pass
        $options = array('cardReference' => '22222');
        $response = $this->gateway->deleteCard($options)->send();

        $this->assertInstanceOf('\Omnipay\Dummy\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNotEmpty($response->getCardReference());
        $this->assertSame('Success', $response->getMessage());
    }

    public function testFailure()
    {
        // reference ids which are odd should fail
        $options = array('cardReference' => '33333');
        $response = $this->gateway->deleteCard($options)->send();

        $this->assertInstanceOf('\Omnipay\Dummy\Message\Response', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNotEmpty($response->getCardReference());
        $this->assertSame('Failure', $response->getMessage());
    }

}

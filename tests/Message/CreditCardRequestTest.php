<?php

namespace Omnipay\Dummy\Message;

use Omnipay\Dummy\Gateway;
use Omnipay\Tests\TestCase;

class CreditCardRequestTest extends TestCase
{
    public function setUp()
    {
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGetData()
    {
        $request = new CreditCardRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array(
            'amount' => '10.00',
            'card' => $this->getValidCard(),
        ));
        $data = $request->getData();
        $this->assertSame('10.00', $data['amount']);
    }

    public function testCreditCardSuccess()
    {
        // card numbers ending in even number should be successful
        $options = array(
            'amount' => '10.00',
            'card' => $this->getValidCard(),
        );
        $options['card']['number'] = '4242424242424242';
        $response = $this->gateway->authorize($options)->send();

        $this->assertInstanceOf('\Omnipay\Dummy\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNotEmpty($response->getTransactionReference());
        $this->assertSame('Success', $response->getMessage());
    }

    public function testCreditCardFailure()
    {
        // card numbers ending in odd number should be declined
        $options = array(
            'amount' => '10.00',
            'card' => $this->getValidCard(),
        );
        $options['card']['number'] = '4111111111111111';
        $response = $this->gateway->authorize($options)->send();

        $this->assertInstanceOf('\Omnipay\Dummy\Message\Response', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNotEmpty($response->getTransactionReference());
        $this->assertSame('Failure', $response->getMessage());
    }

    public function testTokenSuccess()
    {
        // card numbers ending in even number should be successful
        $options = array(
            'amount' => '10.00',
            'cardReference' => '6b34e45d-4015-4864-a8c5-cd7a2df7a3d4',
        );
        $response = $this->gateway->authorize($options)->send();

        $this->assertInstanceOf('\Omnipay\Dummy\Message\Response', $response);
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNotEmpty($response->getTransactionReference());
        $this->assertSame('Success', $response->getMessage());
    }

    public function testTokenFailure()
    {
        // card numbers ending in even number should be successful
        $options = array(
            'amount' => '10.00',
            'cardReference' => 'a9d7105f-cbdd-4984-8591-35b28b739e59',
        );
        $response = $this->gateway->authorize($options)->send();

        $this->assertInstanceOf('\Omnipay\Dummy\Message\Response', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNotEmpty($response->getTransactionReference());
        $this->assertSame('Failure', $response->getMessage());
    }

}

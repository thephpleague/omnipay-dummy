<?php

namespace Omnipay\Dummy\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Dummy Authorize/Purchase Request
 *
 * This is the request that will be called for any transaction which submits a credit card,
 * including `authorize` and `purchase`
 */
class CreditCardRequest extends AbstractRequest
{
    public function getData()
    {
        if ($this->getCardReference()) {
            $this->validate('amount', 'cardReference');
        } else {
            $this->validate('amount', 'card');

            $this->getCard()->validate();
        }

        return array('amount' => $this->getAmount());
    }

    public function sendData($data)
    {
        if ($this->getCardReference()) {
            $data['success'] = 0 === substr($this->getCardReference(), -1, 1) % 2;
        } else {
            $data['success'] = 0 === substr($this->getCard()->getNumber(), -1, 1) % 2;
        }

        $data['reference'] = uniqid();
        $data['message'] = $data['success'] ? 'Success' : 'Failure';

        return $this->response = new Response($this, $data);
    }
}

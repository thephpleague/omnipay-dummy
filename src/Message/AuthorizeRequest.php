<?php

namespace Omnipay\Dummy\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Dummy Authorize Request
 */
class AuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'card');
        $card = $this->getCard();

        if (!($card instanceof \Omnipay\Common\CreditCard)) {
            throw new \Omnipay\Common\Exception\RuntimeException('No credit card object available');
        }

        $card->validate();

        return array('amount' => $this->getAmount());
    }

    public function sendData($data)
    {
        $data['reference'] = uniqid();
        $data['success'] = 0 === substr($this->getCard()->getNumber(), -1, 1) % 2;
        $data['message'] = $data['success'] ? 'Success' : 'Failure';

        return $this->response = new Response($this, $data);
    }
}

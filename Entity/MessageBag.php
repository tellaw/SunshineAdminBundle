<?php
namespace Tellaw\SunshineAdminBundle\Entity;

class MessageBag {

    private $messageBag = array();

    public function addMessage ( $key, $message ) {
        $this->messageBag[$key] = $message;
    }

    public function getMessage ( $key ) {
        if (array_key_exists( $key, $this->messageBag )) {
            return $this->messageBag[$key];
        } else {
            return null;
        }
    }

}
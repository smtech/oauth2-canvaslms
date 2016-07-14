<?php

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class CanvasLMSResourceOwner implements ResourceOwnerInterface {

    protected $properties;

    public function __construct(array $response = []) {
        $this->response = $response;
    }

    public function getId() {
        return $this->properties['id'];
    }

    public function getName() {
        return $this->properties['name'];
    }

    public function toArray() {
        return $this->$properties;
    }
}

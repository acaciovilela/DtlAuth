<?php

namespace DtlAuth\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="facebook")
 */
class Facebook {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(name="access_token", type="string", length=1024)
     * @var string
     */
    protected $accessToken;

    /**
     * @ORM\Column(name="token_type", type="string")
     * @var string
     */
    protected $tokenType;

    /**
     * @ORM\Column(name="expires_in", type="integer", nullable=true)
     * @var integer
     */
    protected $expiresIn;

    /**
     * @ORM\Column(name="face_user_id", type="string", nullable=true)
     * @var string
     */
    protected $faceUserId;

    /**
     * @ORM\ManyToOne(targetEntity="DtlUser\Entity\User", cascade={"persist"})
     * @var \DtlUser\Entity\User
     */
    protected $user;

    public function getId() {
        return $this->id;
    }

    public function getAccessToken() {
        return $this->accessToken;
    }

    public function getTokenType() {
        return $this->tokenType;
    }

    public function getExpiresIn() {
        return $this->expiresIn;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setAccessToken($accessToken) {
        $this->accessToken = $accessToken;
        return $this;
    }

    public function setTokenType($tokenType) {
        $this->tokenType = $tokenType;
        return $this;
    }

    public function setExpiresIn($expiresIn) {
        $this->expiresIn = $expiresIn;
        return $this;
    }

    public function getUser(): \DtlUser\Entity\User {
        return $this->user;
    }

    public function setUser(\DtlUser\Entity\User $user) {
        $this->user = $user;
        return $this;
    }

    public function getFaceUserId() {
        return $this->faceUserId;
    }

    public function setFaceUserId($faceUserId) {
        $this->faceUserId = $faceUserId;
        return $this;
    }

}

<?php

namespace DtlAuth\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="facebook_page")
 */
class FacebookPage {

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
     * @ORM\Column(name="page_id", type="string", nullable=true)
     * @var string
     */
    protected $pageId;

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

    public function getPageId() {
        return $this->pageId;
    }

    public function getUser(): \DtlUser\Entity\User {
        return $this->user;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setAccessToken($accessToken) {
        $this->accessToken = $accessToken;
        return $this;
    }

    public function setPageId($pageId) {
        $this->pageId = $pageId;
        return $this;
    }

    public function setUser(\DtlUser\Entity\User $user) {
        $this->user = $user;
        return $this;
    }

}

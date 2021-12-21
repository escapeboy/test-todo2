<?php
declare(strict_types=1);

namespace Todo\Auth\OAuth;

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_access_tokens", indexes={@ORM\Index(name="user_id_token_index", columns={"user_id"})})
 */
class OauthAccessToken
{
    use Timestamps;
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=100)
     */
    protected string $id;

    /**
     * @ORM\Column(name="user_id", type="guid", nullable=true)
     */
    protected int $userId;

    /**
     * @ORM\Column(name="client_id", type="integer")
     */
    protected int $clientId;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected string $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected string $scopes;

    /**
     * @ORM\Column(type="boolean")
     */
    protected bool $revoked;

    /**
     * @ORM\Column(name="expires_at", type="datetime", nullable=true)
     * @var \DateTime|null
     */
    protected $expiresAt;
}

<?php
declare(strict_types=1);

namespace Todo\Auth\OAuth;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_refresh_tokens", indexes={@ORM\Index(name="access_token_index", columns={"access_token_id"})})
 */
class OauthRefreshToken
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=100)
     */
    protected string $id;

    /**
     * @ORM\Column(name="access_token_id", type="string", length=100)
     */
    protected int $accessTokenId;

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

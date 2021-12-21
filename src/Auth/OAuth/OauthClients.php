<?php
declare(strict_types=1);

namespace Todo\Auth\OAuth;

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_clients", indexes={@ORM\Index(name="user_id_client_index", columns={"user_id"})})
 */
class OauthClients
{
    use Timestamps;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    protected int $id;

    /**
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    protected int $userId;

    /**
     * @ORM\Column(type="string")
     */
    protected string $name;

    /**
     * @ORM\Column(name="secret", type="string", length=100)
     */
    protected string $secret;

    /**
     * @ORM\Column(type="text")
     */
    protected string $redirect;

    /**
     * @ORM\Column(name="personal_access_client", type="boolean")
     */
    protected bool $personalAccessClient;

    /**
     * @ORM\Column(name="password_client", type="boolean")
     */
    protected bool $passwordClient;

    /**
     * @ORM\Column(type="boolean")
     */
    protected bool $revoked;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string|null
     */
    protected ?string $provider;
}

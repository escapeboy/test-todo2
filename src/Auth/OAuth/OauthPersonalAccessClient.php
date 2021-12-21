<?php
declare(strict_types=1);

namespace Todo\Auth\OAuth;

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth_personal_access_clients", indexes={@ORM\Index(name="client_id_index", columns={"client_id"})})
 */
class OauthPersonalAccessClient
{
    use Timestamps;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    protected int $id;

    /**
     * @ORM\Column(name="client_id", type="integer")
     */
    protected int $clientId;
}

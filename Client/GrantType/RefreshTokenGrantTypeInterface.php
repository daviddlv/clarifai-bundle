<?php

namespace ClarifaiBundle\Client\GrantType;

interface RefreshTokenGrantTypeInterface extends GrantTypeInterface
{
    /**
     * @param string $refreshToken
     */
    public function setRefreshToken($refreshToken);

    /**
     * @return bool
     */
    public function hasRefreshToken();
}

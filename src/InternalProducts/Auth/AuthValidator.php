<?php

declare(strict_types=1);

namespace OWC\OpenPub\InternalProducts\Auth;

use WP_REST_Request;

/**
 * Checks incoming requests for authentication.
 */
class AuthValidator
{
    /**
     * Checks if the request is authenticated.
     */
    public static function validate(WP_REST_Request $request): bool
    {
        $authHeader = $request->get_header('Authorization');

        if (empty($authHeader)) {
            return false;
        }

        [$username, $password] = self::getCredentials($authHeader);

        $user = \wp_authenticate($username, $password);

        if (is_wp_error($user)) {
            return false;
        }

        return true;
    }

    /**
     * Gets username, password from a base64 encoded string
     */
    private static function getCredentials(string $authHeader): array
    {
        $raw = base64_decode(str_replace('Basic ', '', $authHeader));

        return explode(':', $raw);
    }
}

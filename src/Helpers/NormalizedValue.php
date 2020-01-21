<?php
/**
 * NormalizedValue
 *
 * @author edgebal
 */

namespace Minds\UnleashClient\Helpers;

use lastguest\Murmur;

class NormalizedValue
{
    /**
     * Normalizes a value using Murmur3 algorithm hash and a normalizer modulus.
     * Returns a value from $min (default 1) to $normalizer (default 100) if
     * ID is truthy, if not it returns $min - 1 (default 0).
     * @param string $id
     * @param string $groupId
     * @param int $normalizer
     * @param int $min
     * @return int
     */
    public function build(string $id, string $groupId, int $normalizer = 100, int $min = 1): int
    {
        if (!$id) {
            return $min - 1;
        }

        return (Murmur::hash3_int("{$id}:{$groupId}") % $normalizer) + $min;
    }

    /**
     * Returns a random value from $min (default 1) to $normalizer (default 100).
     * @param int $normalizer
     * @param int $min
     * @return int
     */
    public function random($normalizer = 100, $min = 1): int
    {
        return mt_rand($min, $normalizer);
    }
}

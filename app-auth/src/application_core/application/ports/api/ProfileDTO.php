<?php
declare(strict_types=1);

namespace toubilib\core\application\ports\api;

class ProfileDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly int $role
    ) {
    }
}

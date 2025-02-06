<?php
declare(strict_types=1);

namespace App\Services\User\Enums;

enum RolesEnum: string
{
    case ROLE_EDITOR = 'ROLE_EDITOR';
    case ROLE_ADMIN = 'ROLE_ADMIN';
}

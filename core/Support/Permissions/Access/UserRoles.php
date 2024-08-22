<?php

namespace Core\Support\Permissions\Access;

class UserRoles
{
    public const int ADMIN = GeneralPermissions::READ | GeneralPermissions::WRITE | GeneralPermissions::DELETE | GeneralPermissions::EXECUTE;
    public const int EDITOR = GeneralPermissions::READ | GeneralPermissions::WRITE;
    public const int VIEWER = GeneralPermissions::READ;
}

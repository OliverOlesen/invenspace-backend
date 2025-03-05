<?php

namespace App\Model;

class PasswordResetModel extends AbstractModel
{
    public string $token = '';
    public string $password = '';
}
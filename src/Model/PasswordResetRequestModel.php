<?php

namespace App\Model;

use App\Model\AbstractModel;

class PasswordResetRequestModel extends AbstractModel
{
    public string $email = '';
}
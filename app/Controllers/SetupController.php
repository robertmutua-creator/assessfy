<?php

namespace App\Controllers;

use App\Core\Migrations;
use App\Model\Users;

class SetupController
{
    public function tables(): void
    {
        (new Migrations());
    }
    // public function seed(): void
    // {
    //     (new Users())->create([
    //         'username' => 'admin',
    //         'role' => 'admin',
    //         'password' => password_hash('123', PASSWORD_DEFAULT),
    //     ]);
    // }
}

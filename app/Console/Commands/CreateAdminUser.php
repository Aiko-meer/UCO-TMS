<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

/**
 * Creates an admin account from the terminal.
 * Run it with:  php artisan user:create-admin
 *
 * Use it once to make the first admin. After that, log in and add other
 * people from the Account > User page.
 */
class CreateAdminUser extends Command
{
    protected $signature = 'user:create-admin';

    protected $description = 'Create an admin user (use this for the very first login)';

    public function handle(): int
    {
        $data = [
            'employee_id' => $this->ask('Employee ID'),
            'fullname'    => $this->ask('Full name'),
            'email'       => $this->ask('Email'),
            'password'    => $this->secret('Password (8+ characters, upper and lower case, number, symbol)'),
        ];

        // Same rules the User page uses, so the admin password is strong too
        $validator = Validator::make($data, [
            'employee_id' => ['required', 'string', 'unique:users,employee_id'],
            'fullname'    => ['required', 'string', 'unique:users,fullname'],
            'email'       => ['required', 'email', 'unique:users,email'],
            'password'    => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        // The User model hashes the password automatically (see casts() in User.php)
        User::create([
            'employee_id' => $data['employee_id'],
            'fullname'    => $data['fullname'],
            'email'       => $data['email'],
            'password'    => $data['password'],
            'user_type'   => 'admin',
            'verified'    => 0,
        ]);

        $this->info("Admin created. You can now log in with {$data['email']}.");

        return self::SUCCESS;
    }
}

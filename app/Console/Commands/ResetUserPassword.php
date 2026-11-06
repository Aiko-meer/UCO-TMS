<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

/**
 * Changes ONLY the password of an existing user.
 * Run it with:  php artisan user:reset-password
 *          or:  php artisan user:reset-password someone@example.com
 */
class ResetUserPassword extends Command
{
    protected $signature = 'user:reset-password {email? : Email of the user}';

    protected $description = 'Change the password of an existing user (nothing else is changed)';

    public function handle(): int
    {
        $email = $this->argument('email') ?: $this->ask('Email of the user');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("No user found with the email {$email}.");

            return self::FAILURE;
        }

        $password = $this->secret('New password (8+ characters, upper and lower case, number, symbol)');
        $confirm  = $this->secret('Type the new password again');

        $validator = Validator::make(
            ['password' => $password, 'password_confirmation' => $confirm],
            ['password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()]]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        // The User model hashes the password automatically (see casts() in User.php)
        $user->password = $password;
        $user->save();

        $this->info("Password updated for {$user->fullname} ({$user->email}).");

        return self::SUCCESS;
    }
}

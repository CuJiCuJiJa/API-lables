<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Attempting;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WordPressPasswordUpdate
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Attempting  $event
     * @return void
     */
    public function handle(Attempting $event)
    {

        $this->check($event->credentials['password'], \App\User::where('email', $event->credentials['email'])->first()->password ?? 'not found');
    }

    public function check($value, $hashedValue, array $options = [])
    {
        if ($this->needsRehash($hashedValue)) {

            if ($this->user_check_password($value, $hashedValue)) {

                $newHashedValue = (new \Illuminate\Hashing\BcryptHasher)->make($value, $options);
                \Illuminate\Support\Facades\DB::update('UPDATE users SET `password` = "' . $newHashedValue . '" WHERE `password` = "' . $hashedValue . '"');
                $hashedValue = $newHashedValue;
            }
        }
    }

    public function needsRehash($hashedValue, array $options = [])
    {
        return substr($hashedValue, 0, 4) != '$2y;';
    }

    // WP PASSWORD FUNCTIONS
    function user_check_password($password, $stored_hash)
    {
        // $hash = md5($password);
        if (WpPassword::check($password, $stored_hash)) {
            // Password success!
            return true;
        } else {
            // Password failed :(
            return false;
        }

    }
}



<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use App\Models\Barber;
use Illuminate\Support\Facades\Hash;

class MakeFilamentAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-filament-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $name = $this->ask('Name');
        $email = $this->ask('Email');
        $password = $this->secret('Password');

        $role = $this->choice('Role', ['owner', 'staff'], 0);

        $barberId = null;
        if($role === 'staff'){
            $barbers = Barber::pluck('id', 'name')->toArray();

            if(empty($barbers)) {
                $this->error('No barbers available. Please create a barber first.');
                return 1;
            }

            $selectedName = $this->choice('Assign to which barber?', array_keys($barbers));
            $barberId = $barbers[$selectedName];
        }

        $admin = Admin::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'barber_id' => $barberId,
        ]);

        $admin->assignRole($role);

        $this->info("Admin user with role [{$role}] created successfully!");
    }
}

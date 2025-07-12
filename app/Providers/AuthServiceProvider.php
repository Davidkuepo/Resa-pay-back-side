<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
// use Illuminate\Support\Facades\Gate; 
use Laravel\Passport\Passport; 

class AuthServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
         $this->registerPolicies(); 
         Passport::routes(); 
    }
}
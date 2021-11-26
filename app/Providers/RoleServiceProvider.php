<?php

namespace App\Providers;

use App\Models\Role;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class RoleServiceProvider extends ServiceProvider
{

    public function register()
    {
        //
    }

    public function boot()
    {
        //Blade directives
        Blade::directive('role', function ($role) {
            return "<?php if(auth()->check() && auth()->user()->hasRole({$role})) : ?>"; //return this if statement inside php tag
        });

        Blade::directive('endrole', function ($role) {
             return "<?php endif; ?>"; //return this endif statement inside php tag
        });

    }
}
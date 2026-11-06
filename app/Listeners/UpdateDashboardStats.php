<?php

namespace App\Listeners;

use App\Http\Controllers\Admin\AdminDashboardController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateDashboardStats
{
    public function handle($event)
    {
        $controller = new AdminDashboardController();
        $controller->getStats();
    }
} 

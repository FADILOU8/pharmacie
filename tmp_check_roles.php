<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

foreach (User::where('role', 'preparateur')->get() as $user) {
    echo "USER: {$user->id} {$user->name} {$user->role}\n";
    echo 'PHARMACY: ' . ($user->pharmacy ? $user->pharmacy->id : 'null') . "\n";
}

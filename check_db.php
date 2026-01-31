<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Poll;
use Illuminate\Support\Facades\DB;

try {
    $count = Poll::count();
    $activeCount = Poll::where('status', 'active')->count();
    echo "Total Polls: $count\n";
    echo "Active Polls: $activeCount\n";
    
    if ($activeCount > 0) {
        $first = Poll::where('status', 'active')->first();
        echo "Example Poll: " . $first->question . " (" . $first->status . ")\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

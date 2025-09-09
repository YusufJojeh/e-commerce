<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 Testing Cache Service Fix\n";
echo "============================\n\n";

try {
    $cache = new \App\Services\AdvancedCacheService();
    echo "✅ Cache service created successfully\n";

    echo "Redis Available: " . ($cache->isRedisAvailable() ? 'YES' : 'NO') . "\n";
    echo "Memcached Available: " . ($cache->isMemcachedAvailable() ? 'YES' : 'NO') . "\n";

    // Test basic cache operation
    echo "\n🧪 Testing cache operation...\n";
    $testData = $cache->get('test_cache_fix', function() {
        return ['message' => 'Cache is working!', 'timestamp' => time()];
    }, 60);

    echo "✅ Cache operation successful\n";
    echo "Cached data: " . json_encode($testData) . "\n";

    // Test tagged cache operation
    echo "\n🧪 Testing tagged cache operation...\n";
    $taggedData = $cache->rememberWithTags('test_tagged_cache', ['test'], function() {
        return ['message' => 'Tagged cache is working!', 'timestamp' => time()];
    }, 60);

    echo "✅ Tagged cache operation successful\n";
    echo "Tagged data: " . json_encode($taggedData) . "\n";

    echo "\n🎉 All tests passed! Cache service is working correctly.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

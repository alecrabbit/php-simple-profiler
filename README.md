# Simple profiler

PHP Simple profiler 

Profiler

Counter

Timer

### Usage

```php
require_once __DIR__ . '/../vendor/autoload.php';

$profiler = new \AlecRabbit\Profiler\Profiler();

$profiler->counter('new')->bump();
$profiler->counter()->bump();
$profiler->counter()->setStep(2);
$profiler->counter()->bump();
var_export($profiler->report());
echo PHP_EOL;

$profiler->timer()->start();
$profiler->timer()->check();
usleep(100);
$profiler->timer()->check();
usleep(510);

$profiler->timer('new')->start();
$profiler->timer('new')->check();
usleep(100);
$profiler->timer('new')->check();
usleep(510);

var_export($profiler->report(null, true));
echo PHP_EOL;
var_export($profiler->report(true));
echo PHP_EOL;
var_export($profiler->report(true, true));
echo PHP_EOL;
```
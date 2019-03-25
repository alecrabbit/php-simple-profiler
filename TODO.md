- [ ] reorganize project structure
- [ ] add more info to show by `stat()` method
- [ ] improve `Benchmark::class` test 
- [ ] consider adding `stopwatch()` function

-----

- [x] add checks if `symfony/console` is installed for `BenchmarkSymfonyProgressBar::class` (0.6.0)
- [x] `Benchmark::class` uses `HRTimer` in php 7.3 and above (0.6.0)
- [x] add `BenchmarkReportTest:class` (0.6.0)
- [x] improve `Timer::class` test (0.6.0)
- [x] consider implementing `HRTimer::class` (using PHP^7.3 function `hrtime()`) (0.6.0)
- [x] custom formatters as parameters (0.6.0)
- [x] separate `Counter` in two classes - `SimpleCounter` and `ExtendedCounter` (0.6.0)

- [x] Delete method `getReport()` (0.6.0)

- [x] Method `getReport()` deprecated  (0.5.1)

- [x] show 'All returns are equal: _result_' instead of actual results for each function (0.5.0)
- [x] rename method `progressBar()` to `showProgressBy()` (0.5.0)
- [x] throw an exception when `Benchmark::getReport()` called before `run()` (0.5.0)

- [x] improve `ProfileReportFormatter` (0.4.1)
- [x] improve `BenchmarkReportFormatter` (0.4.1)
- [x] add `BenchmarkFunctionFormatter` (0.4.1)

- [x] add memory usage to `Benchmark` class (0.3.3)

- [x] improve `Benchmark` tests (0.4.0)
- [x] improve `Benchmark` tests (0.3.1)
- [x] add classes with embedded progress bars (0.3.0)


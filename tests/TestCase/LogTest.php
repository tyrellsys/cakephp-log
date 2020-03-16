<?php
declare(strict_types=1);

namespace Tyrellsys\CakePHPLog\Test\TestCase;

use Tyrellsys\CakePHPLog\Log;
use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;

class LogTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Log::reset();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Log::reset();
    }

    protected function _resetLogConfig()
    {
        Log::setConfig('debug', [
            'engine' => 'File',
            'path' => LOGS,
            'levels' => ['notice', 'info', 'debug'],
            'file' => 'debug',
        ]);
        Log::setConfig('error', [
            'engine' => 'File',
            'path' => LOGS,
            'levels' => ['warning', 'error', 'critical', 'alert', 'emergency'],
            'file' => 'error',
        ]);
    }

    protected function _deleteLogs()
    {
        if (file_exists(LOGS . 'error.log')) {
            unlink(LOGS . 'error.log');
        }
        if (file_exists(LOGS . 'debug.log')) {
            unlink(LOGS . 'debug.log');
        }
    }

    /**
     * Test write method
     * @return void
     */
    public function testWrite()
    {
        $this->_resetLogConfig();

        $this->_deleteLogs();
        $result = Log::write(LOG_WARNING, 'Test warning');
        $this->assertTrue($result);
        $this->assertFileExists(LOGS . 'error.log');

        $this->_deleteLogs();
        Log::write(LOG_WARNING, 'Test warning 1');
        Log::write(LOG_WARNING, ['message' => 'Test warning 2', ['array' => [1, 2, 3]]]);
        Log::write(LOG_WARNING, 'Test warning 3');
        $result = file_get_contents(LOGS . 'error.log');
        $this->assertRegExp('/^2[0-9]{3}-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+ Warning: \[.+?\]:ROOT\/.+?\/TestCase\/LogTest.php\(\d+\)\[\d+\]: Test warning 1/', $result);
        $this->assertRegExp('/\[message\] => Test warning 2\n    \[0\] => Array\n/', $result);
        $this->assertRegExp('/2[0-9]{3}-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+ Warning: \[.+?\]:ROOT\/.+?\/TestCase\/LogTest.php\(\d+\)\[\d+\]: Test warning 3$/', $result);

        $this->_deleteLogs();
        Log::emergency('Test Emergency');
        Log::alert('Test Alert');
        Log::critical('Test Critical');
        Log::error('Test Error');
        Log::warning('Test Warning');
        Log::notice('Test Notice');
        Log::info('Test Info');
        Log::debug('Test Debug');
        $errorResult = file_get_contents(LOGS . 'error.log');
        $debugResult = file_get_contents(LOGS . 'debug.log');

        $this->assertRegExp('/Emergency: .+?: Test Emergency/', $errorResult);
        $this->assertRegExp('/Alert: .+?: Test Alert/', $errorResult);
        $this->assertRegExp('/Critical: .+?: Test Critical/', $errorResult);
        $this->assertRegExp('/Error: .+?: Test Error/', $errorResult);
        $this->assertRegExp('/Warning: .+?: Test Warning/', $errorResult);
        $this->assertRegExp('/Notice: .+?: Test Notice/', $debugResult);
        $this->assertRegExp('/Info: .+?: Test Info/', $debugResult);
        $this->assertRegExp('/Debug: .+?: Test Debug/', $debugResult);
    }
}

<?php
declare(strict_types=1);

namespace Tyrellsys\CakePHPLog\Test\TestCase;

use Tyrellsys\CakePHPLog\LogTrait;
use Cake\Log\Log;
use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;

class LogTraitTest extends TestCase
{
    /**
     * Don't do it, but it's easy to test.
     */
    use LogTrait;

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

        $result = $this->log('Test warning', LOG_WARNING);
        $this->assertTrue($result);
        $this->assertFileExists(LOGS . 'error.log');
        $this->_deleteLogs();

        $this->log('Test warning 1', LOG_WARNING);
        $this->log(['message' => 'Test warning 2', ['array' => [1, 2, 3]]], LOG_WARNING);
        $this->log('Test warning 3', LOG_WARNING);
        $result = file_get_contents(LOGS . 'error.log');
        $this->assertRegExp('/^2[0-9]{3}-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+ Warning: \[.+?\]:ROOT\/.+?\/TestCase\/LogTraitTest.php\(\d+\)\[\d+\]: Test warning 1/', $result);
        $this->assertRegExp('/\[message\] => Test warning 2\n    \[0\] => Array\n/', $result);
        $this->assertRegExp('/2[0-9]{3}-[0-9]+-[0-9]+ [0-9]+:[0-9]+:[0-9]+ Warning: \[.+?\]:ROOT\/.+?\/TestCase\/LogTraitTest.php\(\d+\)\[\d+\]: Test warning 3$/', $result);
        $this->_deleteLogs();
    }
}

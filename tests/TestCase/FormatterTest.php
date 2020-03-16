<?php
declare(strict_types=1);

namespace Tyrellsys\CakePHPLog\Test\TestCase;

use Tyrellsys\CakePHPLog\Formatter;
use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;

class FormatterTest extends TestCase
{
    /**
     * dataProvider for testGetMessage method
     * @return array
     */
    public function dataProviderGetMessage()
    {
        return [
            [
                [
                    'data' => 'message',
/**
[7262b84fb6f1]:ROOT/tests/TestCase/Log/FormatterTest.php(60)[560]: message
*/
                    'expected' => ': message',
                ],
            ],
            [
                [
                    'data' => new Entity(['test' => 'test1']),
/**
[7262b84fb6f1]:ROOT/tests/TestCase/Log/FormatterTest.php(60)[560]: Array
(
    [test] => test1
)
*/
                    'expected' => ": Array\n(\n    [test] => test1\n",
                ],
            ],
            [
                [
                    'data' => [
                        'message' => 'message',
                        'array' => [
                            'item1',
                            'item2',
                        ],
                        'Entity' => new Entity(['test' => 'test2']),
                    ],
/**
[7262b84fb6f1]:ROOT/tests/TestCase/Log/FormatterTest.php(60)[560]: Array
(
    [message] => message
    [array] => Array
(
    [0] => item1
    [1] => item2
)
    [Entity] => {
    "test": "test2"
}
)
*/
                    'expected' => [
                        ": Array\n",
                        "[message] => message\n",
                        "[array] => Array\n(\n    [0] => item1\n    [1] => item2\n)\n",
                        "[Entity] => {\n    \"test\": \"test2\"\n}\n",
                    ],
                ],
            ],
        ];
    }

    /**
     * Test getMessage method
     * @dataProvider dataProviderGetMessage
     * @return void
     */
    public function testGetMessageString($data)
    {
        $line = __LINE__ + 1; // caller line no
        $actual = Formatter::getMessage($data['data']);
        // contain caller filename
        $this->assertStringContainsString(substr(__FILE__, -30), $actual, $actual);
        // contain caller line no
        $this->assertStringContainsString('(' . $line . ')', $actual, $actual);
        // contain (string) message
        foreach ((array)$data['expected'] as $expected) {
            $this->assertStringContainsString($expected, $actual, $expected);
        }
    }
}

# CakePHPLog plugin for CakePHP

## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

```
composer require tyrellsys/cakephp-log
```

## Usage

### Formatter::getMessage($message)

convert `$message` to string.

```
[hostname]:/path/to/filename(line no)[pid]: extracted $message
```


```
...
use Tyrellsys\CakePHPLog\Formatter;
...
    $message = string / array / Cake\Datasource\EntityInterface / Object

    Log::write(LOG_WARNING, Formatter::getMessage($message));
    Log::info(Formatter::getMessage($message));
    $this->log(Formatter::getMessage($message), LOG_WARNING);

```

## Override CakePHP4 Cake\Log\Log and Cake\Log\LogTrait

https://book.cakephp.org/4/en/appendices/4-0-migration-guide.html#log
> Logging related methods like Cake\Log\LogTrait::log(), Cake\Log\Log::write() etc. now only accept string for $message argument. This change was necessary to align the API with PSR-3 standard.

*You understand and use the feature to ignore the change.*


Tyrellsys\CakePHP\Log
```
...
use Tyrellsys\CakePHPLog\Log;
...
        Log::write(LOG_WARNING, $mixed); // mixed message with Tyrellsys/CakePHPLog/Formatter::getMessage()
...
```

Tyrellsys\CakePHPLog\LogTrait
```
...
use Tyrellsys\CakePHPLog\LogTrait;
...
class XXX
{
    use Tyrellsys\CakePHPLog/LogTrait;
...
        $this->log($mixed, LOG_WARNING); // mixed message with Tyrellsys/CakePHPLog/Formatter::getMessage()
...
}
...
```

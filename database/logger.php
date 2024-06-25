<?php
class Logger {

    public function log($level, $message, $color = null) {
        $log_entry = sprintf("[%s][%s] %s\n", date('Y-m-d H:i:s'), $level, $message);

        if (PHP_SAPI === 'cli' && $color !== null) {
            switch ($color) {
                case 'yellow':
                    $log_entry = "$log_entry";
                    break;
                case 'green':
                    $log_entry = "$log_entry";
                    break;
                case 'red':
                    $log_entry = "$log_entry";
                    break;
                default:
                    break;
            }
        }

        file_put_contents(__DIR__ . '/../logs/app.log', $log_entry, FILE_APPEND);

    }

    public function warning($message) {
        $this->log('WARNING', $message, 'yellow');
    }

    public function success($message) {
        $this->log('SUCCESS', $message, 'green');
    }

    public function error($message) {
        $this->log('ERROR', $message, 'red');
    }
}

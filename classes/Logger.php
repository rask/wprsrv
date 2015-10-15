<?php

namespace Wprsrv;

use Psr\Log\LoggerInterface;

class Logger implements LoggerInterface
{
    /**
     * Log file path.
     *
     * @access protected
     * @var String
     */
    protected $logFile;

    /**
     * Logging mode/env.
     *
     * @var String
     */
    protected $mode;

    /**
     * Constructor.
     *
     * @param String $logFile Path to log file.
     *
     * @return void
     */
    public function __construct($logSettings)
    {
        $this->logSettings = $logSettings;

        $this->validateLogFile();
        $this->determineMode();
    }

    /**
     * Validate the log file existance and setup.
     *
     * @since 0.1.0
     * @access protected
     * @return void
     */
    protected function validateLogFile()
    {
        // Log file path.
        $logFile = $this->logSettings['log_file'];

        if (!isset($this->logSettings['log_max_size'])) {
            $this->logSettings['log_max_size'] = 1024*1024*1024;
        }

        // Log file maximum filesize. Use ~10MB if not set.
        $logMaxSize = $this->logSettings['log_max_size'] ? $this->logSettings['log_max_size'] : 1024*1024*1024;

        $logDir = dirname($logFile);
        $logRawName = basename($logFile, '.log');

        if (!file_exists($logFile)) {
            // Log file does not exist, create it.
            $logDir = dirname($logFile);

            @mkdir($logDir, 0755, true);
            file_put_contents($logFile, '');
        } else {
            // Log file size has exceeded limit. Create new and temporarily store the old one.
            $logFileSize = filesize($logFile);

            if ($logFileSize > $logMaxSize) {
                $new = $logDir . RDS . $logRawName . '1' . '.log';

                @unlink($new);

                rename($logFile, $new);
                file_put_contents($logFile, '');
            }
        }

        $this->logFile = $logFile;
    }

    /**
     * Determine the mode we're logging in.
     *
     * @since 0.1.0
     * @access protected
     * @return void
     */
    protected function determineMode()
    {
        if (defined('DOING_AJAX') && DOING_AJAX) {
            $this->mode = 'ajax';
        } elseif (defined('DOING_CRON') && DOING_CRON) {
            $this->mode = 'cron';
        } elseif (php_sapi_name() === 'cli') {
            $this->mode = 'cli';
        } else {
            $this->mode = 'www';
        }

        $longestLen = 4;

        while (strlen($this->mode) < $longestLen) {
            $this->mode .= ' ';
        }

        $this->mode = strtoupper($this->mode);
    }

    /**
     * Write to the log file.
     *
     * @access protected
     *
     * @param String $msg Message to log.
     *
     * @return void
     */
    protected function writeToLog($msg)
    {
        file_put_contents($this->logFile, $msg . "\n", FILE_APPEND);
    }

    /**
     * Parse message context.
     *
     * @param String $msg
     * @param String[] $ctx
     *
     * @return String
     */
    protected function parseContext($msg, $ctx)
    {
        if (empty($ctx)) {
            return $msg;
        }

        if (strpos($msg, '{') === false || strpos($msg, '}') === false) {
            return $msg;
        }

        foreach ($ctx as $key => $value) {
            $msg = str_replace('{' . $key . '}', $value, $msg);
        }

        return $msg;
    }

    /**
     * Generate a label for the log level.
     *
     * @param String $type
     *
     * @return String
     */
    protected function generateTypeLabel($type)
    {
        $longest = 'emergency';
        $longestLen = strlen($longest);

        $label = '[';

        $type = strtoupper($type);

        while (strlen($type) < $longestLen) {
            $type .= ' ';
        }

        $label .= $type;
        $label .= ']';

        return $label;
    }

    /**
     * Generate a log line.
     *
     * @param String $type
     * @param String $msg
     * @param String[] $ctx
     *
     * @return String
     */
    protected function generateLogMessage($type, $msg, $ctx)
    {
        $timestamp = date('Y-m-d H:i:s');
        $typeLabel = $this->generateTypeLabel($type);
        $modeLabel = sprintf('[%s]', $this->mode);
        $msg = $this->parseContext($msg, $ctx);

        $logMessage = sprintf('%s %s %s %s', $timestamp, $typeLabel, $modeLabel, $msg);

        return $logMessage;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function emergency($message, array $context = array())
    {
        $this->writeToLog($this->generateLogMessage('emergency', $message, $context));
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function alert($message, array $context = array())
    {
        $this->writeToLog($this->generateLogMessage('alert', $message, $context));
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function critical($message, array $context = array())
    {
        $this->writeToLog($this->generateLogMessage('critical', $message, $context));
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function error($message, array $context = array())
    {
        $this->writeToLog($this->generateLogMessage('error', $message, $context));
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function warning($message, array $context = array())
    {
        $this->writeToLog($this->generateLogMessage('warning', $message, $context));
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function notice($message, array $context = array())
    {
        $this->writeToLog($this->generateLogMessage('notice', $message, $context));
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function info($message, array $context = array())
    {
        $this->writeToLog($this->generateLogMessage('info', $message, $context));
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function debug($message, array $context = array())
    {
        if (WP_DEBUG) {
            $this->writeToLog($this->generateLogMessage('debug', $message, $context));
        }
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        $this->{$level}($message, $context);
    }
}

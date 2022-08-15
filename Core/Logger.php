<?php

namespace FATCHIP\ObjectCodeK3\Core;

use OxidEsales\Eshop\Core\Registry;

/**
 * copy from oxideshop/source/modules/b2b/PsrLogger/Core/Logger.php
 */
class Logger implements \Psr\Log\LoggerInterface
{

    /**
     * Log file name
     *
     * @var string
     */
    protected string $logFileName = 'fcobjectcodek3.log';

    /**
     * logger object
     *
     * @var \Monolog\Logger
     */
    private $psrLogger;

    /**
     * Create logger instance
     */
    public function __construct()
    {
        $this->psrLogger = new \Monolog\Logger('FCK3');
    }

    /**
     * Push a handler to the list of handlers
     *
     * @param \Monolog\Handler\HandlerInterface $handler
     */
    public function pushHandler(\Monolog\Handler\HandlerInterface $handler)
    {
        $this->psrLogger->pushHandler($handler);
    }

    /**
     * Returns handlers in list
     *
     * @return \Monolog\Handler\HandlerInterface[]
     */
    public function getHandlers()
    {
        return $this->psrLogger->getHandlers();
    }

    /**
     * emergency
     *
     * @param string $message
     * @param array $context
     */
    public function emergency($message, array $context = array())
    {
        $this->initDefaultSettings();
        $this->psrLogger->emergency($message, $context);
    }

    /**
     * alert
     *
     * @param string $message
     * @param array $context
     */
    public function alert($message, array $context = array())
    {
        $this->initDefaultSettings();
        $this->psrLogger->alert($message, $context);
    }

    /**
     * critical
     *
     * @param string $message
     * @param array $context
     */
    public function critical($message, array $context = array())
    {
        $this->initDefaultSettings();
        $this->psrLogger->critical($message, $context);
    }

    /**
     * error
     *
     * @param string $message
     * @param array $context
     */
    public function error($message, array $context = array())
    {
        $this->initDefaultSettings();
        $this->psrLogger->error($message, $context);
    }

    /**
     * warning
     *
     * @param string $message
     * @param array $context
     */
    public function warning($message, array $context = array())
    {
        $this->initDefaultSettings();
        $this->psrLogger->warning($message, $context);
    }

    /**
     * notice
     *
     * @param string $message
     * @param array $context
     */
    public function notice($message, array $context = array())
    {
        $this->initDefaultSettings();
        $this->psrLogger->notice($message, $context);
    }

    /**
     * info
     *
     * @param string $message
     * @param array $context
     */
    public function info($message, array $context = array())
    {
        $this->initDefaultSettings();
        $this->psrLogger->info($message, $context);
    }

    /**
     * debug
     *
     * @param string $message
     * @param array $context
     */
    public function debug($message, array $context = array())
    {
        $this->initDefaultSettings();
        $this->psrLogger->debug($message, $context);
    }

    /**
     * log with level
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = array())
    {
        $this->initDefaultSettings();
        $this->psrLogger->log($level, $message, $context);
    }

    /**
     * init handler with default settings if not already exists
     */
    private function initDefaultSettings()
    {
        if (empty($this->psrLogger->getHandlers())) {
            $this->initDefaultHandler();
        }
    }

    /**
     * init default handler
     */
    private function initDefaultHandler()
    {
        $defaultLogFile = \OxidEsales\Eshop\Core\Registry::getConfig()->getLogsDir() . DIRECTORY_SEPARATOR . $this->logFileName;
        $streamHandler = new \Monolog\Handler\StreamHandler($defaultLogFile);
        $this->psrLogger->pushHandler($streamHandler);
    }
}
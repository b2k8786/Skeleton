<?php

/**
 * class for log data in bit skeleton.
 *
 * @author cts
 * @property Logger $instance
 */
class Logger
{
    private $logPath;
    private static $instance;
    private $db;
    function __construct()
    {
        if (log_storage == 'FILE')
        {
            $this->logPath = LOG_PATH . 'LOG_' . date("d_M_y") . '.log';
        }
        else if (log_storage == 'DB')
        {
            $this->db = Dbase::getInstance();
            $tableName = 'log_' . date('Ymd');
            $this->db->query("CREATE TABLE IF NOT EXISTS `$tableName` (
                    `logID` int(11) NOT NULL AUTO_INCREMENT,
                    `title` varchar(20) NOT NULL,
                    `logData` varchar(255) NOT NULL,
                    `createdAt` timestamp NOT NULL DEFAULT current_timestamp,
                    PRIMARY KEY (`logID`)                    
              )")->run();
        }
    }
    static public function getInstance()
    {
        if (!is_object(self::$instance))
        {
            self::$instance = new Logger();
        }
        return self::$instance;
    }
    /**
     * 
     * @param String log
     * @param String log type
     * @example ACCESS(default) | ERROR | FETCH | EDIT| ADD | LOGIN | LOGOUT
     */
    function log($logData, $type = "ACCESS", $ip = null)
    {
        if (log_storage == 'FILE')
        {
            $file = fopen($this->logPath, "a");
            if (!empty($ip))
            {
                $logData = date("h:i:s") . "  | $type | $ip | " . $logData . "\n";
            }
            else
            {
                $logData = date("h:i:s") . "  | $type " . $logData . "\n";
            }
            fwrite($file, $logData);
        }
        else if (log_storage == 'DB')
        {
            if (!empty($ip))
            {
                $logData = "$ip | " . $logData;
            }
            $tableName = 'log_' . date('Ymd');
            $this->db->insert($tableName, [
                'title' => $type,
                'logData' => $logData
            ])->run();
        }
    }
    function getLogs($date = null)
    {
        if (!empty($date))
        {
            if (log_storage == 'FILE')
            {
                $logFile = fopen(LOG_PATH . 'LOG_' . date("d_M_y", strtotime($date)) . '.log', "r");
                $logs = fread($logFile, filesize(LOG_PATH . 'LOG_' . date("d_M_y", strtotime($date)) . '.log'));
                return $logs;
            }
            else if (log_storage == 'DB')
            {
                $tableName = 'log_' . date("d_M_y", strtotime($date));
                $logs = $this->db->select($tableName)->fetchAssocAll();
                return $logs;
            }
        }
    }
}
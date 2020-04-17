<?php

namespace ChrisComposer\Redis;

use Redis;

class RedisServer
{
    protected static $config = 'default';

    public function __construct()
    {

    }

    public static function connect($config = null, array $option = []) : Redis
    {
        $config_info = self::config($config);

        # init 配置
        $host = $config_info['host'];
        $port = $config_info['port'];
        $password = $config_info['password'];
        $database = $config_info['database'];
        $timeout = 0.0;
        $reserved = null;
        $retry_interval = 0;
        $read_timeout = 0.0;

        # update 配置
        $redis = new Redis();
        if ($option) {
            $host = $option['host'] ?? $host;
            $port = $option['port'] ?? $port;
            $password = $option['password'] ?? $password;
            $timeout = $option['timeout'] ?? $timeout;
            $reserved = $option['reserved'] ?? $reserved;
            $retry_interval = $option['retry_interval'] ?? $retry_interval;
            $read_timeout = $option['read_timeout'] ?? $read_timeout;
            $database = $option['database'] ?? $database;
        }

        # 连接
        $redis->connect($host, $port, $timeout, $reserved, $retry_interval, $read_timeout);
        $redis->auth($password);
        $redis->select($database);

        # 返回实例
        return $redis;
    }

    public static function pconnect($config = null, array $option = []) : Redis
    {
        $config_info = self::config($config);

        # init 配置
        $host = $config_info['host'];
        $port = $config_info['port'];
        $password = $config_info['password'];
        $timeout = 0.0;
        $persistent_id = null;
        $retry_interval = 0;
        $read_timeout = 0.0;

        # update 配置
        if ($option) {
            $host = $option['host'] ?? $host;
            $port = $option['port'] ?? $port;
            $password = $option['password'] ?? $password;
            $timeout = $option['timeout'] ?? $timeout;
            $persistent_id = $option['persistent_id'] ?? $persistent_id;
            $retry_interval = $option['retry_interval'] ?? $retry_interval;
            $read_timeout = $option['read_timeout'] ?? $read_timeout;
        }

        # 连接
        $redis = new Redis();
        $redis->pconnect($host, $port, $timeout, $persistent_id, $retry_interval, $read_timeout);
        $redis->auth($password);

        # 返回实例
        return $redis;
    }

    protected static function config($config)
    {
        # 指定配置
        if ($config) {
            $data = config('database.redis.' . $config);
        }
        # 默认配置
        else {
            $data = config('database.redis.' . self::$config);
        }

        return $data;
    }
}
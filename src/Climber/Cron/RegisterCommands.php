<?php

namespace Peak\Climber\Cron;

use Peak\Climber\Application;
use Peak\Climber\Commands\CronAddCommand;
use Peak\Climber\Commands\CronDelCommand;
use Peak\Climber\Commands\CronInstallCommand;
use Peak\Climber\Commands\CronListCommand;
use Peak\Climber\Commands\CronRunCommand;
use Peak\Climber\Commands\CronUpdateCommand;
use Peak\Climber\Cron\Exception\InvalidDatabaseConfigException;

class RegisterCommands
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * Constructor
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        if (!$this->app->conf()->has('crondb') || !is_array($this->app->conf('crondb'))) {
            throw new InvalidDatabaseConfigException(__CLASS__.': configuration [crondb] is missing or invalid.');
        }

        new Bootstrap($this->app->conf('crondb'));

        $this->add([
            CronAddCommand::class,
            CronUpdateCommand::class,
            CronDelCommand::class,
            CronListCommand::class,
            CronRunCommand::class,
            CronInstallCommand::class,
        ]);
    }

    /**
     * Add commands to console application
     *
     * @param array $class
     */
    public function add(array $classes)
    {
        foreach ($classes as $class) {
            $this->app->add($this->app->container()->instantiate($class));
        }
    }
}
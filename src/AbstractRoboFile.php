<?php

/**
 * AppserverIo\RoboTasks\AbstractRoboFile
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/robo-tasks
 * @link      http://www.appserver.io
 */

namespace AppserverIo\RoboTasks;

use Robo\Robo;
use Robo\Tasks;

/**
 * Abstract implementation of a Robo configuration class.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/robo-tasks
 * @link      http://www.appserver.io
 */
abstract class AbstractRoboFile extends Tasks
{

    /**
     * Load the appserver.io base tasks.
     *
     * @var \AppserverIo\RoboTasks\Base\loadTasks
     */
    use Base\loadTasks;

    /**
<<<<<<< HEAD
     * Initializes the default configuration.
     */
    public function __construct()
    {

        // initialize the default configuration
        Robo::config()->setDefault(sprintf('%s.%s', ConfigurationKeys::DIRS, ConfigurationKeys::SRC), sprintf('%s/src', getcwd()));
        Robo::config()->setDefault(sprintf('%s.%s', ConfigurationKeys::DIRS, ConfigurationKeys::VENDOR), sprintf('%s/vendor', getcwd()));
        Robo::config()->setDefault(sprintf('%s.%s', ConfigurationKeys::DIRS, ConfigurationKeys::TARGET), $targetDir = sprintf('%s/target', getcwd()));
        Robo::config()->setDefault(sprintf('%s.%s', ConfigurationKeys::DIRS, ConfigurationKeys::REPORTS), sprintf('%s/reports', $targetDir));
    }

    /**
     * The sync command implementation.
     *
     * @param array $opts The command OptionsHookDispatcher
     *
     * @return void
     */
    public function sync(array $opts = [InputOptionKeys::SRC => null, InputOptionKeys::DEST => null])
    {
        // load the task
        $task = $this->taskSync();

        // set source directory
        if (isset($opts[InputOptionKeys::SRC])) {
            $task->src($opts[InputOptionKeys::SRC]);
        }

        // set target directory
        if (isset($opts[InputOptionKeys::DEST])) {
            $task->dest($opts[InputOptionKeys::DEST]);
        }

        // run the task
        $task->run();
    }

    /**
     * Returns the source directory.
     *
     * @return string The source directory
     */
    protected function getSrcDir()
    {
        return Robo::config()->get(sprintf('%s.%s', ConfigurationKeys::DIRS, ConfigurationKeys::SRC));
    }

    /**
     * Returns the vendor directory.
     *
     * @return string The vendor directory
     */
    protected function getVendorDir()
    {
        return Robo::config()->get(sprintf('%s.%s', ConfigurationKeys::DIRS, ConfigurationKeys::VENDOR));
    }

    /**
     * Returns the reports directory.
     *
     * @return string The reports directory
     */
    protected function getReportsDir()
    {
        return Robo::config()->get(sprintf('%s.%s', ConfigurationKeys::DIRS, ConfigurationKeys::REPORTS));
    }

    /**
     * Returns the target directory.
     *
     * @return string The target directory
     */
    protected function getTargetDir()
    {
        return Robo::config()->get(sprintf('%s.%s', ConfigurationKeys::DIRS, ConfigurationKeys::TARGET));
    }
}

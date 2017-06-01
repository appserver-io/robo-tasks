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
}

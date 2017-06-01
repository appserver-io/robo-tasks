<?php

/**
 * AppserverIo\RoboTasks\Base\loadTasks
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

namespace AppserverIo\RoboTasks\Base;

/**
 * The task loader trait for the base tasks.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/robo-tasks
 * @link      http://www.appserver.io
 */
trait loadTasks
{

    /**
     * Returns the extended watch task.
     *
     * @return AppserverIo\RoboTasks\Base\Watch
     */
    protected function taskWatch()
    {
        return $this->task(Watch::class);
    }

    /**
     * Returns the extended sync task.
     *
     * @return AppserverIo\RoboTasks\Base\Sync
     */
    protected function taskSync()
    {
        return $this->task(Sync::class);
    }
}

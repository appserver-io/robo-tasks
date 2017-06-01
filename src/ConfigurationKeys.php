<?php

/**
 * AppserverIo\RoboTasks\ConfigurationKeys
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

/**
 * The default configuration keys.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/robo-tasks
 * @link      http://www.appserver.io
 */
class ConfigurationKeys
{

    /**
     * The key for the configuration value that contains the directories.
     *
     * @var string
     */
    const DIRS = 'dirs';

    /**
     * The key for the configuration value that contains the value for the source directory.
     *
     * @var string
     */
    const SRC = 'src';

    /**
     * The key for the configuration value that contains the value for the target directory.
     *
     * @var string
     */
    const TARGET = 'target';

    /**
     * The key for the configuration value that contains the value for the vendor directory.
     *
     * @var string
     */
    const VENDOR = 'vendor';

    /**
     * The key for the configuration value that contains the value for the report directory.
     *
     * @var string
     */
    const REPORTS = 'reports';
}

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

use Robo\Tasks;
use AppserverIo\Properties\Properties;
use AppserverIo\Properties\PropertiesUtil;

/**
 * Abstract implementation of a Robo.li configuration class.
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
     * The build properties.
     *
     * @var \AppserverIo\Appserver\PropertiesInterface
     */
    protected $properties = null;

    /**
     * Initialize's the tasks.
     */
    public function __construct()
    {

        // initialize the build properties
        $this->properties = Properties::create();

        // load properties from build.properties file
        if (file_exists($buildProperties = getcwd() . '/build.properties')) {
            $this->properties->mergeProperties(Properties::create()->load($buildProperties));
        }

        // load the default build properties
        if (file_exists($buildDefaultProperties = getcwd() . '/build.default.properties')) {
            $this->properties->mergeProperties(Properties::create()->load($buildDefaultProperties));
        }

        // initialize the default properties
        $this->properties->setProperty(PropertyKeys::BASE_DIR, getcwd());
        $this->properties->setProperty(PropertyKeys::SRC_DIR, '${base.dir}/src');
        $this->properties->setProperty(PropertyKeys::DIST_DIR, '${base.dir}/dist');
        $this->properties->setProperty(PropertyKeys::VENDOR_DIR, '${base.dir}/vendor');
        $this->properties->setProperty(PropertyKeys::TARGET_DIR, '${base.dir}/target');
        $this->properties->setProperty(PropertyKeys::REPORTS_DIR, '${target.dir}/reports');

        // replace the variables in the properties
        PropertiesUtil::singleton()->replaceProperties($this->properties);
    }

    /**
     * Return the base directory.
     *
     * @return string The base directory
     * @see \AppserverIo\RoboTasks\AbstractRoboFile::getProperty()
     */
    protected function getBaseDir()
    {
        return $this->getProperty(PropertyKeys::BASE_DIR);
    }

    /**
     * Return the source directory.
     *
     * @return string The source directory
     * @see \AppserverIo\RoboTasks\AbstractRoboFile::getProperty()
     */
    protected function getSrcDir()
    {
        return $this->getProperty(PropertyKeys::SRC_DIR);
    }

    /**
     * Return the distribution directory.
     *
     * @return string The distribution directory
     * @see \AppserverIo\RoboTasks\AbstractRoboFile::getProperty()
     */
    protected function getDistDir()
    {
        return $this->getProperty(PropertyKeys::DIST_DIR);
    }

    /**
     * Return the target directory.
     *
     * @return string The target directory
     * @see \AppserverIo\RoboTasks\AbstractRoboFile::getProperty()
     */
    protected function getTargetDir()
    {
        return $this->getProperty(PropertyKeys::TARGET_DIR);
    }

    /**
     * Return the vendor directory.
     *
     * @return string The vendor directory
     * @see \AppserverIo\RoboTasks\AbstractRoboFile::getProperty()
     */
    protected function getVendorDir()
    {
        return $this->getProperty(PropertyKeys::VENDOR_DIR);
    }

    /**
     * Return the reports directory.
     *
     * @return string The reports directory
     * @see \AppserverIo\RoboTasks\AbstractRoboFile::getProperty()
     */
    protected function getReportsDir()
    {
        return $this->getProperty(PropertyKeys::REPORTS_DIR);
    }

    /**
     * Searches for the property with the specified key in this property list.
     *
     * @param string $key     Holds the key of the value to return
     * @param string $section Holds a string with the section name to return the key for (only matters if sections is set to TRUE)
     *
     * @return string Holds the value of the passed key
     * @see \AppserverIo\Properties\Properties::getProperty()
     */
    protected function getProperty($key, $section = null)
    {
        return $this->properties->getProperty($key, $section);
    }
}

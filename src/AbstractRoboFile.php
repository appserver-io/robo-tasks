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
     * Initialize the
     */
    public function __construct()
    {

        // load the configuration
        $config = Robo::config();

        // set the default values
        $config->setDefault(ConfigurationKeys::BASE_DIR, $baseDir = getcwd());
        $config->setDefault(ConfigurationKeys::SRC_DIR, $baseDir . DIRECTORY_SEPARATOR . 'src');
        $config->setDefault(ConfigurationKeys::DIST_DIR, $baseDir . DIRECTORY_SEPARATOR . 'dist');
        $config->setDefault(ConfigurationKeys::VENDOR_DIR, $baseDir . DIRECTORY_SEPARATOR . 'vendor');
        $config->setDefault(ConfigurationKeys::TARGET_DIR, $targetDir = $baseDir . DIRECTORY_SEPARATOR . 'target');
        $config->setDefault(ConfigurationKeys::REPORTS_DIR, $targetDir . DIRECTORY_SEPARATOR . 'reports');
    }

    /**
     * Sync's the extension with the Magento 2 sources.
     *
     * @param array $opts Array with commandline options
     *
     * @return void
     */
    public function sync(
        $opts = [
            InputOptionKeys::SRC_DIR => null,
            InputOptionKeys::TARGET_DIR => null
        ]
    ) {

        // initialize src/target directory
        $srcDir = $this->getSrcDir();
        $targetDir = $this->getTargetDir();

        // query whether or not the default source directory has been overwritten
        if ($opts[InputOptionKeys::SRC_DIR]) {
            $srcDir = $opts[InputOptionKeys::SRC_DIR];
        }

        // query whether or not the default target directory has been overwritten
        if ($opts[InputOptionKeys::TARGET_DIR]) {
            $targetDir = $opts[InputOptionKeys::TARGET_DIR];
        }

        // start watching the src directory
        $this->taskWatch()->monitor($srcDir, function(FilesystemEvent $event) use ($srcDir, $targetDir) {
             // load the resource that changed
             $filename = $event->getResource();

             // prepare the target filename
             $targetFilename = $this->prepareTargetFilename($srcDir, $targetDir, $filename);

             // query whether or not it is a file
             if ($filename instanceof FileResource) {
                 // query whether or not the file has to be copied or deleted
                 switch ($event->getType()) {
                     case $event->getType() === FilesystemEvent::DELETE:
                         // remove the target file
                         $this->_remove($targetFilename);
                         break;

                     case $event->getType() === FilesystemEvent::CREATE:
                     case $event->getType() === FilesystemEvent::MODIFY:
                         // if yes, copy it ot the target directory
                         $this->taskFilesystemStack()
                              ->copy($filename, $targetFilename)
                              ->run();
                         break;

                     default:
                         throw new \Exception(
                             sprintf('Found invalid event type %s', $event->getTypeString())
                         );
                 }
             }
         })->run();
    }

    /**
     * Prepare and return the target filename.
     *
     * @param string $srcDir    The source filename
     * @param string $targetDir The relative/absolute target directory
     * @param string $filename  The relative/absolute pathname of the file
     *
     * @return string The prepared target filename
     */
    protected function prepareTargetFilename($srcDir, $targetDir, $filename)
    {
        return sprintf(
            '%s%s',
            realpath($targetDir),
            str_replace(realpath($srcDir), '', $filename)
        );
    }

    /**
     * Return the base directory.
     *
     * @return string The base directory
     */
    protected function getBaseDir()
    {
        return $this->get(ConfigurationKeys::BASE_DIR);
    }

    /**
     * Return the source directory.
     *
     * @return string The source directory
     */
    protected function getSrcDir()
    {
        return $this->get(ConfigurationKeys::SRC_DIR);
    }

    /**
     * Return the distribution directory.
     *
     * @return string The distribution directory
     */
    protected function getDistDir()
    {
        return $this->get(ConfigurationKeys::DIST_DIR);
    }

    /**
     * Return the target directory.
     *
     * @return string The target directory
     */
    protected function getTargetDir()
    {
        return $this->get(ConfigurationKeys::TARGET_DIR);
    }

    /**
     * Return the vendor directory.
     *
     * @return string The vendor directory
     */
    protected function getVendorDir()
    {
        return $this->get(ConfigurationKeys::VENDOR_DIR);
    }

    /**
     * Return the reports directory.
     *
     * @return string The reports directory
     */
    protected function getReportsDir()
    {
        return $this->get(ConfigurationKeys::REPORTS_DIR);
    }

    /**
     * Fetch a configuration value.
     *
     * @param string      $key             Which config item to look up
     * @param string|null $defaultOverride Override usual default value with a different default
     *
     * @return mixed
     */
    protected function get($key, $defaultOverride = null)
    {
        return Robo::config()->get($key, $defaultOverride);
    }
}

<?php

/**
 * AppserverIo\RoboTasks\Base\Sync
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

use Lurker\Resource\FileResource;
use Lurker\Event\FilesystemEvent;
use Robo\Common\BuilderAwareTrait;
use Robo\Contract\BuilderAwareInterface;

/**
 * A task that provides directory synchronization functionality.
 *
 * ```php
 * <?php
 * $this->taskSync()->src('src')->dest('dest')->run();
 * ?>
 * ```
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/robo-tasks
 * @link      http://www.appserver.io
 */
class Sync extends Watch implements BuilderAwareInterface
{

    /**
     * The builder to internally create tasks.
     *
     * @var \Robo\Common\BuilderAwareTrait
     */
    use BuilderAwareTrait;

    /**
     * The source directory.
     *
     * @var array
     */
    protected $src;

    /**
     * The target directory.
     *
     * @var array
     */
    protected $dest;

    /**
     * Set the source directory.
     *
     * @param string $src The source directory
     *
     * @return \AppserverIo\RoboTasks\Base\Sync The task instance
     */
    public function src($src)
    {
        $this->src = $src;
        return $this;
    }

    /**
     * Set the destination directory.
     *
     * @param string $dest The destination directory
     *
     * @return \AppserverIo\RoboTasks\Base\Sync The task instance
     */
    public function dest($dest)
    {
        $this->dest = $dest;
        return $this;
    }

    /**
     * Provides the main synchronize functionality.
     *
     * @param FilesystemEvent $event The event that has to be processed
     *
     * @return void
     * @throws \Exception Is thrown, if a non supported event type will be passed
     */
    public function sync(FilesystemEvent $event)
    {

        // load the resource that changed
        $filename = $event->getResource();

        // prepare the destination filename
        $targetFilename = $this->prepareDestFilename($filename);

        // query whether or not it is a file
        if ($filename instanceof FileResource) {
            // query whether or not the file has to be copied or deleted
            switch ($event->getType()) {
                case $event->getType() === FilesystemEvent::DELETE:
                    // remove the target file
                    $this->collectionBuilder()->taskFilesystemStack()->remove($targetFilename)->run();
                    break;

                case $event->getType() === FilesystemEvent::CREATE:
                case $event->getType() === FilesystemEvent::MODIFY:
                    // if yes, copy it ot the target directory
                    $this->collectionBuilder()->taskFilesystemStack()->copy($filename, $targetFilename)->run();
                    break;

                default:
                    throw new \Exception(
                        sprintf('Found invalid event type %s', $event->getTypeString())
                    );
            }
        }
    }

    /**
     * Invokes the task and its functionality.
     *
     * @return \Robo\Result The tasks result
     * @see \Robo\Contract\TaskInterface::run()
     */
    public function run()
    {

        // add the monitor
        $this->monitor($this->src, array($this, 'sync'));

        // start synchronizing the directories
        return parent::run();
    }

    /**
     * Prepare and return the destination filename.
     *
     * @param string $filename The relative/absolute pathname of the file
     *
     * @return string The prepared destination filename
     */
    protected function prepareDestFilename($filename)
    {
        return sprintf(
            '%s%s',
            realpath($this->dest),
            str_replace(realpath($this->src), '', $filename)
        );
    }
}

<?php

/**
 * AppserverIo\RoboTasks\Base\Watch
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

use Robo\Task\BaseTask;
use Lurker\ResourceWatcher;
use Lurker\Event\FilesystemEvent;

/**
 * Alternative watch task. The difference to the default watch task is, that this task
 * will by default watch also the delete + create events.
 *
 * If only specific events should be watched, use the `events()` method to define them.
 *
 * ```php
 * <?php
 * $this->taskWatch()
 *  ->monitor('composer.json', function() {
 *      $this->taskComposerUpdate()->run();
 * })->monitor('src', function() {
 *      $this->taskExec('phpunit')->run();
 * })->run();
 * ?>
 * ```
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io/robo-tasks
 * @link      http://www.appserver.io
 */
class Watch extends BaseTask
{

    /**
     * The array with the paths that has to be monitored.
     *
     * @var array
     */
    protected $monitor = array();

    /**
     * The event mask to use, by default we watch ALL events.
     *
     * @var integer
     */
    protected $eventMask = FilesystemEvent::ALL;

    /**
     * Set the paths to watch and the callback to execute.
     *
     * @param string|string[] $paths    The paths to watch
     * @param callable        $callable The callback that has to be executed on a change
     *
     * @return \AppserverIo\RoboTasks\Base\Watch The task instance
     * @throws \Exception Is thrown, if the passed paths is nor a string or an array
     */
    public function monitor($paths, callable $callable)
    {

        // convert the paths to an array if necessary
        if (is_string($paths)) {
            $paths = array($paths);
        } elseif (!is_array($paths)) {
            throw new \Exception(sprintf('Passed paths has either to be a string or an array'));
        }

        // append the paths
        $this->monitor[] = array($paths, $callable);
        return $this;
    }

    /**
     * Set the events to watch.
     *
     * @param integer $eventMask The event mask
     *
     * @return \AppserverIo\RoboTasks\Base\Watch The task instance
     */
    public function events($eventMask)
    {
        $this->eventMask = $eventMask;
        return $this;
    }

    /**
     * Invokes the task and its functionality.
     *
     * @return \Robo\Result The tasks result
     * @see \Robo\Contract\TaskInterface::run()
     */
    public function run()
    {

        // query whether or not the lurker library has been installed
        if (!class_exists('Lurker\\ResourceWatcher')) {
            return Result::errorMissingPackage($this, 'ResourceWatcher', 'henrikbjorn/lurker');
        }

        // intialize the watcher
        $watcher = new ResourceWatcher();

        // iterate over the directories that has to be watched
        foreach ($this->monitor as $k => $monitor) {
            // iterate over the directories that has to be watched
            foreach ($monitor[0] as $i => $dir) {
                // watch dir given directory
                $watcher->track("fs.$k.$i", $dir, $this->eventMask);

                // write a log message
                $this->printTaskInfo('Watching {dir} for changes...', ['dir' => $dir]);

                // add the listerner for the directory
                $watcher->addListener("fs.$k.$i", $monitor[1]);
            }
        }

        // start the watcher itself
        $watcher->start();

        // return the result
        return Result::success($this);
    }
}

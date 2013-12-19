<?php

/**
 * http://aeqdev.com/tools/php/atpl/
 * v 1.1
 *
 * Copyright © 2013 Krylosov Maksim <Aequiternus@gmail.com>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace aeqdev;

/**
 * Micro include based template engine.
 *
 * Features:
 * - Extending templates in both directions:
 *   wrapping content with layout or filling layout with content.
 * - Wrapping with another ATPL objects.
 * - Pushing child ATPL objects to render list of them as contents.
 * - Extremly fast: only 2 integers calculated while rendering.
 * - No extra buffering of results, only pure includes used.
 */
class ATPL
{

    private $views = [];
    private $view;
    private $count;

    /**
     * @var self[]
     */
    private $wrappers = [];
    private $wrapper;

    /**
     * @var self[]
     */
    private $children = [];

    /**
     * @var self
     */
    private $renderWith;

    /**
     * Adds view by class or method name.
     *
     * Uses PSR-0 style of converting class name to path,
     * with one addition: method separator "::" converted to directory separator too,
     * and differs in extension: ".phtml" used.
     *
     * @param string $class_or_method __CLASS__ or __METHOD__.
     * @return \aeqdev\ATPL Current object.
     */
    public function addView($class_or_method)
    {
        return $this->addViewFile($this->getViewPath($class_or_method));
    }

    /**
     * Gets path for view file by class or method name.
     *
     * Uses PSR-0 style of converting class name to path,
     * with one addition: method separator "::" converted to directory separator too,
     * and differs in extension: ".phtml" used.
     *
     * @param string $class_or_method __CLASS__ or __METHOD__.
     * @return string Path to view file.
     */
    public function getViewPath($class_or_method)
    {
        return preg_replace('/\\\|::|_(?!.*\\\)/', DIRECTORY_SEPARATOR, $class_or_method) . '.phtml';
    }

    /**
     * Adds view by file name.
     *
     * @param string $path View file path.
     * @return \aeqdev\ATPL Current object.
     */
    public function addViewFile($path)
    {
        $this->views [] = $path;
        return $this;
    }

    /**
     * Adds wrapper to the stack.
     *
     * @param \aeqdev\ATPL $wrappers Wrapper.
     * @return \aeqdev\ATPL Current object.
     */
    public function wrap(self $wrappers)
    {
        $this->wrappers [] = $wrappers;
        return $this;
    }

    /**
     * Adds child.
     *
     * @param \aeqdev\ATPL $child Child.
     * @return \aeqdev\ATPL Current object.
     */
    public function push(self $child)
    {
        $this->children [] = $child;
        return $this;
    }

    /**
     * Renders using wrappers, views and children.
     */
    public function render()
    {
        $this->view = 0;
        $this->count = count($this->views);
        $this->wrapper = count($this->wrappers);
        $this->contents();
    }

    /**
     * Uses output buffering to return rendered string.
     */
    public function __toString()
    {
        ob_start();
        $this->render();
        return ob_get_clean();
    }

    /**
     * Renders next step: wrapper, view or children.
     */
    public function contents()
    {
        if ($this->renderWrappers()) {
            if ($this->renderViews()) {
                $this->renderChildren();
            }
        }
    }

    /**
     * Renders wrapper from top of stack, if any.
     *
     * @return bool True if all wrappers rendered, false otherwise.
     */
    private function renderWrappers()
    {
        if ($this->wrapper == 0) {
            return true;
        } else {
            $this->wrappers[--$this->wrapper]->renderWith($this);
            return false;
        }
    }

    /**
     * Renders ATPL object with another one, that will be used to render next step.
     * Intended to use with wrappers.
     *
     * @param self $renderWith
     */
    private function renderWith(self $renderWith)
    {
        $this->renderWith = $renderWith;
        $this->render();
        unset($this->renderWith);
    }

    /**
     * Renders next view from queue, if any.
     *
     * @return bool True if all views rendered, false otherwise.
     */
    private function renderViews()
    {
        if ($this->view == $this->count) {
            return true;
        } else {
            include $this->views[$this->view++];
            return false;
        }
    }

    /**
     * Renders list of children or calls next step of associated ATPL object, if any.
     */
    private function renderChildren()
    {
        if (isset($this->renderWith)) {
            $this->renderWith->contents();
        } else {
            foreach ($this->children as $children) {
                $children->render();
            }
        }
    }

}

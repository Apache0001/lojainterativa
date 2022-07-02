<?php

namespace Source\Core;

use League\Plates\Engine;

/**
 * Class View
 * 
 * @author Pablo O.Mesquita
 * @package Source\Core
 */

class View 
{
    /** @var Engine */
    private $engine;

    /**
     * construct
     *
     * @param [type] $path
     * @param [type] $ext
     */
    public function __construct(string $path = CONF_VIEW_PATH, string $ext = CONF_VIEW_EXT)
    {
        $this->engine = Engine::create($path, $ext);
    }

    /**
     * render
     *
     * @param string $templateName
     * @param array $data
     * @return string
     */
    public function render(string $templateName, array $data): string
    {
        return $this->engine->render($templateName, $data);
    }

    /**
     * path
     *
     * @param string $name
     * @param string $path
     * @return View
     */
    public function path(string $name, string $path): View
    {
        $this->engine->addFolder($name, $path);
        return $this;
    }

    /**
     * engine
     *
     * @return void
     */
    public function engine()
    {
        return $this->engine();
    }
}
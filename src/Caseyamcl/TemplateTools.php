<?php

namespace Caseyamcl;

class TemplateTools
{
    /**
     * Build a navigation menu
     *
     * @TODO: Add logic for set to current if current!
     * @param array $nav
     * @return string
     */
    public function navigation($path, $display)
    {
        return sprintf("<a href='%s' title='Link to %s'>%s</a>", $path, $display, $display);
    }

}

/* EOF: TwigFunctions.php */
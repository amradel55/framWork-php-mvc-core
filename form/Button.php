<?php

namespace app\core\form;


class Button extends HtmlElement
{
    public string $text;

    /**
     * @param string $text
     */

    public function __construct(string $text)
    {
        $this->text = $text;
    }


    public function render(): string
    {

        return sprintf('<button type="submit" class="btn btn-primary">%s</button>', $this->text);
    }
}
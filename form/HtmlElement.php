<?php

namespace app\core\form;


abstract class HtmlElement
{
    abstract public function render() : string;
}
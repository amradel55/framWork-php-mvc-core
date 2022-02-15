<?php

namespace mg\FrameworkPhpMvcCore\form;


abstract class HtmlElement
{
    abstract public function render() : string;
}
<?php

namespace app\core\form;

class Form extends HtmlElement
{

    public string $action;
    public string $method;

    /**
     * @var \HtmlElement[]
     */
    protected array $elements = [];

    /**
     * @param string $action
     * @param string $method
     */
    public function __construct(string $action= '', string $method = 'get')
    {
        $this->action = $action;
        $this->method = $method;
    }


    public function addElement(HtmlElement $element)
    {
        $this->elements[] = $element;
    }

    public function render(): string
    {
        $content = implode(PHP_EOL, array_map(fn($el) => $el->render(), $this->elements));
        return sprintf('<form class="form-horizontal" action="%s" method="%s">%s</form>', $this->action, $this->method, $content);
    }
}
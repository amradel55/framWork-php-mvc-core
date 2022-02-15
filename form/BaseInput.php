<?php

namespace app\core\form;


abstract class BaseInput extends HtmlElement
{
    public string $label;
    public string $name;
    public string $value;
    public $model;

    /**
     *
     * @param string $name
     * @param string $label
     * @param string $value
     */
    public function __construct(string $name, string $label = '', string $value = '', $model = [])
    {
        $this->label = $label;
        $this->name = $name;
        $this->value = $value;
        $this->model = $model;
    }

    public function render(): string
    {
        return sprintf('<div class="form-group"> 
                <label class="control-label">%s</label><br>
                %s 
                 <div class="invalid-feedback">
                    %s
                </div>
            </div>', $this->label, $this->renderInput(), $this->model->getFirstError($this->name));
    }

    abstract public function renderInput(): string;

}
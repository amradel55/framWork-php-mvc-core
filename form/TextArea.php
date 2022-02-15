<?php

namespace app\core\form;

class TextArea extends BaseInput
{

    public function renderInput(): string
    {
        return sprintf(' <textarea  name="%s" value="%s" class="form-control %s"></textarea>', $this->name, $this->value, $this->model->hasError($this->name) ?  ' is-invalid' : '');
    }

}
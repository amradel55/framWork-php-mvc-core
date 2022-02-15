<?php

namespace app\core\form;


class TextInput extends BaseInput
{


    public function renderInput(): string
    {
        return sprintf(' <input type="text" name="%s" value="%s" class="form-control %s"/>', $this->name, $this->value, $this->model->hasError($this->name) ?  ' is-invalid' : '');
    }
}
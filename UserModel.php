<?php

namespace mg\FrameworkPhpMvcCore;

use  mg\FrameworkPhpMvcCore\db\DbModel;

abstract class UserModel extends DbModel
{
 abstract public function getDisplayName(): string;
}
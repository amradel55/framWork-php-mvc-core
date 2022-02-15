<?php

namespace app\core;

use app\core\Application;

abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL= 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';


    public function loadData($data)
    {
        foreach ($data as $key => $value)
        {
             if (property_exists($this, $key))
             {
                 $this->{$key} = $value;
             }
        }
    }
    abstract public function rules(): array;

    public array $errors = [];

    public function validate()
    {
         foreach ($this->rules() as $att => $rules)
         {
             $value = $this->{$att};
            foreach ($rules as $rule)
            {
                $ruleName = $rule;
                if (!is_string($ruleName))
                {
                    $ruleName = $rule[0];
                }
                if ($ruleName === self::RULE_REQUIRED && !$value)
                {
                    $this->addErrorForRule($att, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL))
                {
                    $this->addErrorForRule($att, self::RULE_EMAIL);
                }
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min'])
                {
                    $this->addErrorForRule($att, self::RULE_MIN, $rule);
                }
                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max'])
                {
                    $this->addErrorForRule($att, self::RULE_MAX, $rule);
                }
                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']})
                {
                    $this->addErrorForRule($att, self::RULE_MATCH, $rule);
                }
                if ($ruleName === self::RULE_UNIQUE )
                {
                    $className = $rule['class'];
                    $uniqueAttr = $rule['att'] ?? $att;
                    $tableName = $className::tableName();
                    $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
                    $statement->bindValue(":attr", $value);
                    $statement->execute();
                   $record = $statement->fetchObject();
                   if ($record)
                   {
                       $this->addErrorForRule($att, self:: RULE_UNIQUE, ['filed' => $att]);
                   }
                }
            }
         }

         return empty($this->errors);
    }

    private function addErrorForRule(string $att, string $rule, $params = [])
    {
        $message = $this->errorMessages()[$rule] ?? '';
        foreach ($params as $key => $value)
        {
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$att][] = $message;
    }

    public function addError(string $att, string $message)
    {
        $this->errors[$att][] = $message;
    }

    public function errorMessages()
    {
        return [
          self::RULE_REQUIRED => 'This field is required',
          self::RULE_MAX => 'Max length of this field must be {max}',
          self::RULE_MIN => 'Min length of this field must be {min}',
          self::RULE_MATCH => 'This field must be the same as {match}',
          self::RULE_EMAIL => 'This field must be valid email address',
          self::RULE_UNIQUE => 'Record with this {filed} already exists',
        ];
    }

    public function hasError($att)
    {
        return $this->errors[$att] ?? false;
    }

    public function getFirstError($att)
    {
        return $this->errors[$att][0] ?? false;
    }
}
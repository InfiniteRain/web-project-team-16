<?php

namespace WebTech\Hospital;

/**
 * Validator class is used for form submission validation.
 *
 * @author David Lõssenko <lysenkodavid@gmail.com>
 */
class Validator
{
    /**
     * @var string Regex for rule signatures.
     */
    private const ruleSignatureRegex =
        '/^[a-zA-Z][a-zA-Z0-9]*(:({(integer|double|string)}){1}(,{(integer|double|string)})*)?$/';

    /**
     * @var array Array of the registered rules.
     */
    private $registeredRules;

    /**
     * @var array Rule set that is passed to the constructor.
     */
    private $passedRuleSet;

    /**
     * @var array Data set that is passed to the constructor.
     */
    private $passedDataSet;

    /**
     * Validator constructor.
     *
     * @param array $dataSet
     * @param array $ruleSet
     * @throws \Exception
     */
    public function __construct(array $dataSet, array $ruleSet)
    {
        // Registering rules.
        $this->registeredRules = [
            'required' => function (string $field, $value) {
                return !empty($value)
                    ?: "Field {$field} is required.";
            },
            'max:{integer}' => function (string $field, $value, int $length) {
                return $value <= $length || empty($value)
                    ?: "Field {$field} has to be less than or equal to {$length}.";
            },
            'min:{integer}' => function (string $field, $value, int $length) {
                return strlen($value) >= $length || empty($value)
                    ?: "Field {$field} has to contain at least {$length} characters.";
            },
            'email' => function (string $field, $value) {
                return filter_var($value, FILTER_VALIDATE_EMAIL) === false && !empty($value)
                    ? "Field {$field} has to contain a valid E-Mail address."
                    : true;
            },
            'alphanumeric' => function (string $field, $value) {
                return !preg_match('/[^a-z_\-0-9]/i', $value) || empty($value)
                    ?: "Field {$field} can contain only numbers, letters, dashes and underscores.";
            },
            'same:{string}' => function (string $field, $value, string $secondField) {
                return $value === $this->passedDataSet[$secondField]
                    ?: "Contents of the field {$field} has to match contents of the field {$secondField}.";
            },
            'unique:{string},{string}' => function (string $field, $value, string $table, string $column) {
                $rows = Database::query("SELECT * FROM {$table} WHERE {$column} = ?", [$value]);
                return count($rows) === 0 || empty($value)
                    ?: "Value in the field {$field} is already taken.";
            },
            'int' => function(string $field, $value) {
                return ctype_digit($value) || empty($value)
                    ?: "Field {$field} has to be an integer.";
            },
            'date' => function(string $field, $value) {
                $format = "Y-m-d";
                $d = \DateTime::createFromFormat($format, $value);
                return $d && $d->format($format) == $value || empty($value)
                    ?: "Field {$field} does not meet the correct format.";
            },
            'time' => function(string $field, $value) {
                $format = "H:i";
                $d = \DateTime::createFromFormat($format, $value);
                return $d && $d->format($format) == $value || empty($value)
                    ?: "Field {$field} does not meet the correct format.";
            }
        ];

        // Validating rules.
        foreach ($this->registeredRules as $registeredRuleSignature => $_) {
            if (!preg_match(self::ruleSignatureRegex, $registeredRuleSignature)) {
                throw new \Exception("Registered rule signature {$registeredRuleSignature} is not valid.");
            }
        }

        // Validating passed rules.
        foreach ($ruleSet as $field => $passedRules) {
            foreach (explode('|', $passedRules) as $passedRule) {
                $foundRule = false;
                foreach ($this->registeredRules as $registeredRule => $registeredRuleFunction) {
                    $registeredRuleRegex = '/^' . str_replace(
                            ['{integer}', '{double}', '{string}'],
                            ['(-?\d+)', '(-?(?:\d+|\d*\.\d+))', '([a-zA-Z0-9\s:\-]*)'],
                            $registeredRule
                        ) . '$/';

                    if (preg_match($registeredRuleRegex, $passedRule, $matches)) {
                        $foundRule = true;

                        $this->passedRuleSet[$field][$passedRule] = [
                            'func' => $this->registeredRules[$registeredRule],
                            'params' => array_merge([$field, $dataSet[$field]], array_slice($matches, 1))
                        ];

                        break;
                    }
                }

                if (!$foundRule) {
                    throw new \Exception("Rule signature $passedRule is not valid.");
                }
            }
        }

        $this->passedDataSet = $dataSet;
    }

    /**
     * Performs validation, and if it fails, redirects back with proper errors.
     *
     * @param Controller $controller
     * @param array $extraData
     */
    public function passOrFail(Controller $controller, array $extraData = [])
    {
        $validationErrors = [];
        foreach ($this->passedRuleSet as $field => $rules) {
            foreach ($rules as $rule) {
                $result = call_user_func_array($rule['func'], $rule['params']);
                if ($result !== true) {
                    $validationErrors[$field][] = $result;
                }
            }
        }

        if (count($validationErrors) > 0) {
            $controller->redirectBack(array_merge(
                ['validationErrors' => $validationErrors],
                $extraData
            ));
        }
    }
}
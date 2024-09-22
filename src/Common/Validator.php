<?php

declare(strict_types=1);

namespace Common;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\ValidationException;

abstract class Validator
{
    /**
     * @var Validator[]
     */
    protected array $args;

    /**
     * @var array<string, array<mixed> | string>
     */
    protected array $rules = [];

    /**
     * @var array|string[]
     */
    protected array $messages = [];

    /**
     * @param Validator ...$args
     */
    public function __construct(Validator ...$args)
    {
        $this->rules = $this->getRules();
        $this->messages = $this->getMessages();

        foreach ($args as $arg) {
            foreach ($arg->rules() as $name => $params) {
                $this->mergeRules([$arg->name() . '.' . $name => $params]);
            }

            foreach ($arg->messages() as $name => $message) {
                $this->mergeMessages([$arg->name() . '.' . $name => $message]);
            }
        }
    }

    /**
     * @return string
     */
    abstract public function name(): string;

    /**
     * @return array<string, array<mixed> | string>
     */
    public function rules(): array
    {
        return $this->rules;
    }

    /**
     * @return array|string[]
     */
    public function messages(): array
    {
        return $this->messages;
    }

    /**
     * @param array<mixed | string> $rules
     * @return $this
     */
    public function mergeRules(array $rules): self
    {
        $this->rules = array_merge($this->rules, $rules);

        return $this;
    }

    /**
     * @param array|string[] $messages
     * @return $this
     */
    public function mergeMessages(array $messages): self
    {
        $this->messages = array_merge($this->messages, $messages);

        return $this;
    }

    /**
     * @throws ValidationException
     */
    public function validate(Request $request): void
    {
        $validate = ValidatorFacade::make($request->all(), $this->rules, $this->messages);

        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
    }

    /**
     * @return array|string[]
     */
    abstract protected function getMessages(): array;

    /**
     * @return array<string, array<mixed> | string>
     */
    abstract protected function getRules(): array;

}

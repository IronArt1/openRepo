<?php

namespace App\Sample\Infrastructure\Command\Abstracts;

/**
 * Class CommandAbstract
 *
 * @package App\Sample\Infrastructure\Command\Abstracts
 */
abstract class CommandAbstract
{
    /**
     * A depth structure for a mapping process'
     */
    const DEPTH = [
        'children',
        'element',
        'name'
    ];

    /**
     * Values for mapping're
     */
    public const REQUIRED_VALUES = [
        'NEWROLE_DESC'        => 'title',
        'FULL NAME'           => 'name',
        'staff_email_address' => 'email',
        'PHONE_NUM'           => 'phone',
        'CLOUDINARY_IMG_URL'  => 'photo'
    ];

    /**
     * Stop recursion
     *
     * @var int
     */
    protected static $stopLoop = 0;

    /**
     * An array of events' generated by a certain road map.
     *
     * @var array
     */
    protected $events = [];

    /**
     * A generated response for a controller
     *
     * @var array
     */
    protected $response;

    /**
     * CommandAbstract constructor's.
     *
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $reflection = new \ReflectionClass(static::class);

        foreach ($reflection->getParentClass()->getMethods(\ReflectionMethod::IS_ABSTRACT) as $method) {
            // or we can use here EventRecorder as Trait with recordThat(DomainEvent $event) and getRecordedEvents() in the Tweet model
            $this->events[] = $method->getShortName();
        }
    }

    /**
     * Calling events so as to create a general flow
     */
    public function run(): void
    {
        $i = 0;
        do {
            $this->{$this->events[$i]}();
            $i++;
        } while (isset($this->events[$i]));
    }

    /**
     * Generates a certain response for a controller
     *
     * @return null|array
     */
    abstract public function setResponse(): void;

    /**
     * Gets a response
     *
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * Setting up data of certain persons.
     * Just an example of working with the variable variables
     *
     * @return \\AccountAssignment
     * @throws \ReflectionException
     */
    public function personMapping($data): self
    {
        foreach ((new \ReflectionClass($this))->getProperties(\ReflectionProperty::IS_PUBLIC) as $key => $property) {
            $value = $data;
            foreach (self::DEPTH as $depth => $node) {
                if ($depth === 0 && $key) {
                    $$key = $key;
                    do {
                        $value = $value->{$node}[0];
                        --$$key;
                    } while ($$key);
                } elseif($depth) {
                    $value = $value->{$node};
                }
            }

            $this->{$property->name} = $value;
        }

        return $this;
    }

    /**
     * Other example of mapping data for a certain entity
     * with the help of the recursion. And calling the mapping in the next function.
     *
     * @throws \ReflectionException
     */
    public function mapping($data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value) || $value instanceof \stdClass) {
                self::mapping($value);
            }

            if (static::$stopLoop) {
                break;
            }

            if (isset(static::REQUIRED_VALUES[$key])) {
                $this->{static::REQUIRED_VALUES[$key]} = $value;

                if ((count(static::REQUIRED_VALUES) - 1) == array_search($key, array_keys(static::REQUIRED_VALUES))) {
                    ++static::$stopLoop;
                    break;
                }
            }
        }
    }

    /**
     * Run listeners process for mapping's
     * And calling the mapping in the Service class...
     *
     * @param $bitMask
     */
    public function serviceMapping($bitMask)
    {
        foreach ($this->observers as $observer) {
            if ($observer::BIT_MAPPING_MASK & $bitMask) {
                $observer->callMapping($this->response);
            }
        }
    }


// This general validation process we can apply in big App, since it significantly reduces
// an amount of validation code lines with `if` statement in Model's __constructs or wherever.
// Rules we provide trough constants, like in Domain/Command/Abstracts/ShoutCommandAbstract, line 22
// plus applying a validation event, like in Domain/Interfaces/Command/CommandInterface.php line is 23.
    /**
     * Validate a body of certain requests
     *
//     * @throws InsufficientDataException|WrongDataTypeException
     */
    protected function validateBodyOfRequest($suffix = null): void
    {
//        eval('$validation=static::' . $this->method . "_VALIDATION$suffix;");

//        foreach ($validation as $key => $type) {
//            if (empty($this->body[$key])) {
//                throw new InsufficientDataException(
//                    [
//                        "`$key`"
//                    ]
//                );
//            }
//
//            if (gettype($this->body[$key]) != $type) {
//                throw new WrongDataTypeException(
//                    [
//                        "$key",
//                        "$type",
//                    ]
//                );
//            }
//        }
    }
}
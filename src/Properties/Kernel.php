<?php

namespace Aprog\Properties;

use Aprog\Exceptions\AprogException;
use Throwable;
use TypeError;

class Kernel
{
    /**
     * @throws AprogException
     */
    public function __construct(array|object $data = [])
    {
        foreach ($data as $key => $value) {
            try {
                $this->$key = $value;
            } catch (Throwable $th) {
                # Якщо це помилка типу (TypeError)
                if ($th instanceof TypeError) {
                    # Парсимо повідомлення
                    $message = $th->getMessage();
                    $properties = [];

                    if (preg_match('/property\s+([^\:]+)::\$(\w+)\s+of\s+type\s+([^\"]+)/', $message, $matches)) {
                        [$_, $class, $property, $expectedType] = $matches;

                        # Отримуємо коротку назву класу
                        $lowerClass = strtolower(class_basename($class));
                        $propertyReplace = str_replace("property", "", $lowerClass);
                        $propertyGroup = ucfirst($propertyReplace);

                        # Формуємо зрозуміле повідомлення
                        $niceMessage = "property_required_type";
                        $properties = ['class' => $propertyGroup, 'property' => $property, 'type' => $expectedType];
                    } else {
                        $niceMessage = $message;
                    }

                    throw new AprogException($niceMessage, params: $properties);
                }

                throw new AprogException($th->getMessage());
            }
        }
    }

    public function __get($name)
    {
        return $this->$name ?? ($this->defaults[$name] ?? null);
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}

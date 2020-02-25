<?php

namespace App\Modules;

use Exception;

class JsonDB
{
    /* путь к дириктории с таблицами json */
    const PATH_DB = __DIR__ . '/../../storage/db/';

    protected $data = [];

    protected $name = null;

    /**
     * @param string $name
     * @return $this
     * Комментаний: выбираем таблицу (json файл)
     */
    public function table(string $name): self
    {
        if (file_exists(self::PATH_DB . $name . '.json')) {
            $this->data = json_decode(file_get_contents(self::PATH_DB . $name . '.json'), true);
            $this->name = $name;
        }

        return $this;
    }

    /**
     * @param array $array
     * @return bool
     * Комментаний: множественное изменение данных
     */
    public function updateArray(array $array): bool
    {
        $array_keys = array_intersect(array_keys($array), array_keys($this->data));

        foreach ($array_keys as $key) {
            if (gettype($this->data[$key]) === 'array') {
                $fields = array_collapse($this->data[$key]);

                foreach($array[$key] as $k => $item) {
                    if (in_array($k, $fields) && isset($this->data[$key][$k]))
                        $this->data[$key][$k] = $item;
                }
            } elseif (gettype($this->data[$key]) === 'string' && gettype($array[$key]) === 'string') {
                $this->data[$key] = $array[$key];
            }
        }

        return (count($array_keys) >= 1) ? $this->_update() : false;
    }

    /**
     * @param string $key
     * @param array $value
     * @return bool
     * Комментаний: обновление таблицы по ключу
     */
    public function updateKey(string $key, array $value): bool
    {
        if (array_key_exists($key, $this->data)) {
            $fields = array_collapse($this->data[$key]);

            foreach($value as $k => $item) {
                if (in_array($k, $fields))
                    $this->data[$key][$k] = $item;
            }

            return $this->_update();
        } else {
            return false;
        }
    }

    /**
     * @return bool
     * Комментаний: запись данных в файл
     */
    public function _update()
    {
        try {
            $data = json_encode($this->data);
            file_put_contents(self::PATH_DB . $this->name . '.json', $data);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @param string $key
     * @return object
     * Комментаний: получаем информацию по ключу
     */
    public function get(string $key)
    {
        if (gettype($this->data[$key]) === 'string')
            return $this->data[$key];
        elseif (gettype($this->data[$key]) === 'array')
            return (object) $this->data[$key];
        else
            return null;
    }

    /**
     * @return array
     * Комментаний: все содержимое json таблицы
     */
    public function all()
    {
        return $this->data ?? [];
    }
}
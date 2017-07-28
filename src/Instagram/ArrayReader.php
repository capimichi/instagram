<?php

namespace Capimichi\Instagram;

class ArrayReader
{
    /**
     * Read nested array from path in form: key1/key2/key3/0/1/key4
     *
     * @param $array
     * @param $path
     * @return mixed
     * @throws \Exception
     */
    public static function getNestedPath($array, $path)
    {
        $data = $array;
        $pathPieces = explode("/", $path);
        foreach ($pathPieces as $pathPiece) {
            if (array_key_exists($pathPiece, $data)) {
                $data = $data[$pathPiece];
                if (is_object($data)) {
                    $data = (array)$data;
                }
            } else {
                throw new \Exception(sprintf("Wrong path %s (%s), valid values are: %s", $path, $pathPiece, implode(", ", array_keys($data))));
            }
        }
        return $data;
    }

}
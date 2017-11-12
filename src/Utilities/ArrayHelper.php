<?php
namespace TakaakiMizuno\PhpCodeManipulator\Utilities;

// Ref https://qiita.com/ka215/items/dcd2e1b0fb8c626c9e44
class ArrayHelper
{
    /**
     * @param array    $baseArray
     * @param mixed    $insertValue
     * @param int|null $position
     *
     * @return bool
     */
    public static function insert(&$baseArray, $insertValue, $position = null)
    {
        if (!is_array($baseArray)) {
            return false;
        }
        $position   = is_null($position) ? count($baseArray) : intval($position);
        $baseKeys   = array_keys($baseArray);
        $baseValues = array_values($baseArray);
        if (is_array($insertValue)) {
            $insert_keys  = array_keys($insertValue);
            $insertValues = array_values($insertValue);
        } else {
            $insert_keys  = [0];
            $insertValues = [$insertValue];
        }
        $insertKeysAfter   = array_splice($baseKeys, $position);
        $insertValuesAfter = array_splice($baseValues, $position);
        foreach ($insert_keys as $insertKeysValue) {
            array_push($baseKeys, $insertKeysValue);
        }
        foreach ($insertValues as $insertValuesValue) {
            array_push($baseValues, $insertValuesValue);
        }
        $baseKeys     = array_merge($baseKeys, $insertKeysAfter);
        $isKeyNumeric = true;
        foreach ($baseKeys as $keyValue) {
            if (!is_int($keyValue)) {
                $isKeyNumeric = false;
                break;
            }
        }
        $baseValues = array_merge($baseValues, $insertValuesAfter);
        if ($isKeyNumeric) {
            $baseArray = $baseValues;
        } else {
            $baseArray = array_combine($baseKeys, $baseValues);
        }

        return true;
    }

    /**
     * @param array    $baseArray
     * @param int|null $deletePosition
     * @param int      $deleteItems
     * @param bool     $reRollIndex
     *
     * @return bool
     */
    public static function delete(&$baseArray, $deletePosition = null, $deleteItems = 1, $reRollIndex = true)
    {
        if (!is_array($baseArray)) {
            return false;
        }
        if (is_null($deletePosition) || !is_int($deletePosition)) {
            return false;
        }
        if (!is_int($deleteItems) || intval($deleteItems) == 0) {
            return false;
        }
        $indexNumber = 0;
        foreach ($baseArray as $key => $value) {
            if ($deletePosition == $indexNumber) {
                unset($baseArray[$key]);
                $deleteItems--;
                $deletePosition++;
            }
            if ($deleteItems == 0) {
                break;
            }
            $indexNumber++;
        }
        $isKeyNumeric = true;
        foreach (array_keys($baseArray) as $keyValue) {
            if (!is_int($keyValue)) {
                $isKeyNumeric = false;
                break;
            }
        }
        if ($isKeyNumeric && $reRollIndex) {
            $baseArray = array_merge($baseArray, []);
        }

        return true;
    }
}

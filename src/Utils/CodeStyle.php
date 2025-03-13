<?php

namespace Seal\LaravelDataMapper\Utils;

class CodeStyle
{
    public static function camelToSnake(string $camelCase): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $camelCase));
    }

    public static function snakeToCamel(string $snakeCase): string
    {
        $words = explode('_', $snakeCase);
        $camelCase = array_shift($words); // Сохраняем первое слово в нижнем регистре
        foreach ($words as $word) {
            $camelCase .= ucfirst($word); // Каждое следующее слово начинаем с заглавной буквы
        }

        return $camelCase;
    }
}

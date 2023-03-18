<?php

function dd($value){
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
    exit;
}

function _env(string $name, ?string $default = null): string|bool{
    $envFilePath = dirname(dirname(__DIR__)). DIRECTORY_SEPARATOR . '.env';
    if(! file_exists($envFilePath)) return false;

    $envValues = file_get_contents($envFilePath);

    $pattern = "/(?<={$name}=)[^\s]*/";
    preg_match($pattern, $envValues, $matches);

    if($matches){
        return str_replace('"', '', $matches[0]);
    }

    if($default) return $default;
    return false;
}
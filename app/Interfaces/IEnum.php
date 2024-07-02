<?php

interface IEnum
{
    public function getNombre(): string;
    public static function fromId(int $id): ?self;
    public static function imprimirOpciones(): string;
}

?>
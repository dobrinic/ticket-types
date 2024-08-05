<?php

namespace Forms;

interface RequestFormInterface
{
    public function notValid(): bool;

    public function errors(): array;

    public function getData(): array;
}
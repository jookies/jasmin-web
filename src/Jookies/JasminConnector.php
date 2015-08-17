<?php

namespace Jookies;

abstract class JasminConnector
{
    protected $telnet;
    protected $properties;

    abstract public function getAll();
    abstract public function save();
    abstract public function update();
    abstract public function show();
    abstract public function delete();
}
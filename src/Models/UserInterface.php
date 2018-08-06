<?php
/**
 * Created by PhpStorm.
 * User: cairo
 * Date: 06/08/2018
 * Time:  14:22
 */

namespace CROFin\Models;


interface UserInterface
{
    public function getId():int;

    public function getFullName():string;

    public function getEmail():string;

    public function getPassword():string;
}
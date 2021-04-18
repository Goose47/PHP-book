<?php
namespace Ninja;

interface Routes
{
    public function getRoutes($route) : array;
    public function getAuthentication() : \Ninja\Authentication;
    public function checkPermission($permission) : bool;

}
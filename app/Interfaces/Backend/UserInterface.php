<?php

namespace App\Interfaces\backend;

interface UserInterface
{
    public function getUsers($request);
    public function create($request);
    public function update($request, $id);
    public function getById($request, $id);
    public function delete($request, $id);
    public function getOptions($request);
}

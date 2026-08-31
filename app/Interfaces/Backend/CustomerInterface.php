<?php

namespace App\Interfaces\backend;

interface CustomerInterface
{
    public function getCustomers($request);
    public function create($request);
    public function update($request, $id);
    public function getById($request, $id);
    public function delete($request, $id);
    public function getOptions($request);
}
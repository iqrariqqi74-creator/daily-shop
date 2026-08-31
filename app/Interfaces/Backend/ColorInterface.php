<?php

namespace App\Interfaces\Backend;
interface ColorInterface
{
    public function getColors($request);
    public function create($request);
    public function update($request, $id);
    public function getById($request, $id);
    public function delete($request, $id);
    public function getOptions($request);
}
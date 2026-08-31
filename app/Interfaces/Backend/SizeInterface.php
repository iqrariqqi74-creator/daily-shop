<?php

namespace App\Interfaces\Backend;
interface SizeInterface
{
    public function getSizes($request);
    public function create($request);
    public function update($request, $id);
    public function getById($request, $id);
    public function delete($request, $id);
    public function getOptions($request);
}

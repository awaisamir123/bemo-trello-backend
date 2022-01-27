<?php

namespace App\Http\Repository;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface ColumnRepositoryInterface
 * @package App\Repositories
 */
interface ColumnRepositoryInterface {

    /**
     * @param array $input
     * @return Model
     */
    public function index();

    public function create(array $input);

    public function columnArrangement(array $input);

    public function update($id, array $input);

    public function delete($id);

}

<?php

namespace App\Http\Repository;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface CardRepositoryInterface
 * @package App\Repositories
 */
interface CardRepositoryInterface {

    /**
     * @param array $input
     * @return Model
     */
    public function index();

    public function view($id);

    public function create(array $input);

    public function update($id, array $input);

    public function delete($id);

}

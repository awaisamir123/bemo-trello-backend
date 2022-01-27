<?php

namespace App\Http\Controllers;

use App\Http\Repository\ColumnRepositoryInterface;
use App\Http\Resources\ColumnResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ColumnController extends Controller
{
    private $columnRepo;

    public function __construct(ColumnRepositoryInterface $columnRepo)
    {
        $this->columnRepo = $columnRepo;
    }

    public function index()
    {
        $response = $this->columnRepo->index();
        return $response;
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                "statusCode" => Response::HTTP_BAD_GATEWAY,
                'message' => 'validation error',
                'error' => $validator->errors()
            ];
        }

        $input = $request->all();
        $response = $this->columnRepo->create($input);
        if ($response["statusCode"] == Response::HTTP_OK) {
            return $response;
        }
        return $response;
    }

    public function columnArrangement(Request $request) {
        $input = $request->all();
        $response = $this->columnRepo->columnArrangement($input);
        if ($response["statusCode"] == Response::HTTP_OK) {
            return $response;
        }
        return $response;
    }

    public function update($id, Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                "statusCode" => Response::HTTP_BAD_GATEWAY,
                'message' => 'validation error',
                'error' => $validator->errors()
            ];
        }

        $input = $request->all();
        $response = $this->columnRepo->update($id, $input);
        if ($response["statusCode"] == Response::HTTP_OK) {
            return $response;
        }
        return $response;
    }

    public function destroy($id) {
        $response = $this->columnRepo->delete($id);
        if ($response["statusCode"] == Response::HTTP_OK) {
            return $response;
        }
        return $response;
    }
}

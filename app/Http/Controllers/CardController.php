<?php

namespace App\Http\Controllers;

use App\Http\Repository\CardRepositoryInterface;
use App\Http\Repository\ColumnRepositoryInterface;
use App\Models\Column;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CardController extends Controller
{
    private $cardRepo;

    public function __construct(CardRepositoryInterface $cardRepo)
    {
        $this->cardRepo = $cardRepo;
    }

    public function index()
    {
        $response = $this->cardRepo->index();
        return $response;
    }

    public function view($id) {
        $response = $this->cardRepo->view($id);
        return $response;
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'column_id' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                "statusCode" => Response::HTTP_BAD_GATEWAY,
                'message' => 'validation error',
                'error' => $validator->errors()
            ];
        }

        $column = Column::find($request['column_id']);
        if(!$column) {
            return [
                "statusCode" => Response::HTTP_BAD_GATEWAY,
                'message' => 'Column error.',
                'error' => 'Column not found.'
            ];
        }

        $input = $request->all();
        $response = $this->cardRepo->create($input);
        if ($response["statusCode"] == Response::HTTP_OK) {
            return $response;
        }
        return $response;
    }

    public function update($id, Request $request) {
        $validator = Validator::make($request->all(), [
            'column_id' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                "statusCode" => Response::HTTP_BAD_GATEWAY,
                'message' => 'validation error',
                'error' => $validator->errors()
            ];
        }

        $column = Column::find($request['column_id']);
        if(!$column) {
            return [
                "statusCode" => Response::HTTP_BAD_GATEWAY,
                'message' => 'Column error.',
                'error' => 'Column not found.'
            ];
        }

        $input = $request->all();
        $response = $this->cardRepo->update($id, $input);
        if ($response["statusCode"] == Response::HTTP_OK) {
            return $response;
        }
        return $response;
    }

    public function destroy($id) {
        $response = $this->cardRepo->delete($id);
        if ($response["statusCode"] == Response::HTTP_OK) {
            return $response;
        }
        return $response;
    }
}

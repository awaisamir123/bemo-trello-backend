<?php

namespace App\Http\Repository\Eloquent;

use App\Http\Repository\CardRepositoryInterface;
use App\Models\Card;
use App\Models\Column;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CardRepository extends BaseRepository implements CardRepositoryInterface
{

    /**
     * CartRepository constructor.
     *
     * @param Column $model
     */
    public function __construct(Card $model)
    {
        parent::__construct($model);
    }

    public function index()
    {
        $card = Card::with('column')->get();
        return [
            "statusCode" => Response::HTTP_OK,
            "data" => count($card) ? $card->toArray() : []
        ];
    }

    public function view($id)
    {
        $card = Card::where('id', $id)->with('column')->first();
        return [
            "statusCode" => Response::HTTP_OK,
            "data" => isset($card)? $card->toArray() : []
        ];
    }

    public function create(array $input): array
    {
        DB::beginTransaction();
        try {
            $card = new Card();
            $card->column_id = $input['column_id'];
            $card->title = $input['title'];
            $card->description = $input['description'];
            $card->save();
            DB::commit();
            return [
                "statusCode" => Response::HTTP_OK,
                "message" => 'Card add successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                "statusCode" => Response::HTTP_BAD_GATEWAY,
                'message' => 'Error while creating card',
                'error' => $e->getMessage()
            ];
        }
    }

    public function update($id, array $input): array
    {
        DB::beginTransaction();
        try {
            $card = Card::find($id);
            if ($card) {
                $card->column_id = $input['column_id'];
                $card->title = $input['title'];
                $card->description = $input['description'];
                $card->save();
                DB::commit();
                return [
                    "statusCode" => Response::HTTP_OK,
                    "message" => 'Card updated successfully.'
                ];
            } else {
                return [
                    "statusCode" => Response::HTTP_BAD_GATEWAY,
                    'message' => 'Error while update card',
                    'error' => 'Record not found.'
                ];
            }
        } catch (\Exception $e) {
            DB::rollback();
            return [
                "statusCode" => Response::HTTP_BAD_GATEWAY,
                'message' => 'Error while creating card',
                'error' => $e->getMessage()
            ];
        }
    }

    public function delete($id) {
        try {
            $card = Card::find($id);
            if ($card) {
                $card->status = 0;
                $card->save();
                $card->delete();
                return [
                    "statusCode" => Response::HTTP_OK,
                    "message" => 'Card deleted successfully.'
                ];
            } else {
                return [
                    "statusCode" => Response::HTTP_BAD_GATEWAY,
                    'message' => 'Error while delete card',
                    'error' => 'Record not found.'
                ];
            }
        } catch (\Exception $e) {
            return [
                "statusCode" => Response::HTTP_BAD_GATEWAY,
                'message' => 'Error while creating card',
                'error' => $e->getMessage()
            ];
        }
    }

}

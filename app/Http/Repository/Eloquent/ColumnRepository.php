<?php

namespace App\Http\Repository\Eloquent;

use App\Http\Repository\ColumnRepositoryInterface;
use App\Http\Resources\ColumnResource;
use App\Models\Card;
use App\Models\Column;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ColumnRepository extends BaseRepository implements ColumnRepositoryInterface
{

    /**
     * ColumnRepository constructor.
     *
     * @param Column $model
     */
    public function __construct(Column $model)
    {
        parent::__construct($model);
    }

    public function index()
    {
        $columns = Column::with('cards')->get();
        return [
            "statusCode" => Response::HTTP_OK,
            "data" => count($columns) ? $columns->toArray() : []

        ];
    }

    public function create(array $input): array
    {
        DB::beginTransaction();
        try {
            $column = new Column();
            $column->title = $input['title'];
            $column->save();
            DB::commit();
            return [
                "statusCode" => Response::HTTP_OK,
                "message" => 'Column add successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                "statusCode" => Response::HTTP_BAD_GATEWAY,
                'message' => 'Error while creating column',
                'error' => $e->getMessage()
            ];
        }
    }

    public function columnArrangement(array $input): array
    {
        DB::beginTransaction();
        try {
            $column = Column::find($input['column_id']);
            if ($column) {
                if(count($input['card_ids'])) {
                    foreach ($input['card_ids'] as $key=>$cardId) {
                        $cardId = Card::where('column_id', $column->id)
                            ->where('id', $cardId)
                            ->first();
                        if($cardId) {
                            $cardId->ordering = $key+1;
                            $cardId->save();
                        }
                    }
                }
                else {
                    return [
                        "statusCode" => Response::HTTP_BAD_GATEWAY,
                        'message' => 'Error while ordering card in column',
                        'error' => 'Card ids not in array.'
                    ];
                }
                DB::commit();
                return [
                    "statusCode" => Response::HTTP_OK,
                    "message" => 'Column card ordering completed successfully.'
                ];
            } else {
                return [
                    "statusCode" => Response::HTTP_BAD_GATEWAY,
                    'message' => 'Error while ordering card in column',
                    'error' => 'Column record not found.'
                ];
            }
        } catch (\Exception $e) {
            DB::rollback();
            return [
                "statusCode" => Response::HTTP_BAD_GATEWAY,
                'message' => 'Error while ordering card in column',
                'error' => $e->getMessage()
            ];
        }
    }

    public function update($id, array $input): array
    {
        DB::beginTransaction();
        try {
            $column = Column::find($id);
            if ($column) {
                $column->title = $input['title'];
                $column->save();
                DB::commit();
                return [
                    "statusCode" => Response::HTTP_OK,
                    "message" => 'Column updated successfully.'
                ];
            } else {
                return [
                    "statusCode" => Response::HTTP_BAD_GATEWAY,
                    'message' => 'Error while update column',
                    'error' => 'Record not found.'
                ];
            }
        } catch (\Exception $e) {
            DB::rollback();
            return [
                "statusCode" => Response::HTTP_BAD_GATEWAY,
                'message' => 'Error while creating column',
                'error' => $e->getMessage()
            ];
        }
    }

    public function delete($id)
    {
        try {
            $column = Column::find($id);
            if ($column) {
                $column->status = 0;
                $column->save();
                Card::where('column_id', $column->id)->update([
                    'status' => 0,
                    'deleted_at' => Carbon::now(),
                ]);
                $column->delete();
                return [
                    "statusCode" => Response::HTTP_OK,
                    "message" => 'Column deleted successfully.'
                ];
            } else {
                return [
                    "statusCode" => Response::HTTP_BAD_GATEWAY,
                    'message' => 'Error while delete column',
                    'error' => 'Record not found.'
                ];
            }
        } catch (\Exception $e) {
            DB::rollback();
            return [
                "statusCode" => Response::HTTP_BAD_GATEWAY,
                'message' => 'Error while creating column',
                'error' => $e->getMessage()
            ];
        }
    }

}

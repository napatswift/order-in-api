<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Table;
use App\Http\Resources\TableResource;
use App\Http\Requests\StoreTableRequest;
use App\Http\Requests\UpdateTableRequest;

class TableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $this->authorize('viewAny', Table::class);

        return Table::all();
    }

    public function show(Table $table)
    {
        $this->authorize('view', $table);

        return new TableResource($table);
    }

    public function store(StoreTableRequest $request)
    {
        $this->authorize('create', Table::class);

        $table = new TableResource(Table::create($request->all()));
        if ($table->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Table saved successfully',
                'table_number' => $table->table_number
            ], 201);
        }
        return response()->json([
            'success' => false,
            'message' => 'Table saved failed',
        ], 500); 
    }

    public function update(UpdateTableRequest $request, Table $table)
    {
        $this->authorize('update', $table);
        
        $table->update($request->all());
        if ($table->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Table updated successfully',
                'table_number' => $table->table_number
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Table updated failed',
        ], 500); 
    }

    public function destroy(Table $table)
    {
        $this->authorize('delete', $table);
        
        $number = $table->table_number;
        if ($table->delete()) {
            return response()->json([
                'success' => "Table {$number} deleted successfully"
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => "Table {$number} deleted failed"
        ], 500);
    }
}

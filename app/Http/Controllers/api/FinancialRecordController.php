<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\FinancialRecord;

class FinancialRecordController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric',
            'description' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ]);
        }

        $user = Auth::user();
        $transaction = new FinancialRecord([
            'user_id' => $user->id,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        if ($transaction->save()) {
            return response()->json([
                'status' => true,
                'message' => 'Transaction added successfully',
                'data' => $transaction->id
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to add transaction',
                'data' => null
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'sometimes|required|in:income,expense',
            'amount' => 'sometimes|required|numeric',
            'description' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $transaction = FinancialRecord::where('user_id', $user->id)->find($id);

        if (!$transaction) {
            return response()->json([
                'status' => false,
                'message' => 'Transaction not found',
                'data' => null
            ], 404);
        }

        if ($request->has('type')) $transaction->type = $request->type;
        if ($request->has('amount')) $transaction->amount = $request->amount;
        if ($request->has('description')) $transaction->description = $request->description;

        if ($transaction->save()) {
            return response()->json([
                'status' => true,
                'message' => 'Transaction updated successfully',
                'data' => $transaction->id
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update transaction',
                'data' => null
            ]);
        }
    }
}

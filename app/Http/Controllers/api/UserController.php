<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;

class UserController extends Controller
{
    public function list()
    {
        $users = User::with('FinancialRecords')->get();

        $data = $users->map(function ($user) {
            $income = $user->FinancialRecords->where('type', 'income')->sum('amount');
            $expense = $user->FinancialRecords->where('type', 'expense')->sum('amount');

            return [
                'user' => $user,
                'total_income' => $income,
                'total_expense' => $expense,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'List of users with transactions',
            'data' => $data
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Traits\Loggable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    use Loggable;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        // $this->custom_authorize('add_sales');
        $cashier = $this->cashierMoney(null, 'user_id = "'.Auth::user()->id.'"', 'status = "abierta"')->original;

        if (!$cashier['return']) {
            return redirect()
                ->back()
                ->with(['message' => 'Usted no cuenta con caja abierta.', 'alert-type' => 'warning']);
        }

        if($cashier['amountCashier'] < $request->amount)
        {
            return redirect()
                ->back()
                ->with(['message' => 'No cuenta con monto en efectivo disponible para realizar un gasto.', 'alert-type' => 'warning']);
        }

        DB::beginTransaction();
        try {
            $sale = Expense::create([
                'categoryExpense_id' => $request->categoryExpense_id ,
                'observation' => $request->observation,
                'amount' => $request->amount,
                'cashier_id' => $cashier['cashier']->id,
            ]);

            DB::commit();
            return redirect()
                ->back()
                ->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success', 'sale' => $sale]);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->logError($th, $request);
            return redirect()
                ->back()
                ->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpensesController extends Controller
{
    //metoto per ritornare un dato in base all'user_id
    public function idValue()
    {
        $userId = Auth::id();
        return Expenses::where('user_id', $userId);
    }
    //tutte le richieste get
    public function index(Request $request)
    {
        $userId = Auth::id();
        //se ho dei filtri richiama il metodo filterByMonth
        if ($request->has('filter.month')) {
            return $this->filterByMonth($request);
        }

        $expenses = Expenses::where('user_id', $userId)->get();

        return $expenses;
    }

    public function show($id)
    {
        $expenses = $this->idValue()->find($id);
        return $expenses;
    }

    public function filterByMonth(Request $request)
    {
        // return $expenses;
        $month = $request->input('filter.month');

        // Filtra le spese per il mese specificato
        $expenses = $this->idValue()->whereRaw("MONTHNAME(date) = ?", [$month])->orderBy('date', 'asc')->get();

        // Calcola la somma degli importi delle spese
        $total = $expenses->sum('amount');

        if ($total == 0) {
            return response()->json(['message' => 'No expenses found within the specified month'], 204);
        }

        // Crea un array associativo contenente sia le spese che il totale
        $result = [
            'expenses' => $expenses,
            'total' => $total,
        ];

        // Restituisci l'array associativo come parte della risposta JSON
        return response()->json($result);
    }



    //richiesta post
    public function store(Request $request)
    {

        $userId = Auth::id();


        $data = $request->only(['title', 'amount', 'description', 'date']);
        $data['user_id'] = $userId;

        Expenses::create($data);

        return response()->json(['message' => 'Expense created successfully'], 200);
    }

    //richiesta put
    public function update(Request $request, $id)
    {

        $expense = $this->idValue()->find($id);
        if (!$expense) {
            return response()->json(['error' => 'Expense not found'], 404);
        }
        $data = $request->only(['title', 'amount', 'description', 'date']);


        $expense->update($data);
        return response()->json(['message' => 'Expense updated successfully'], 200);
    }

    //richiesta delete
    public function destroy($id)
    {
        try {
            // Cerca la spesa per ID e cerca di eliminarla
            $expense = $this->idValue()->findOrFail($id);
            $expense->delete();
    
            return response()->json(['message' => 'Expense deleted successfully'], 200);
        } catch (\Exception $e) {
            // In caso di errore, restituisci una risposta con il messaggio di errore
            return response()->json(['message' => 'Error deleting expense: ' . $e->getMessage()], 500);
        }
    }
}

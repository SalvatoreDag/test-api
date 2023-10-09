<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incomes;
use Illuminate\Support\Facades\Auth;

class IncomesController extends Controller
{
    //metoto per ritornare un dato in base all'user_id
    public function idValue()
    {
        $userId = Auth::id();
        return Incomes::where('user_id', $userId);
    }
    //tutte le richieste get
    public function index(Request $request)
    {
        $userId = Auth::id();
        //se ho dei filtri richiama il metodo filterByMonth
        if ($request->has('filter.month')) {
            return $this->filterByMonth($request);
        }

        $expenses = Incomes::where('user_id', $userId)->get();

        return $expenses;
    }

    public function show($id)
    {
        $expenses = $this->idValue()->find($id);
        return $expenses;
    }


    public function filterByMonth(Request $request)
    {
        $month = $request->input('filter.month');


        $expenses = $this->idValue()->whereRaw("MONTHNAME(data) = ?", [$month])->get();

        if ($expenses->isEmpty()) {
            return response()->json(['message' => 'No incomes found within the specified month'], 204);
        }

        return $expenses;
    }

    public function total()
    {
        $total = $this->idValue()->sum('amount');

        return $total;
    }


    //richiesta post
    public function store(Request $request)
    {

        $userId = Auth::id();


        $data = $request->only(['title', 'amount', 'description', 'data']);
        $data['user_id'] = $userId;

        Incomes::create($data);

        return response()->json(['message' => 'Incomes created successfully'], 200);
    }

    //richiesta put
    public function update(Request $request, $id)
    {

        $expense = $this->idValue()->find($id);
        $data = $request->only(['title', 'amount', 'description', 'data']);


        $expense->update($data);
        return response()->json(['message' => 'Incomes updated successfully'], 200);
    }

    //richiesta delete
    public function destroy($id)
    {

        $expense = $this->idValue()->find($id)->delete();

        return response()->json(['message' => 'Incomes deleted successfully'], 200);
    }
}

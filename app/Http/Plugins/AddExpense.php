<?php

namespace App\Http\Plugins;

use Illuminate\Http\Request;
use App\Expense;

class AddExpense
{   
    /**
     * Add expense.
     * 
     * @return Responce
     */
    public function add(Request $request, $element)
    {
        $scope = [];
        
        $expense = $request->input('expense');
        
        if ($expense == 'sape') {
            $expense = new Expense;
            
            $expense->name = 'Sape';
            $expense->category_id = 2;
            $expense->source_id = 4;
            $expense->sum = 4000;
            $expense->service_section_id = 4;
            
            $expense->save();
            
            usleep(100000);
            
            $expense = new Expense;
            
            $expense->name = 'Комиссия Яндекса';
            $expense->category_id = 4;
            $expense->source_id = 4;
            $expense->sum = 210.53;
            $expense->service_section_id = 4;
            
            $expense->save();
        }
        
        return response()->json($scope);
    }
    
    /**
     * Fast adding expense.
     * 
     * @return View
     */
    public function index(Request $request, $element)
    {
        $scope = [];
        
        $scope['element'] = $element;
        
        return view('plugins.addExpense', $scope);
    }
}
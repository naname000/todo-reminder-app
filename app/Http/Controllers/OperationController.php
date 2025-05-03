<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOperationRequest;
use App\Http\Requests\UpdateOperationRequest;
use App\Models\Operation;
use Inertia\Inertia;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paginator = Operation::orderBy('scheduled_at', 'desc')->paginate(9);
        $paginator = Operation::orderBy('id', 'desc')->paginate(6);

        return Inertia::render('Operation/Index', [
          'operations' => $paginator
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Operation/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOperationRequest $request)
    {
        $operation = Operation::create($request->validated());
        return redirect()->route('operations.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Operation $operation)
    {
        return Inertia::render('Operation/Show', [
          'operation' => $operation
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Operation $operation)
    {
        return Inertia::render('Operation/Edit', [
          'operation' => $operation
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOperationRequest $request, Operation $operation)
    {
        $operation->update($request->validated());
        return redirect()->route('operations.show', $operation->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Operation $operation)
    {
        $operation->delete();
        return redirect()->route('operations.index');
    }
}

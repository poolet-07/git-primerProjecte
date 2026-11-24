<?php

namespace App\Http\Controllers;
use App\Models\Administrador;

use Illuminate\Http\Request;

class AdministradorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Administrador::get();
        return view('adminLlistat',
        [
            'admins'=> $admins
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("formAdmin");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->input('user');
        $pass = $request->input('pwd');
        Administrador::create([
            'user'=>$user,
            'pwd' =>$pass
        ]);
        return redirect()->route('menu');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.f
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

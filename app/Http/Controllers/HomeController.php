<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class HomeController extends Controller
{

    public function __construct()
    {
        // $this->middleware(['permission:view_dashboard']);
    }

    public function dashboard()
    {
        return view('dashboard');

        return abort(403);
    }

    public function index(Request $request)
    {

        $data = new User;

        if ($request->get('search')) {
            $data = $data->where('name', 'LIKE', '%' . $request->get('search') . '%')
                ->orWhere('email', 'LIKE', '%' . $request->get('search') . '%');
        }

        if ($request->get('tanggal')) {
            $data = $data->where('name', 'LIKE', '%' . $request->get('search') . '%')
                ->orWhere('email', 'LIKE', '%' . $request->get('search') . '%');
        }

        // $data = $data->onlyTrashed();

        $data = $data->get();

        return view('index', compact('data', 'request'));
    }

    public function assets(Request $request)
    {

        $data = new User;

        if ($request->get('search')) {
            $data = $data->where('name', 'LIKE', '%' . $request->get('search') . '%')
                ->orWhere('email', 'LIKE', '%' . $request->get('search') . '%');
        }

        if ($request->get('tanggal')) {
            $data = $data->where('name', 'LIKE', '%' . $request->get('search') . '%')
                ->orWhere('email', 'LIKE', '%' . $request->get('search') . '%');
        }

        $data = $data->get();

        if ($request->get('export') == 'pdf') {
            $pdf = Pdf::loadView('pdf.assets', ['data' => $data]);
            return $pdf->stream('Data Assets.pdf');
        }

        return view('assets', compact('data', 'request'));
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'photo' => 'required|mimes:png,jpg,jpeg|max:2048',
            'email' => 'required|email',
            'nama'  => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $photo      = $request->file('photo');
        $filename   = date('Y-m-d') . $photo->getClientOriginalName();
        $path       = 'photo-user/' . $filename;

        Storage::disk('public')->put($path, file_get_contents($photo));


        $data['email']      = $request->email;
        $data['name']       = $request->nama;
        $data['password']   = Hash::make($request->password);
        $data['image']      = $filename;

        User::create($data);

        return redirect()->route('admin.index');
    }

    public function edit(Request $request, $id)
    {
        $data = User::find($id);

        return view('edit', compact('data'));
    }

    public function detail(Request $request, $id)
    {
        $data = User::find($id);

        return view('detail', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'nama'      => 'required',
            'password'  => 'nullable',
            'photo'     => 'nullable|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($validator->fails()) return redirect()->back()->withInput()->withErrors($validator);

        $find = User::find($id);

        $data['email']      = $request->email;
        $data['name']       = $request->nama;

        if ($request->password) {
            $data['password']   = Hash::make($request->password);
        }

        $photo      = $request->file('photo');

        if ($photo) {

            $filename   = date('Y-m-d') . $photo->getClientOriginalName();
            $path       = 'photo-user/' . $filename;

            if ($find->image) {
                Storage::disk('public')->delete('photo-user/' . $find->image);
            }

            Storage::disk('public')->put($path, file_get_contents($photo));

            $data['image']      = $filename;
        }

        $find->update($data);

        return redirect()->route('admin.index');
    }

    public function delete(Request $request, $id)
    {
        $data = User::find($id);

        if ($data) {
            $data->forceDelete();
        }

        return redirect()->route('admin.index');
    }
}

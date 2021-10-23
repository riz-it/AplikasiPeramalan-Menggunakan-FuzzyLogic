<?php

namespace App\Http\Controllers;

use App\Models\Cabai;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataImport;

class CabaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contents.datacabai', [
            'active' => 1,
            'activeTipe' => 0,
            'data' => Cabai::orderBy('tanggal')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request->id;
        if ($id == '') {
            $newDate = date("Y/m/d", strtotime($request->tanggal));
            $result = preg_replace("/[^0-9]/", "", $request->harga);
            Cabai::create([
                'tanggal' => $newDate,
                'harga' => $result
            ]);
            return redirect()->route('data.index')
                ->with('success', 'Data berhasil ditambahkan.');
        } else {

            Cabai::where('id', $id)->update([
                'tanggal' => $request->tanggal,
                'harga' => preg_replace("/[^0-9]/", "", $request->harga)
            ]);
            return redirect()->route('data.index')
                ->with('success', 'Data berhasil diubah.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Cabai::where('id', $id)->first();
        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cabai::where('id', $id)->delete();

        return redirect()->route('data.index')
            ->with('success', 'Data berhasil dihapus');
    }

    public function import(Request $request)
    {
        // validasi
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        // menangkap file excel
        $file = $request->file('file');

        // membuat nama file unik
        $nama_file = rand() . $file->getClientOriginalName();

        // // upload ke folder file_siswa di dalam folder public
        // $file->move('file_data', $nama_file);

        // // import data
        Excel::import(new DataImport, $file);
        return redirect()->route('data.index')
            ->with('success', 'Data berhasil diimport.');
    }
}

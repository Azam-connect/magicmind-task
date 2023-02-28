<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Storage::disk('public')->exists('task.json')) {
            $data = Storage::disk('public')->get('task.json');
        } else {
            Storage::disk('public')->put('task.json', json_encode([]));
            $data = Storage::disk('public')->get('task.json');
        }
        $data = json_decode($data, true);
        if (empty($data)) {
            return view('welcome', compact('data'));
        } else {
            return view('welcome', compact('data'));
        }
    }

    /**
     * Search from resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function search($searchText = null)
    {
        $data = json_decode(Storage::disk('public')->get('task.json'), true);
        if (empty($searchText)) {
            return $data;
        } else {
            $collection = collect($data);
            $data = $collection->filter(function ($value, $key) use ($searchText) {
                $pattern = "/$searchText/i";
                return (preg_match($pattern, $value['id']) || preg_match($pattern, $value['task'])|| preg_match($pattern, $value['date']));
            });
        }
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'task' => 'required|regex:/^[a-zA-Z0-9\s ]+$/',
            'task_date' => 'required|date',
        ]);


        $data = json_decode(Storage::disk('public')->get('task.json'), true);

        if (empty($request->task_id)) {
            $data[] = ["id" => count($data)+1, "task" => $request->task, "date" => $request->task_date];
        } else {
            $collection = collect($data);
            $findData = $collection->firstWhere('id', $request->task_id);
            $id = $findData['id'];
            $editData = ["id" => $id, "task" => $request->task, "date" => $request->task_date];
            $withoutEditData = $collection->filter(function ($value, $key) use ($id) {
                $value['id'] != $id;
            });
            $data = $withoutEditData;
            $data[] = $editData;
        }
        Storage::disk('public')->put('task.json', json_encode($data));
        //return $data;

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = json_decode(Storage::disk('public')->get('task.json'), true);
        $collection = collect($data);
        $data = $collection->firstWhere('id', $id);
        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = json_decode(Storage::disk('public')->get('task.json'), true);
        $collection = collect($data);
        $data = $collection->filter(function ($value, $key) use ($id) {
            $value['id'] != $id;
        });
        Storage::disk('public')->put('task.json', json_encode($data));
        $data = json_decode(Storage::disk('public')->get('task.json'), true);
    }
}

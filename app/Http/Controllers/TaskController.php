<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index() {
        return Task::where('archive', 0)
            ->orderBy('id', 'desc')->get();
    }

    public function archived() {
        return Task::where('archive', 1)
            ->orderBy('id', 'desc')->get();
    }

    public function all() {
        return Task::orderBy('id', 'desc')->get();
    }

    public function store(Request $request) {
        // dd(Carbon::parse(request('deadline'))->toDateTimeString());
        // dd(Carbon::parse(request('deadline'))->setTimeZone('Asia/Yangon')->toDateTimeString());

        $this->validate($request, [
            'body'=>'required|max:500'
        ]);

        return Task::create([
            'body' => request('body'),
            'deadline' => Carbon::parse(request('deadline'))->setTimeZone('Asia/Yangon')->toDateTimeString()
        ]);
        
    }

    public function edit(Request $request) {
        $this->validate($request, [
            'body'=>'required|max:500',
            'deadline' => Carbon::parse(request('deadline'))->setTimeZone('Asia/Yangon')->toDateTimeString()
        ]);

        $task = Task::findOrFail($request->id);
        $task->body = $request->body;
        $task->save();
    }

    public function archive($id) {
        $task = Task::findOrFail($id);

        if($task->archive == 1) {
            $task->archive = 0;
        }
        else {
            $task->archive = 1;
        }
        
        $task->save();
    }

    public function destroy($id) {
        $task = Task::findOrFail($id);
        $task->delete();
    }
}

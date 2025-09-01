<?php

namespace App\Http\Controllers;
use App\Models\TbUser; use Illuminate\Http\Request; use Illuminate\Support\Facades\Hash;

class TbUserController extends Controller {
  public function index(){ $items=TbUser::orderBy('userName')->paginate(15); return view('users.index',compact('items')); }
  public function create(){ return view('users.create'); }
  public function store(Request $r){
    $v=$r->validate([
      'userId'=>'required|max:8|unique:tb_users,userId',
      'userName'=>'required|max:60',
      'userPassword'=>'required|min:6',
      'userLevel'=>'required|max:2',
      'userEmail'=>'nullable|email|max:120',
      'hashMobile'=>'nullable|max:255','hashWeb'=>'nullable|max:255',
    ]);
    $v['userPassword']=Hash::make($v['userPassword']); $v['lastUpdated']=now();
    TbUser::create($v); return redirect()->route('users.index')->with('ok','Created');
  }
  public function edit(TbUser $user){ return view('users.edit',compact('user')); }
  public function update(Request $r, TbUser $user){
    $v=$r->validate([
      'userId'=>'required|max:8|unique:tb_users,userId,'.$user->uid.',uid',
      'userName'=>'required|max:60',
      'userPassword'=>'nullable|min:6',
      'userLevel'=>'required|max:2',
      'userEmail'=>'nullable|email|max:120',
      'hashMobile'=>'nullable|max:255','hashWeb'=>'nullable|max:255',
    ]);
    if(!empty($v['userPassword'])) $v['userPassword']=Hash::make($v['userPassword']); else unset($v['userPassword']);
    $user->update($v+['lastUpdated'=>now()]); return redirect()->route('users.index')->with('ok','Updated');
  }
  public function destroy(TbUser $user){ $user->delete(); return back()->with('ok','Deleted'); }
  public function show(TbUser $user){ return view('users.show',compact('user')); }
}

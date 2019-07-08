<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Account;
use App\Transaction;
use Validator;

class UserProfile extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function show(Request $request)
    {
        $user = User::find($request->user()->id);
        $mainAccount = $user->main_account()->first();
        $mainAccountTransactions = $user->main_account->transactions;
        return view('userProfile.home',compact('user','mainAccount','mainAccountTransactions','availableAccounts'));
    }

    public function showAccount($id){
        $account = Account::where('user_id',$id)->with(['user'])->first();
        $accountTransactions = $account->transactions;

       return view('userProfile.showAccount',compact('account','accountTransactions'));
    }


    public function availableAccounts(Request $request){
        $user = User::find($request->user()->id);
        $availableAccounts = $user->available_accounts;
        return view('userProfile.showAvailableAccounts',compact('user','availableAccounts'));
    }
    public function moneyTransferForm(Request $request){
        $balance = $request->user()->main_account->balance;
        $available_accounts_edit = $request->user()->available_accounts_edit;
        $target_user = User::find($request->target_id)??0;
        return view('userProfile.moneyTransferForm',compact('balance','target_user','available_accounts_edit'));
    }
    public function userInfo($id){
        $message = User::find($id)->name??'ИНН не найден';
        return response()->json(['msg'=>$message]);
    }
    public function moneyTransfer(Request $request){
        $init_id = $request->init_id??$request->user()->id;
        $user = User::find($init_id);
        $params = $request->all();
        $params['init_id'] = $user->id;
        Validator::make($params,[
            'init_id'=>['required','nullable','integer','exists:users,id',function ($attribute, $value, $fail) use($user){
                if (!request()->user()->checkAvailableEdit($value)) {
                    $fail('Не балуй. Этот счет тебе недоступен');
                }
            }],
            'target_id'=>'required|integer|exists:users,id',
            'amount'=>"required|integer|regex:/^\d+(\.\d{1,2})?$/|min:0|max:".$user->main_account->balance,
            'message'=>"required|string|min:25|max:1000",
        ],[
            'target_id.exists'=>'Получателя не существует',
            'target_id.required'=>'Укажи получателя',
            'amount.required'=>'Укажи сумму',
            'amount.regex'=>'Не балуй. Введи корректно',
            'amount.min'=>'Не балуй. Введи корректно',
            'amount.max'=>'Надо подкопить',
            'message.max'=>'Короче моржно?',
            'message.required'=>'Напиши комментарий',
            'message.min'=>'Напиши побольше, а то потом не вспомнишь куда деньги дел',
            'init_id.exists'=>'Отправителя не существует',
            'init_id.required'=>'Укажи отправителя',

        ])->validate();
        $createParams = $request->all();
        $createParams['account_init_id'] = $user->main_account->id;
        $createParams['account_target_id'] = User::find($params['target_id'])->main_account->id;
        $transaction = $this->createTransaction($createParams);
        return redirect()->route('userProfileShow')->with(['transaction'=>$transaction->toArray()]);
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

    protected function createTransaction($params){
        $targetAccount = Account::find($params['account_target_id']); dump($targetAccount->balance);
        $targetAccount->balance += $params['amount'];
        $targetAccount->save();

        $initAccount = Account::find($params['account_init_id']);
        $initAccount->balance -= $params['amount'];
        $initAccount->save();

        $transaction = new Transaction();
        $transaction->account_target_id = $params['account_target_id'];
        $transaction->amount = $params['amount'];
        $transaction->message = htmlspecialchars(trim($params['message']));
        $transaction->account_init_id = $params['account_init_id'];
        $transaction->save();
        return $transaction;
    }


}

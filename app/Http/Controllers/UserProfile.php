<?php

namespace App\Http\Controllers;

use App\TransactionType;
use App\User;
use Dotenv\Exception\ValidationException;
use Hash;
use Illuminate\Http\Request;
use App\Account;
use App\Transaction;
use Illuminate\Validation\Rule;
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
        if ($mainAccount){
            $mainTransactions = Transaction::where('account_init_id',$mainAccount->id)
                ->orWhere('account_target_id',$mainAccount->id)
                ->orderBy('created_at','desc')->paginate(10);
        }

        return view('userProfile.home',compact('user','mainAccount','availableAccounts','mainTransactions'));
    }

    public function showAccount($id){
        $account = Account::where('id',$id)->with(['user'])->first();
        if (!$account){
            abort(404);
        }
        if (!request()->user()->checkAvailableEdit($id)){
            abort(403);
        }
        $accountTransactions = Transaction::where('account_init_id',$account->id)
            ->orWhere('account_target_id',$account->id)
            ->orderBy('created_at','desc')->paginate(10);
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
        $transactionTypes = TransactionType::all();
        return view('userProfile.moneyTransferForm',compact('balance','target_user','available_accounts_edit','transactionTypes'));
    }
    public function userInfo($id){
        $Account = Account::find($id);
        $message = $Account->name??$Account->user->name??'Неизвестный ИНН';
        return response()->json(['msg'=>$message]);
    }
    public function moneyTransfer(Request $request){
        $params = $request->all();
        $params['init_id'] = $accountInitID = $request->init_id??$request->user()->main_account->id;
        $accountInit = Account::find($params['init_id']);
        $validator = Validator::make($params,[
            'init_id'=>['required','integer','exists:accounts,id',function ($attribute, $value, $fail){
                if (!request()->user()->checkAvailableEdit($value)) {
                    $fail('Не балуй. Этот счет тебе недоступен');
                }
            }],
            'target_id'=>'required|integer|exists:accounts,id',
            'amount'=>"required|integer|regex:/^\d+(\.\d{1,2})?$/|min:0|max:".$accountInit->balance,
            'message'=>"required|string|min:25|max:1000",
            'transaction_type_id'=>'required:in'.implode(',', TransactionType::all()->getQueueableIds())
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

        ]);
        $validator->validate();
        $createParams = $request->all();
        $createParams['account_init_id'] = $accountInit->id;
        $createParams['account_target_id'] = $params['target_id'];
        $transaction = $this->createTransaction($createParams);
        return redirect()->route('userProfileShow')->with(['transaction'=>$transaction->toArray()]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = request()->user();
        return view('userProfile.editForm',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'crew'=>'required|integer|max:7',
        ],[
            'crew.required'=>'Введи номер экипажа'
        ])->validate();

        $user = $request->user();
        $user->name = $request->name;
        $user->crew = $request->crew;
        $user->save();
        return redirect()->back()->with(['success'=>'Изменения сохранены']);
    }
    public function updatePasswordForm(){
        return view('userProfile.updatePasswordForm');
    }
    public function updatePassword(Request $request){
        if (!Hash::check($request->now_password, request()->user()->password)){
            throw \Illuminate\Validation\ValidationException::withMessages(['now_password'=>'Неверный текущий пароль']);
        }
        $this->validate($request,[
            'now_password' => ['required'],
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ],[
            'password.required'=>'Укажите новый пароль',
            'password_confirmation.required'=>'Укажите подтверждение нового пароля',
            'password.confirmed'=>'Новый пароль и подтверждение не совпадают',
        ]);
        request()->user()->password = Hash::make($request->password);
        request()->user()->save();
        return redirect()->route('profileEditForm')->with(['password'=>'ok']);
    }

    protected function createTransaction($params){
        $targetAccount = Account::find($params['account_target_id']);
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
        $transaction->transaction_type_id = $params['transaction_type_id'];
        $transaction->save();
        return $transaction;
    }


}

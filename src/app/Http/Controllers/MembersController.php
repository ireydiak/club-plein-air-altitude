<?php

namespace App\Http\Controllers;

use App\Database\Transaction\MemberTransaction;
use App\Domain\Member;
use App\Http\Requests\UserStoreRequest;
use App\User;
use DB;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class MembersController extends Controller
{
    protected $transaction;

    public function __construct() {
        $config = new Configuration();

        $connectionParams = array(
            'dbname' => 'statut',
            'user' => 'statut',
            'password' => 'statut',
            'host' => '172.30.0.2',
            'driver' => 'pdo_mysql',
        );

        $conn = DriverManager::getConnection($connectionParams, $config);
        $this->transaction = new MemberTransaction($conn);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('members/index', [
           'members' => $this->transaction->all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('members/create', [
            'model' => json_encode(Member::form())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        $validated = $request->validated();

        try {
            if (($attributes = $this->transaction->create($validated)) !== null) {
                return response()->json(['message' => 'Membre ajouté avec succès', 'user' => new Member($attributes)]);
            } else {
                return response()->json(['message' => 'Erreur dans l\'ajout du membre.'], 422);
            }

        } catch (DBALException $e) {
            return response()->json(['message' => $e->getMessage(), 'errors' => []], 422);
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
        return response('unimplemented');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (($member = $this->transaction->findById($id)) !== null) {
            return view('members.edit', [
                'member' => $this->transaction->findById($id)
            ]);
        }

        die('not found');
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
        if (($member = $this->transaction->findById($id)) !== null) {
            return response()->json(['message' => 'Member modifié avec succès', 'member' => $member]);
        }

        return response()->json(['message' => 'Le membre n\'existe pas', 'errors' => []], 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response('unimplemented');
    }
}

<?php

namespace App\Http\Controllers;

use App\Database\Transaction\MemberTransaction;
use App\Domain\Member;
use App\Http\Requests\UserStoreRequest;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Propaganistas\LaravelPhone\PhoneNumber;

class MembersController extends Controller
{
    protected $transaction;

    public function __construct() {
        $config = new Configuration();

        $connectionParams = array(
            'dbname' => 'statut',
            'user' => 'statut',
            'password' => 'statut',
            'host' => '172.30.0.4',
            'driver' => 'pdo_mysql',
        );

        $conn = DriverManager::getConnection($connectionParams, $config);
        $this->transaction = new MemberTransaction($conn);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('members/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserStoreRequest $request
     * @return JsonResponse
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $validated['isPermanent'] = ($validated['role'] !== 'Membre');
            $validated['isAdmin'] = ($validated['role'] === 'Admin');
            $validated['password'] = (isset($validated['password']) ? $validated['password'] : $validated['email']);
            if (isset($validated['phone']) && !empty($validated['phone'])) {
                $validated['phone'] = PhoneNumber::make($validated['phone'])->ofCountry($validated['phoneRegion'])->formatE164();
            }
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
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (($member = $this->transaction->findById($id)) !== null) {
            $attributes = $request->all();
            $attributes['isPermanent'] = ($request->input('role') !== 'Membre');
            $attributes['isAdmin'] = ($request->input('role') === 'Admin');
            $this->transaction->update($id, $attributes);
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

    public function importCSV() {
        $row = 1;
        if (($handle = fopen("gestion.csv", "r")) !== FALSE) {
            while (($line = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row > 1) {
                    list($uid, $firstName, $lastName, $cip, $phoneNumber, $facebook, $email) = $line;

                    try {
                        $phoneNumber = PhoneNumber::make($phoneNumber, ['CA', 'FR'])->formatE164();
                    } catch (\Exception $e) {
                        $phoneNumber = null;
                    }

                    if (!empty($uid) && !empty($firstName) && !empty($lastName)) {
                        $this->transaction->create([
                            'firstName' => $firstName,
                            'lastName' => $lastName,
                            'cip' => (preg_match('/[a-z]{4}[0-9]{4}/', $cip) ? $cip : null) ,
                            'password' => app('hash')->make($phoneNumber) ?? app('hash')->make('default'),
                            'facebookLink' => null,
                            'email' => (!empty($email) ? $email : sprintf('%s.%s@usherbrooke.ca',$firstName, $lastName)),
                            'phone' => $phoneNumber,
                            'isPermanent' => false,
                            'isAdmin' => false
                        ]);
                    }
                }
                $row += 1;
            }
            fclose($handle);
        }
    }
}

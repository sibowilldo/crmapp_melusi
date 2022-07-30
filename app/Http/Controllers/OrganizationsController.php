<?php

namespace App\Http\Controllers;

use App\Models\organization;
use App\Models\Account;
use Illuminate\Http\Request;

class OrganizationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = Auth::user();

        if(auth()->user()->hasRole('super_admin'))
        {
            $organization = Organization::paginate();
            return view('organizations.index', compact('organization'));
        }
        $organization = Organization::where('account_id','=',auth()->user()->account_id)->get();


        return view('organizations.index', compact('organization'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $this->authorize('create-organizations', $organization);
        $account = Account::all();
        return view('organizations.create')->with('account',$account);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
          //
          $request->validate([
            'name'=> 'required',
            'email' => 'required',
            'city'=> 'required',
            'phone' => 'required',
            'country'=> 'required',
            'region'=> 'required',
            'address'=> 'required',
            'postal_code'=> 'required',
            'account_id'=> 'required',




        ]);

        organization::create($request->all());
        return redirect()->route('organizations.index')
                        ->with('success','Contact created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\organization  $organizations
     * @return \Illuminate\Http\Response
     */
    public function show(organization $organization)
    {

        if(auth()->user()->hasRole('admin') && (Auth::user()->account_id===$organization->account_id))
        {
            return view('organizations.show',compact('organization'));
        }
        $this->authorize('can-read-organizations',$organization);
        $this->authorize('can-view-own-org',$organization);

        return view('organizations.show',compact('organization'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\organization  $organizations
     * @return \Illuminate\Http\Response
     */
    public function edit(organization $organization)
    {
        //
        $account = Account::all();

        return view('organizations.edit',compact('organization'))->with('account',$account);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\organization  $organizations
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, organization $organization)
    {
        //
         //
         $request->validate([
            'name'=> 'required',
            'email' => 'required',
            'city'=> 'required',
            'phone' => 'required',
            'country'=> 'required',
            'region'=> 'required',
            'address'=> 'required',
            'postal_code'=> 'required',
            'account_id'=> 'required',




        ]);

        $organization->update($request->all());

        return redirect()->route('organizations.index')
                        ->with('success','account name updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\organization  $organizations
     * @return \Illuminate\Http\Response
     */
    public function destroy(organization $organization)
    {
        //
        $organization->delete();


        return redirect()->route('organizations.index')
                         ->with('success','Organization deleted successfully');
    }
}

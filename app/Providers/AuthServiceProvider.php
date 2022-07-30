<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('delete-accounts', function (User $user) {
            return ($user->hasRole('super_admin'))
                ? Response::allow()
                : Response::denyWithStatus(403);
        });

        Gate::define('read-accounts',function(User $user,Account $account)
        {
            return ($user->hasAnyRole(['admin', 'user'])
                && ($account->id === $user->account_id))
                ? Response::allow()
                : Response::denyWithStatus(403);

        });

        Gate::define('create-accounts', function (User $user) {
            return ($user->hasRole('super_admin'))
                ? Response::allow()
                : Response::denyWithStatus(403);
        });

        Gate::define('read-organizations',function(User $user, Organization $organization)
        {
            return ($user->hasAnyRole(['admin', 'user'])
                && ($organization->account_id === $user->account_id))
                ? Response::allow()
                : Response::denyWithStatus(403);

        });

        Gate::define('read-users',function(User $user)
        {
          return  $user->id === auth()->id();
        });

        Gate::define('read-contacts',function(User $user,Contact $contact )
        {
            return ($user->hasAnyRole(['admin', 'user'])
                && ($contact->account_id === $user->account_id)
                && ($user->organization_id === $contact->organization_id))
                ? Response::allow()
                : Response::denyWithStatus(403);
        });

        Gate::define('create-organizations', function (User $user) {
            return ($user->hasRole('admin'))
                ? Response::allow()
                : Response::denyWithStatus(403);
        });

        Gate::define('create-contacts', function (User $user) {
            return ($user->hasRole('admin'))
                ? Response::allow()
                : Response::denyWithStatus(403);
        });

        Gate::define('update-contact', function (User $user, Contact $contact) {
            return ($user->hasRole('admin')
                && ($user->account_id === $contact->account_id)
                && ($contact->organization_id === $user->organization_id))
                ? Response::allow()
                : Response::denyWithStatus(403);
        });

        Gate::define('delete-contacts', function (User $user, Contact $contact) {
            return ($user->hasRole('admin')
                && ($user->account_id === $contact->account_id)
                && ($contact->organization_id === $user->organization_id))
                ? Response::allow()
                : Response::denyWithStatus(404);
        });


        Gate::define('update-organizations', function (User $user,Organization $organization) {
            return ($user->hasRole('admin')
                && ($user->account_id === $organization->account_id))
                ? Response::allow()
                : Response::denyWithStatus(403);
});

        Gate::define('delete-organizations', function (User $user,Organization $organization) {
            return ($user->hasRole('admin')
                && ($user->account_id === $organization->account_id)
                && ($organization->id === $user->organization_id))
                ? Response::allow()
                : Response::denyWithStatus(403);
        });

        Gate::before(function($user,$ability)
        {
           return $user->hasRole('super_admin')?true :null;
        });

       Gate::define('can-view-own',function(User $user,User $currentUser)
       {
          return ($user->id === $currentUser->id)
              || ($user->hasRole('admin')
              && ($user->account_id === $currentUser->account_id)
              && ($currentUser->hasRole('Super_admin')));
       });

       Gate::define('can-view-own-cont',function(User $user, Contact $contact)
       {
          return ($user->account_id === $contact->account_id)
                && ($user->organization_id === $contact->organization_id);
       });

       Gate::define('can-view-own-org',function(User $user, Organization $organization)
       {
          return ($user->account_id === $organization->account_id);
       });

       Gate::define('can-view-own-acc',function(User $user, Account $account)
       {
          return ($user->account_id === $account->id);
       });

       Gate::define('show-accounts',function(User $user, Account $account)
       {
          return ($user->account_id === $account->id);
       });

       Gate::define('show-organizations',function(User $user, Organization $organization)
       {
          return ($user->account_id === $organization->account_id)
                && ($user->organization_id === $organization->id);
       });
    }
}

<?php

namespace App\Http\View\Composers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\View\View;

class AuthenticatedComposer
{
     /**
     * The authenticated Client
     *
     * @var  \App\User
     */
    protected $user;
    
    /**
     * Create a new authenticated composer.
     *
     * @param  null|\Illuminate\Contracts\Auth\Authenticatable  $user
     */
    public function __construct(Authenticatable $user = null)
    {
        $this->user = $user;
    }
    
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('authenticated', $this->user);
    }
}
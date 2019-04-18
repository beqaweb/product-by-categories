<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class CanManageProduct
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authUser = User::query()->find(auth()->user()->id);
        $product = $request->route('product');
        if (($product && !$authUser->categories->contains($product->id)) && !$authUser->can('manage product')) {
            return redirect('products/forbidden');
        }

        return $next($request);
    }
}

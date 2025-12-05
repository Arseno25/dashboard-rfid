<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        return view('auth.login', [
            'redirectTo' => $this->sanitizeRedirect($request->query('redirect')),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $redirectTo = $this->sanitizeRedirect($request->string('redirect')->toString());

        return $redirectTo
            ? redirect()->intended($redirectTo)
            : redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function sanitizeRedirect(?string $target): ?string
    {
        if (! $target) {
            return null;
        }

        $target = trim($target);

        if ($target === '' || Str::startsWith($target, '//')) {
            return null;
        }

        if (Str::startsWith($target, ['http://', 'https://'])) {
            $appUrl = rtrim((string) config('app.url'), '/');

            if ($appUrl === '') {
                return null;
            }

            if (Str::startsWith($target, $appUrl)) {
                $target = '/'.ltrim(Str::after($target, $appUrl), '/');
            } else {
                return null;
            }
        }

        if (! Str::startsWith($target, '/')) {
            return null;
        }

        return $target ?: null;
    }
}

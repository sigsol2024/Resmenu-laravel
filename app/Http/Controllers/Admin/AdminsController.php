<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\Admin\AdminAccountService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AdminsController extends Controller
{
    public function __construct(private readonly AdminAccountService $accounts) {}

    public function index()
    {
        $admins = Admin::query()->orderBy('id')->get();
        $primary = $this->accounts->primaryAdmin();

        return view('admin.admins.index', [
            'admins' => $admins,
            'primaryAdminId' => $primary?->id,
            'currentAdmin' => request()->user('admin'),
            'permissionKeys' => Admin::permissionKeys(),
        ]);
    }

    public function create()
    {
        return view('admin.admins.create', [
            'permissionKeys' => Admin::permissionKeys(),
            'permissionLabels' => $this->permissionLabels(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedPayload($request);
        /** @var Admin $actor */
        $actor = $request->user('admin');

        $this->accounts->create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'is_super_admin' => $data['role'] === 'super',
            'is_active' => (bool) $data['is_active'],
            'permissions' => $data['permissions'],
        ], $actor);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Administrator created.');
    }

    public function edit(Admin $admin)
    {
        return view('admin.admins.edit', [
            'admin' => $admin,
            'isPrimary' => $this->accounts->isPrimary($admin),
            'permissionKeys' => Admin::permissionKeys(),
            'permissionLabels' => $this->permissionLabels(),
        ]);
    }

    public function update(Request $request, Admin $admin)
    {
        $data = $this->validatedPayload($request, $admin);
        /** @var Admin $actor */
        $actor = $request->user('admin');

        try {
            $this->accounts->update($admin, [
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => $data['password'] ?: null,
                'is_super_admin' => $data['role'] === 'super',
                'is_active' => (bool) $data['is_active'],
                'permissions' => $data['permissions'],
            ], $actor);
        } catch (ValidationException $e) {
            throw $e;
        }

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Administrator updated.');
    }

    public function destroy(Request $request, Admin $admin)
    {
        /** @var Admin $actor */
        $actor = $request->user('admin');

        $this->accounts->delete($admin, $actor);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Administrator deleted.');
    }

    /**
     * @return array{
     *     username: string,
     *     email: string,
     *     password?: string,
     *     role: string,
     *     is_active: bool,
     *     permissions: array<string, bool>
     * }
     */
    private function validatedPayload(Request $request, ?Admin $existing = null): array
    {
        $passwordRules = $existing
            ? ['nullable', 'string', Password::min(8)->letters()->numbers()]
            : ['required', 'string', Password::min(8)->letters()->numbers()];

        $data = $request->validate([
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('admins', 'username')->ignore($existing?->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('admins', 'email')->ignore($existing?->id),
            ],
            'password' => $passwordRules,
            'role' => ['required', Rule::in(['super', 'regular'])],
            'is_active' => ['nullable', 'boolean'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['nullable'],
        ]);

        $permissions = [];
        foreach (Admin::permissionKeys() as $key) {
            $permissions[$key] = $request->boolean('permissions.'.$key);
        }

        return [
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'] ?? null,
            'role' => $data['role'],
            'is_active' => $request->boolean('is_active'),
            'permissions' => $permissions,
        ];
    }

    /** @return array<string, string> */
    private function permissionLabels(): array
    {
        return [
            'subscription_plans' => 'Subscription Plans',
            'subscriptions' => 'Subscriptions',
            'payments' => 'Payments',
            'payment_settings' => 'Payment Settings',
            'templates' => 'Templates',
            'qr_templates' => 'QR Templates',
            'settings' => 'Settings',
            'crm' => 'CRM',
        ];
    }
}

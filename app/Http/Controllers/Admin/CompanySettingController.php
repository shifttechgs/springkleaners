<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Company;
use App\Support\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanySettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.company-settings.edit', [
            'name' => Company::name(),
            'address' => implode("\n", Company::addressLines()),
            'regNo' => Company::regNo(),
            'cell' => Company::cell(),
            'email' => Company::email(),
            'bankName' => Company::bankName(),
            'branchCode' => Company::branchCode(),
            'accountNo' => Company::accountNo(),
            'referenceNote' => Company::referenceNote(),
            'reviewUrl' => Company::reviewUrl(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:1000',
            'reg_no' => 'nullable|string|max:100',
            'cell' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'bank_name' => 'required|string|max:100',
            'branch_code' => 'required|string|max:20',
            'account_no' => 'required|string|max:50',
            'reference_note' => 'nullable|string|max:255',
            'review_url' => 'nullable|url|max:2000',
        ]);

        Setting::set('company_name', $data['name']);
        Setting::set('company_address', $data['address']);
        Setting::set('company_reg_no', $data['reg_no'] ?? '');
        Setting::set('company_cell', $data['cell']);
        Setting::set('company_email', $data['email']);
        Setting::set('banking_bank_name', $data['bank_name']);
        Setting::set('banking_branch_code', $data['branch_code']);
        Setting::set('banking_account_no', $data['account_no']);
        Setting::set('banking_reference_note', $data['reference_note'] ?? '');
        Setting::set('review_url', $data['review_url'] ?? '');

        return back()->with('status', 'Business details updated.');
    }
}

@extends('admin.layout')
@section('title', 'Business Details')
@section('content')

    @include('admin.settings._tabs')

    @if (session('status'))
        <div class="mb-5 px-4 py-3 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-700 text-[13px] font-medium">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 px-4 py-3 bg-rose-50 border border-rose-100 rounded-xl text-rose-600 text-[13px] font-medium">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.company-settings.update') }}">
        @csrf
        @method('PUT')

        <div class="card overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-line bg-canvas">
                <p class="font-bold text-ink text-[13.5px]">Business Info</p>
                <p class="text-muted text-[12px] mt-0.5">Appears on quotes, invoices and client emails.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5 border-b border-line">
                <div class="lg:col-span-2">
                    <p class="font-semibold text-ink text-[13.5px]">Business name</p>
                </div>
                <div class="lg:col-span-1">
                    <input type="text" name="name" value="{{ old('name', $name) }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5 border-b border-line">
                <div class="lg:col-span-2">
                    <p class="font-semibold text-ink text-[13.5px]">Address</p>
                    <p class="text-muted text-[12.5px] mt-1 max-w-lg">One line per address line.</p>
                </div>
                <div class="lg:col-span-1">
                    <textarea name="address" rows="4" required
                              class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors resize-none">{{ old('address', $address) }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5 border-b border-line">
                <div class="lg:col-span-2">
                    <p class="font-semibold text-ink text-[13.5px]">Registration number</p>
                </div>
                <div class="lg:col-span-1">
                    <input type="text" name="reg_no" value="{{ old('reg_no', $regNo) }}"
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5 border-b border-line">
                <div class="lg:col-span-2">
                    <p class="font-semibold text-ink text-[13.5px]">Cell number</p>
                </div>
                <div class="lg:col-span-1">
                    <input type="text" name="cell" value="{{ old('cell', $cell) }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5">
                <div class="lg:col-span-2">
                    <p class="font-semibold text-ink text-[13.5px]">Email</p>
                </div>
                <div class="lg:col-span-1">
                    <input type="email" name="email" value="{{ old('email', $email) }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>
        </div>

        <div class="card overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-line bg-canvas">
                <p class="font-bold text-ink text-[13.5px]">Banking Details</p>
                <p class="text-muted text-[12px] mt-0.5">Shown on quotes (for deposits) and invoices (for EFT payment).</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5 border-b border-line">
                <div class="lg:col-span-2">
                    <p class="font-semibold text-ink text-[13.5px]">Bank name</p>
                </div>
                <div class="lg:col-span-1">
                    <input type="text" name="bank_name" value="{{ old('bank_name', $bankName) }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5 border-b border-line">
                <div class="lg:col-span-2">
                    <p class="font-semibold text-ink text-[13.5px]">Branch code</p>
                </div>
                <div class="lg:col-span-1">
                    <input type="text" name="branch_code" value="{{ old('branch_code', $branchCode) }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5 border-b border-line">
                <div class="lg:col-span-2">
                    <p class="font-semibold text-ink text-[13.5px]">Account number</p>
                </div>
                <div class="lg:col-span-1">
                    <input type="text" name="account_no" value="{{ old('account_no', $accountNo) }}" required
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5">
                <div class="lg:col-span-2">
                    <p class="font-semibold text-ink text-[13.5px]">Reference note</p>
                    <p class="text-muted text-[12.5px] mt-1 max-w-lg">Shown alongside the banking details so clients know what reference to use.</p>
                </div>
                <div class="lg:col-span-1">
                    <input type="text" name="reference_note" value="{{ old('reference_note', $referenceNote) }}"
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>
        </div>

        <div class="card overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-line bg-canvas">
                <p class="font-bold text-ink text-[13.5px]">Review Link</p>
                <p class="text-muted text-[12px] mt-0.5">The QR code in the Thank You email/WhatsApp message points here.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 px-6 py-5">
                <div class="lg:col-span-2">
                    <p class="font-semibold text-ink text-[13.5px]">Review URL</p>
                </div>
                <div class="lg:col-span-1">
                    <input type="url" name="review_url" value="{{ old('review_url', $reviewUrl) }}"
                           class="w-full bg-white border border-line rounded-xl px-3.5 py-2.5 text-[13px] text-ink focus:border-navy focus:outline-none transition-colors">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="btn-primary">Save Changes</button>
        </div>
    </form>

@endsection

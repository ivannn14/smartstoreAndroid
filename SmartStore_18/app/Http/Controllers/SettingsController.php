<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\CompanyProfile;
use App\Models\PaymentType;  // Add this line
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    // Show settings page
    public function index()
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    // Update user settings
    public function update(Request $request)
    {
        // Validate the request
        $request->validate([
            'email_notifications' => 'nullable|boolean',
            'two_factor' => 'nullable|boolean',
        ]);

        // Save settings to the authenticated user
        $user = Auth::user();
        $user->email_notifications = $request->has('email_notifications');
        $user->two_factor = $request->has('two_factor');
        $user->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    public function companyProfile()
    {
        $profile = CompanyProfile::first();
        return view('settings.company-profile', compact('profile'));
    }

    public function updateCompanyProfile(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'tax_number' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $profile = CompanyProfile::firstOrNew();

        if ($request->hasFile('logo')) {
            if ($profile->logo) {
                Storage::disk('public')->delete($profile->logo);
            }
            $path = $request->file('logo')->store('company', 'public');
            $validated['logo'] = $path;
        }

        $profile->fill($validated);
        $profile->save();

        return redirect()->route('settings.company-profile')
            ->with('success', 'Company profile updated successfully');
    }

    public function siteSettings()
    {
        $settings = \App\Models\SiteSettings::first();
        return view('settings.site', compact('settings'));
    }

    public function updateSiteSettings(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'default_currency' => 'required|string|max:3',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
            'footer_text' => 'nullable|string',
            'maintenance_mode' => 'boolean'
        ]);

        $settings = \App\Models\SiteSettings::firstOrNew();
        $settings->fill($validated);
        $settings->maintenance_mode = $request->has('maintenance_mode');
        $settings->save();

        return redirect()->route('settings.site')
            ->with('success', 'Site settings updated successfully');
    }

    public function units()
    {
        return view('settings.units');
    }

    public function paymentTypes()
    {
        $paymentTypes = PaymentType::paginate(10);
        return view('settings.payment-types', compact('paymentTypes'));
    }

    public function storePaymentType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean'
        ]);
    
        PaymentType::create($validated);
        return response()->json(['message' => 'Payment type created successfully']);
    }
    
    public function deletePaymentType($id)
    {
        $paymentType = PaymentType::findOrFail($id);
        $paymentType->delete();
        return response()->json(['message' => 'Payment type deleted successfully']);
    }
}

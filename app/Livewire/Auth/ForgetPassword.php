<?php

namespace App\Livewire\Auth;

use App\Models\Customer;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

#[Layout('components.shop-layout')]
class ForgetPassword extends Component
{
    public $email = '';
    public $showEmailSent = false;
    public $resetToken = '';
    public $newPassword = '';
    public $confirmPassword = '';
    public $showNewPassword = false;
    public $showConfirmPassword = false;
    public $resetEmail = '';
    public $hasValidToken = false;
    public $token = null;

    // Define validation rules as a protected property
    protected $rules = [
        'email' => 'required|email|exists:customers,email',
    ];

    // Custom validation messages
    protected $messages = [
        'email.required' => 'Please enter your email address.',
        'email.email' => 'Please enter a valid email address.',
        'email.exists' => 'This email is not registered in our system.',
    ];

    public function mount($token = null)
    {
        // Check if token is passed as route parameter or query parameter
        if ($token) {
            $this->token = $token;
        } elseif (request()->has('token')) {
            $this->token = request()->query('token');
        }

        // Get email from query parameter
        if (request()->has('email')) {
            $this->resetEmail = urldecode(request()->query('email'));
            $this->email = urldecode(request()->query('email'));
        }

        // Validate token if present
        if ($this->token) {
            $this->resetToken = $this->token;
            $this->validateToken();
        }
    }

    public function validateToken()
    {
        $email = $this->resetEmail ?: $this->email;
        
        if (empty($email)) {
            $this->hasValidToken = false;
            session()->flash('error', 'Invalid reset token. Please request a new reset link.');
            return;
        }

        // Find the reset record by email
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$resetRecord) {
            $this->hasValidToken = false;
            session()->flash('error', 'Invalid reset token. Please request a new reset link.');
            return;
        }

        // Verify the token using Hash::check
        if (!Hash::check($this->token, $resetRecord->token)) {
            $this->hasValidToken = false;
            session()->flash('error', 'Invalid or expired reset token. Please request a new reset link.');
            return;
        }

        // Check if token is not expired (1 hour)
        if (now()->diffInHours($resetRecord->created_at) > 1) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            $this->hasValidToken = false;
            session()->flash('error', 'Reset link has expired. Please request a new one.');
            return;
        }

        // Token is valid
        $this->hasValidToken = true;
        $this->resetToken = $this->token;
        $this->resetEmail = $email;
        $this->email = $email;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function sendResetLink()
    {
        $this->validate();

        // Check if customer exists and is active
        $customer = Customer::where('email', $this->email)->first();

        if (!$customer || $customer->status !== 'active') {
            $this->addError('email', 'Your account is not active. Please contact support.');
            return;
        }

        // Generate reset token
        $token = Str::random(64);

        // Store token in password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $this->email],
            [
                'email' => $this->email,
                'token' => bcrypt($token),
                'created_at' => now()
            ]
        );

        try {
            // Send email with reset link
            Mail::to($this->email)->send(new ResetPasswordMail($token, $this->email));

            $this->showEmailSent = true;
            $this->email = '';

            session()->flash('success', 'Password reset link has been sent to your email address.');

        } catch (\Exception $e) {
            $this->addError('email', 'Unable to send reset email. Please try again later.');
            \Log::error('Password reset email failed: ' . $e->getMessage());
        }
    }

    public function resetPassword()
    {
        // Define validation rules for password reset
        $passwordRules = [
            'newPassword' => 'required|min:8|same:confirmPassword',
            'confirmPassword' => 'required|min:8',
        ];

        $passwordMessages = [
            'newPassword.required' => 'Please enter a new password.',
            'newPassword.min' => 'Password must be at least 8 characters.',
            'newPassword.same' => 'Passwords do not match.',
            'confirmPassword.required' => 'Please confirm your password.',
            'confirmPassword.min' => 'Password must be at least 8 characters.',
        ];

        $this->validate($passwordRules, $passwordMessages);

        $email = $this->resetEmail ?: $this->email;

        if (empty($email)) {
            session()->flash('error', 'Invalid reset request. Please try again.');
            return redirect()->route('customer.forgot-password');
        }

        // Verify token again before resetting
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$resetRecord) {
            session()->flash('error', 'Invalid or expired reset token. Please request a new reset link.');
            return redirect()->route('customer.forgot-password');
        }

        // Verify the token using Hash::check
        if (!Hash::check($this->resetToken, $resetRecord->token)) {
            session()->flash('error', 'Invalid or expired reset token. Please request a new reset link.');
            return redirect()->route('customer.forgot-password');
        }

        // Check if token is not expired (1 hour)
        if (now()->diffInHours($resetRecord->created_at) > 1) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            session()->flash('error', 'Reset link has expired. Please request a new one.');
            return redirect()->route('customer.forgot-password');
        }

        // Update customer password
        $customer = Customer::where('email', $email)->first();

        if (!$customer) {
            session()->flash('error', 'User not found. Please try again.');
            return redirect()->route('customer.forgot-password');
        }

        $customer->password = Hash::make($this->newPassword);
        $customer->save();

        // Delete reset token
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        session()->flash('success', 'Password has been reset successfully. You can now login with your new password.');

        return redirect()->route('customer.login');
    }

    public function toggleNewPassword()
    {
        $this->showNewPassword = !$this->showNewPassword;
    }

    public function toggleConfirmPassword()
    {
        $this->showConfirmPassword = !$this->showConfirmPassword;
    }

    public function render()
    {
        return view('livewire.auth.forget-password');
    }
}
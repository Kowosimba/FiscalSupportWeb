<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Comment;
use App\Models\CallLog;
use App\Models\Faq;
use App\Models\Blog;
use App\Models\NewsletterSubscriber;
use App\Models\Service;
use App\Models\CustomerContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Notifications\CustomerTicketResolvedNotification;
use App\Notifications\CustomerTicketCreatedNotification;
use App\Notifications\TicketAssignedNotification;
use App\Notifications\JobAssignedNotification;
use App\Notifications\JobCreatedNotification;
use App\Notifications\JobStatusUpdated;
use App\Notifications\NewTicketForAssignmentNotification;
use App\Notifications\TechnicianOutstandingTicketNotification;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SupportTicketController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    // Security constants
    private const MAX_SUBMISSIONS_PER_HOUR_IP = 3;
    private const MAX_SUBMISSIONS_PER_DAY_EMAIL = 5;
    private const MAX_GLOBAL_SUBMISSIONS_PER_HOUR = 100;
    private const SPAM_SCORE_THRESHOLD = 3;
    private const MIN_FORM_FILL_TIME = 5; // seconds
    private const MAX_FILE_SIZE = 5242880; // 5MB
    private const ALTCHA_TIMEOUT = 10; // seconds
    
    // Allowed file types
    private const ALLOWED_MIME_TYPES = [
        'application/pdf', 
        'image/jpeg', 
        'image/png', 
        'image/jpg'
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        // Initialize security logging
        $this->initializeSecurityLogging();
    }

    /**
     * Public ticket submission with enhanced security
     */
    public function store(Request $request)
    {
        $timestamp = now()->format('Y-m-d H:i:s');
        
        Log::info('🎫 TICKET STORE METHOD CALLED', [
            'form_data' => $request->except(['attachment', 'altcha-token']),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => $timestamp,
            'user' => 'Sire-bit'
        ]);

        // Security checks cascade
        $securityResult = $this->performSecurityChecks($request);
        if (!$securityResult['passed']) {
            return $this->errorResponse($securityResult['message']);
        }

        // Enhanced validation
        $validatedData = $this->validateTicketData($request);
        if (!$validatedData) {
            $this->logSecurityEvent('VALIDATION_FAILED', $request);
            return $this->errorResponse('Invalid form data. Please check your inputs and try again.');
        }

        // Multi-layer Altcha verification
        if (!$this->verifyAltcha($request)) {
            $this->logSecurityEvent('ALTCHA_VERIFICATION_FAILED', $request, [
                'token_present' => $request->filled('altcha-token'),
                'token_length' => strlen($request->input('altcha-token', ''))
            ]);
            return $this->errorResponse('Security verification failed. Please refresh the page and try again.');
        }

        try {
            DB::beginTransaction();

            // Process attachment with enhanced security
            $attachmentPath = $this->handleAttachment($request);

            // Create ticket with enhanced data and security context
            $ticket = $this->createTicket($validatedData, $attachmentPath, $request);

            Log::info('🎫 TICKET CREATED SUCCESSFULLY', [
                'ticket_id' => $ticket->id,
                'customer_email' => $ticket->email,
                'service' => $ticket->service,
                'assigned_to' => $ticket->assigned_to,
                'ip' => $request->ip(),
                'security_score' => $this->calculateSecurityScore($request),
                'timestamp' => $timestamp,
                'user' => 'Sire-bit'
            ]);

            // Send notifications - public creation context
            $this->dispatchNotifications($ticket, 'public');

            // Update rate limiting and security metrics
            $this->updateSecurityMetrics($request);

            DB::commit();

            return $this->successResponse($ticket);

        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up uploaded file if it exists
            if (isset($attachmentPath) && $attachmentPath) {
                Storage::disk('public')->delete($attachmentPath);
            }

            Log::error('Ticket submission failed', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['attachment', 'altcha-token']),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => $timestamp,
                'user' => 'Sire-bit'
            ]);

            return $this->errorResponse('Failed to submit ticket. Please try again or contact us directly.');
        }
    }

    /**
     * FIXED: Admin-side ticket creation with proper assignment notifications
     */
    public function adminStore(Request $request)
{
    Log::info('🎫 ADMIN TICKET STORE METHOD CALLED', [
        'admin_user' => Auth::user()->name,
        'admin_id' => Auth::id(),
        'request_data' => $request->except(['attachment']),
        'timestamp' => now()->format('Y-m-d H:i:s'),
    ]);

    $request->validate([
        'company_name' => 'required|string|max:255',
        'contact_details' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'service' => 'required|string|max:255',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
        'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'priority' => 'required|in:low,medium,high',
        'assigned_to' => 'nullable|exists:users,id',
    ]);

    try {
        DB::beginTransaction();

        $data = $request->only([
            'company_name',
            'contact_details',
            'email',
            'service',
            'subject',
            'message',
            'priority',
            'assigned_to',
        ]);

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $this->handleAttachment($request);
        }

        $data['status'] = 'in_progress';
        $data['ip_address'] = $request->ip();
        $data['user_agent'] = $request->userAgent();
        $data['submitted_at'] = now();

        $ticket = Ticket::create($data);
        
        Log::info('🎫 ADMIN TICKET CREATED SUCCESSFULLY', [
            'ticket_id' => $ticket->id,
            'created_by' => Auth::user()->name,
            'assigned_to' => $ticket->assigned_to,
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ]);

        // Send notifications if ticket is assigned to someone
        if ($ticket->assigned_to) {
            $this->sendAssignmentNotifications($ticket);
        }

        // Send general notifications (if you have other notification logic)
        $this->dispatchNotifications($ticket, 'admin');

        DB::commit();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ticket created successfully!',
                'ticket' => [
                    'id' => $ticket->id,
                    'subject' => $ticket->subject,
                    'status' => $ticket->status,
                    'priority' => $ticket->priority,
                    'assigned_to' => $ticket->assigned_to,
                ],
            ]);
        }

        return redirect()->route('admin.tickets.unassigned')
            ->with('success', 'Ticket #' . $ticket->id . ' created successfully!');
            
    } catch (\Exception $e) {
        DB::rollBack();

        Log::error('Admin ticket creation failed: ' . $e->getMessage(), [
            'user_id' => Auth::id(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'user' => 'Sire-bit'
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating ticket. Please try again.',
            ], 500);
        }

        return redirect()->back()
            ->with('error', 'Error creating ticket. Please try again.')
            ->withInput();
    }
}

/**
 * Send assignment notifications to the assigned technician
 */
private function sendAssignmentNotifications(Ticket $ticket)
{
    try {
        // Find the assigned user
        $assignedUser = User::find($ticket->assigned_to);
        
        if (!$assignedUser) {
            Log::warning('🎫 ASSIGNED USER NOT FOUND', [
                'ticket_id' => $ticket->id,
                'assigned_to' => $ticket->assigned_to,
                'timestamp' => now()->format('Y-m-d H:i:s'),
            ]);
            return;
        }

        Log::info('🎫 SENDING ASSIGNMENT NOTIFICATIONS', [
            'ticket_id' => $ticket->id,
            'assigned_to_id' => $assignedUser->id,
            'assigned_to_name' => $assignedUser->name,
            'assigned_to_email' => $assignedUser->email,
            'created_by' => Auth::user()->name,
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ]);

        // Send the TicketAssignedNotification (both email and database)
        $assignedUser->notify(new \App\Notifications\TicketAssignedNotification($ticket));

        Log::info('🎫 ✅ ASSIGNMENT NOTIFICATIONS SENT SUCCESSFULLY', [
            'ticket_id' => $ticket->id,
            'assigned_to_id' => $assignedUser->id,
            'assigned_to_name' => $assignedUser->name,
            'notification_channels' => ['mail', 'database'],
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ]);

        // Optional: Send immediate notification to other stakeholders
        $this->notifyStakeholders($ticket, 'ticket_created_and_assigned');

    } catch (\Exception $e) {
        Log::error('🎫 ❌ FAILED TO SEND ASSIGNMENT NOTIFICATIONS', [
            'ticket_id' => $ticket->id,
            'assigned_to' => $ticket->assigned_to,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ]);
        
        // Don't throw the exception to avoid breaking ticket creation
        // Just log the error for debugging
    }
}

/**
 * Notify other stakeholders about ticket creation/assignment
 */
private function notifyStakeholders(Ticket $ticket, string $eventType)
{
    try {
        // Get admin users who should be notified (adjust query as needed)
        $admins = User::where('role', 'admin')
                     ->where('id', '!=', Auth::id()) // Don't notify the creator
                     ->where('id', '!=', $ticket->assigned_to) // Don't duplicate for assigned user
                     ->get();

        foreach ($admins as $admin) {
            // Create a simple database notification for admins
            $admin->notifications()->create([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\TicketCreatedNotification',
                'data' => [
                    'type' => 'ticket_created',
                    'ticket_id' => $ticket->id,
                    'title' => 'New Ticket Created',
                    'message' => 'Ticket #' . $ticket->id . ' has been created and assigned to ' . 
                                ($ticket->assignedUser->name ?? 'Unknown'),
                    'action_text' => 'View Ticket',
                    'action_url' => route('admin.tickets.show', $ticket->id),
                    'ticket' => [
                        'id' => $ticket->id,
                        'subject' => $ticket->subject,
                        'company' => $ticket->company_name,
                        'priority' => $ticket->priority,
                        'status' => $ticket->status,
                    ],
                    'icon' => 'fas fa-ticket-alt',
                    'color' => 'primary',
                    'created_by' => Auth::user()->name,
                ],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Log::info('🎫 STAKEHOLDER NOTIFICATIONS CREATED', [
            'ticket_id' => $ticket->id,
            'notified_admins' => $admins->count(),
            'event_type' => $eventType,
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ]);

    } catch (\Exception $e) {
        Log::error('🎫 FAILED TO NOTIFY STAKEHOLDERS', [
            'ticket_id' => $ticket->id,
            'event_type' => $eventType,
            'error' => $e->getMessage(),
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ]);
    }
}

    /**
     * FIXED: Unified notification dispatch that handles ALL scenarios properly
     */
    private function dispatchNotifications(Ticket $ticket, string $creationContext = 'public'): void
    {
        try {
            Log::info('🎫 STARTING NOTIFICATION DISPATCH', [
                'ticket_id' => $ticket->id,
                'creation_context' => $creationContext,
                'assigned_to' => $ticket->assigned_to,
                'customer_email' => $ticket->email,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);

            // 1. Always send customer notification first
            try {
                Notification::route('mail', $ticket->email)
                    ->notify(new CustomerTicketCreatedNotification($ticket));

                Log::info('🎫 ✅ CUSTOMER NOTIFICATION SENT', [
                    'ticket_id' => $ticket->id,
                    'customer_email' => $ticket->email,
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ]);
            } catch (\Exception $e) {
                Log::error('🎫 ❌ CUSTOMER NOTIFICATION FAILED', [
                    'ticket_id' => $ticket->id,
                    'customer_email' => $ticket->email,
                    'error' => $e->getMessage()
                ]);
            }

            // 2. Handle assignment notification if ticket is assigned
            if ($ticket->assigned_to) {
                Log::info('🎫 TICKET IS ASSIGNED - SENDING ASSIGNMENT NOTIFICATION', [
                    'ticket_id' => $ticket->id,
                    'assigned_to' => $ticket->assigned_to,
                    'creation_context' => $creationContext
                ]);
                
                $this->sendAssignmentNotification($ticket, $creationContext);
            } else {
                Log::info('🎫 TICKET NOT ASSIGNED - SKIPPING ASSIGNMENT NOTIFICATION', [
                    'ticket_id' => $ticket->id,
                    'creation_context' => $creationContext
                ]);
            }

            // 3. Send manager notifications based on context
            $this->sendManagerNotifications($ticket, $creationContext);

            Log::info('🎫 ✅ NOTIFICATION DISPATCH COMPLETED', [
                'ticket_id' => $ticket->id,
                'creation_context' => $creationContext,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            Log::error('🎫 ❌ NOTIFICATION DISPATCH FAILED', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Don't fail the ticket creation if notifications fail
        }
    }

    /**
     * FIXED: Assignment notification with detailed logging and error handling
     */
    private function sendAssignmentNotification(Ticket $ticket, string $context = 'unknown'): void
    {
        try {
            Log::info('🎫 ATTEMPTING TO SEND ASSIGNMENT NOTIFICATION', [
                'ticket_id' => $ticket->id,
                'assigned_to_id' => $ticket->assigned_to,
                'context' => $context,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);

            $technician = User::find($ticket->assigned_to);
            
            if (!$technician) {
                Log::error('🎫 ❌ TECHNICIAN NOT FOUND FOR ASSIGNMENT', [
                    'ticket_id' => $ticket->id,
                    'assigned_to_id' => $ticket->assigned_to,
                    'context' => $context
                ]);
                return;
            }

            Log::info('🎫 TECHNICIAN FOUND - SENDING NOTIFICATION', [
                'ticket_id' => $ticket->id,
                'technician_id' => $technician->id,
                'technician_name' => $technician->name,
                'technician_email' => $technician->email,
                'context' => $context
            ]);

            $technician->notify(new TicketAssignedNotification($ticket));
            
            Log::info('🎫 ✅ ASSIGNMENT NOTIFICATION SENT SUCCESSFULLY', [
                'ticket_id' => $ticket->id,
                'technician_id' => $technician->id,
                'technician_name' => $technician->name,
                'technician_email' => $technician->email,
                'notification_type' => 'TicketAssignedNotification',
                'context' => $context,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            Log::error('🎫 ❌ ASSIGNMENT NOTIFICATION FAILED', [
                'ticket_id' => $ticket->id,
                'assigned_to_id' => $ticket->assigned_to,
                'context' => $context,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
        }
    }

    /**
     * FIXED: Manager notifications with proper self-exclusion
     */
    private function sendManagerNotifications(Ticket $ticket, string $creationContext): void
    {
        try {
            Log::info('🎫 STARTING MANAGER NOTIFICATION PROCESS', [
                'ticket_id' => $ticket->id,
                'creation_context' => $creationContext,
                'current_user_id' => Auth::id(),
                'current_user_role' => Auth::user()->role ?? 'unknown'
            ]);

            // Get active managers
            $managers = Cache::remember('active_managers', 300, function () {
                return User::where('role', 'manager')
                          ->where('status', 'active')
                          ->get(['id', 'email', 'name']);
            });

            if ($managers->isEmpty()) {
                Log::info('🎫 NO ACTIVE MANAGERS FOUND', [
                    'ticket_id' => $ticket->id,
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ]);
                return;
            }

            $currentUserId = Auth::id();
            $currentUser = Auth::user();
            $excludedManagerId = null;

            // Determine exclusion logic based on creation context
            if ($creationContext === 'admin' && $currentUser && $currentUser->role === 'manager') {
                $excludedManagerId = $currentUserId;
                Log::info('🎫 EXCLUDING MANAGER FROM SELF-NOTIFICATION', [
                    'ticket_id' => $ticket->id,
                    'excluded_manager_id' => $excludedManagerId,
                    'excluded_manager_name' => $currentUser->name,
                    'creation_context' => $creationContext
                ]);
            }

            $notifiedCount = 0;
            $skippedCount = 0;

            foreach ($managers as $manager) {
                // Skip the manager who created the ticket
                if ($manager->id === $excludedManagerId) {
                    $skippedCount++;
                    Log::info('🎫 SKIPPING SELF-NOTIFICATION FOR MANAGER', [
                        'ticket_id' => $ticket->id,
                        'manager_id' => $manager->id,
                        'manager_name' => $manager->name,
                        'reason' => 'self_exclusion'
                    ]);
                    continue;
                }

                try {
                    $manager->notify(new NewTicketForAssignmentNotification($ticket));
                    $notifiedCount++;
                    
                    Log::info('🎫 ✅ MANAGER NOTIFICATION SENT', [
                        'ticket_id' => $ticket->id,
                        'manager_id' => $manager->id,
                        'manager_name' => $manager->name,
                        'manager_email' => $manager->email,
                        'timestamp' => now()->format('Y-m-d H:i:s')
                    ]);
                } catch (\Exception $e) {
                    Log::error('🎫 ❌ MANAGER NOTIFICATION FAILED', [
                        'ticket_id' => $ticket->id,
                        'manager_id' => $manager->id,
                        'manager_name' => $manager->name,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            Log::info('🎫 ✅ MANAGER NOTIFICATION PROCESS COMPLETED', [
                'ticket_id' => $ticket->id,
                'total_managers' => $managers->count(),
                'notified_count' => $notifiedCount,
                'skipped_count' => $skippedCount,
                'excluded_manager_id' => $excludedManagerId,
                'creation_context' => $creationContext,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            Log::error('🎫 ❌ MANAGER NOTIFICATION PROCESS FAILED', [
                'ticket_id' => $ticket->id,
                'creation_context' => $creationContext,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * FIXED: Manual assignment with proper notification
     */
    public function assignTechnician(Request $request, Ticket $ticket)
    {
        $this->authorize('assign tickets');

        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $previousTechnician = $ticket->assigned_to;
        $newTechnician = $request->assigned_to;

        Log::info('🎫 MANUAL ASSIGNMENT ATTEMPT', [
            'ticket_id' => $ticket->id,
            'previous_technician' => $previousTechnician,
            'new_technician' => $newTechnician,
            'assigned_by' => Auth::user()->name,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);

        $ticket->assigned_to = $newTechnician;
        $ticket->save();

        // Send assignment notification if technician changed
        if ($previousTechnician != $newTechnician) {
            $this->sendAssignmentNotification($ticket, 'manual_assignment');
            
            Log::info('🎫 ✅ MANUAL ASSIGNMENT COMPLETED', [
                'ticket_id' => $ticket->id,
                'previous_technician' => $previousTechnician,
                'new_technician' => $newTechnician,
                'assigned_by' => Auth::user()->name,
                'notification_sent' => true
            ]);
        } else {
            Log::info('🎫 MANUAL ASSIGNMENT - NO CHANGE', [
                'ticket_id' => $ticket->id,
                'technician' => $newTechnician,
                'assigned_by' => Auth::user()->name,
                'notification_sent' => false
            ]);
        }

        return redirect()->route('admin.tickets.unassigned')->with('success', 'Ticket assigned successfully!');
    }

    /**
     * Enhanced multi-layer security checks
     */
    private function performSecurityChecks(Request $request): array
    {
        // IP reputation check
        if ($this->isKnownBadIP($request->ip())) {
            $this->logSecurityEvent('BLOCKED_BAD_IP', $request);
            return ['passed' => false, 'message' => 'Request blocked for security reasons.'];
        }

        // Enhanced rate limiting
        if (!$this->checkAdvancedRateLimit($request)) {
            $this->logSecurityEvent('RATE_LIMIT_EXCEEDED', $request);
            return ['passed' => false, 'message' => 'Too many submissions. Please wait before submitting again.'];
        }

        // Advanced spam detection
        if ($this->isAdvancedSpamSubmission($request)) {
            $this->logSecurityEvent('SPAM_DETECTED', $request, [
                'spam_indicators' => $this->getSpamIndicators($request)
            ]);
            return ['passed' => false, 'message' => 'Submission blocked. Please contact us directly if this is legitimate.'];
        }

        // Behavioral analysis
        if ($this->detectSuspiciousBehavior($request)) {
            $this->logSecurityEvent('SUSPICIOUS_BEHAVIOR', $request);
            return ['passed' => false, 'message' => 'Request flagged for security review.'];
        }

        return ['passed' => true, 'message' => 'Security checks passed'];
    }

    /**
     * Enhanced Altcha verification with intelligent fallback
     */
    protected function verifyAltcha(Request $request): bool
    {
        $token = $request->input('altcha-token');
        
        if (!$token) {
            Log::warning('Altcha token missing', [
                'ip' => $request->ip(),
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'user' => 'Sire-bit'
            ]);
            return false;
        }

        // Check token format
        if (strlen($token) < 10) {
            Log::warning('Altcha token too short', [
                'token_length' => strlen($token),
                'ip' => $request->ip()
            ]);
            return false;
        }

        try {
            $response = Http::timeout(self::ALTCHA_TIMEOUT)
                ->withHeaders([
                    'User-Agent' => 'Laravel-FiscalSupport/1.0',
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ])
                ->post('https://altcha.org/api/verify', [
                    'token' => $token,
                    'ip' => $request->ip(),
                    'timestamp' => time(),
                ]);

            if ($response->successful()) {
                $result = $response->json();
                $isValid = isset($result['success']) && $result['success'] === true;
                
                Log::info('Altcha verification result', [
                    'success' => $isValid,
                    'response_data' => $result,
                    'ip' => $request->ip(),
                    'timestamp' => now()->format('Y-m-d H:i:s'),
                    'user' => 'Sire-bit'
                ]);
                
                return $isValid;
            } else {
                Log::error('Altcha API returned error', [
                    'status' => $response->status(),
                    'response_body' => $response->body(),
                    'ip' => $request->ip(),
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ]);
                
                return $this->intelligentFallback($request);
            }
        } catch (\Exception $e) {
            Log::error('Altcha verification exception', [
                'error' => $e->getMessage(),
                'token_length' => strlen($token),
                'ip' => $request->ip(),
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'user' => 'Sire-bit'
            ]);
            
            return $this->intelligentFallback($request);
        }
    }

    /**
     * Intelligent fallback verification system
     */
    protected function intelligentFallback(Request $request): bool
    {
        // Check if this is a genuine service outage vs individual failure
        $recentFailures = Cache::get('altcha_failures', 0);
        $serviceOutage = $recentFailures > 5; // If many failures, likely service issue
        
        if (!$serviceOutage) {
            // Individual failure - reject submission
            Log::warning('Individual Altcha failure - rejecting submission', [
                'ip' => $request->ip(),
                'recent_failures' => $recentFailures
            ]);
            return false;
        }

        Log::warning('Altcha service outage detected - using strict fallback', [
            'ip' => $request->ip(),
            'recent_failures' => $recentFailures,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);

        // Increment failure counter
        Cache::put('altcha_failures', $recentFailures + 1, now()->addMinutes(15));

        // Strict security checks during outage
        return $this->strictFallbackChecks($request);
    }

    /**
     * Strict security checks for fallback scenario
     */
    protected function strictFallbackChecks(Request $request): bool
    {
        $securityScore = 0;
        $checks = [];

        // Honeypot check (critical)
        if ($request->filled('website')) {
            $checks['honeypot'] = false;
            return false; // Immediate fail
        }
        $checks['honeypot'] = true;
        $securityScore += 2;

        // Timing check
        $formStartTime = $request->input('form_start_time');
        if ($formStartTime && (time() - $formStartTime) >= self::MIN_FORM_FILL_TIME) {
            $checks['timing'] = true;
            $securityScore += 2;
        } else {
            $checks['timing'] = false;
        }

        // Browser fingerprint check
        $userAgent = $request->userAgent();
        if ($this->isLegitimateUserAgent($userAgent)) {
            $checks['user_agent'] = true;
            $securityScore += 1;
        } else {
            $checks['user_agent'] = false;
        }

        // Required headers check
        if ($this->hasRequiredHeaders($request)) {
            $checks['headers'] = true;
            $securityScore += 1;
        } else {
            $checks['headers'] = false;
        }

        // Content quality check
        if ($this->hasQualityContent($request)) {
            $checks['content_quality'] = true;
            $securityScore += 1;
        } else {
            $checks['content_quality'] = false;
        }

        Log::info('Fallback security check results', [
            'ip' => $request->ip(),
            'security_score' => $securityScore,
            'checks' => $checks,
            'threshold' => 4,
            'passed' => $securityScore >= 4
        ]);

        // Require minimum score of 4/7 during fallback
        return $securityScore >= 4;
    }

    /**
     * Advanced rate limiting with multiple layers
     */
    private function checkAdvancedRateLimit(Request $request): bool
    {
        $ip = $request->ip();
        $email = $request->input('email', '');
        $userAgent = $request->userAgent();
        
        $limits = [
            "ticket_ip_{$ip}" => [
                'limit' => self::MAX_SUBMISSIONS_PER_HOUR_IP, 
                'window' => 3600,
                'description' => 'IP hourly limit'
            ],
            "ticket_email_{$email}" => [
                'limit' => self::MAX_SUBMISSIONS_PER_DAY_EMAIL, 
                'window' => 86400,
                'description' => 'Email daily limit'
            ],
            "ticket_global" => [
                'limit' => self::MAX_GLOBAL_SUBMISSIONS_PER_HOUR, 
                'window' => 3600,
                'description' => 'Global hourly limit'
            ],
            "ticket_ua_" . md5($userAgent) => [
                'limit' => 10, 
                'window' => 3600,
                'description' => 'User agent hourly limit'
            ],
        ];
        
        foreach ($limits as $key => $config) {
            $attempts = Cache::get($key, 0);
            if ($attempts >= $config['limit']) {
                Log::warning('Rate limit exceeded', [
                    'limit_type' => $config['description'],
                    'key' => $key,
                    'attempts' => $attempts,
                    'limit' => $config['limit'],
                    'ip' => $ip,
                    'email' => $email,
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ]);
                return false;
            }
        }
        
        return true;
    }

    /**
     * Advanced spam detection with ML-style scoring
     */
    private function isAdvancedSpamSubmission(Request $request): bool
    {
        $spamScore = 0;
        $indicators = $this->getSpamIndicators($request);
        
        // Calculate weighted spam score
        $weights = [
            'excessive_links' => 3,
            'suspicious_domains' => 4,
            'excessive_caps' => 2,
            'repeated_phrases' => 2,
            'suspicious_email_pattern' => 3,
            'gibberish_content' => 3,
            'disposable_email' => 4,
            'suspicious_timing' => 2,
            'duplicate_content' => 5,
            'suspicious_contact_details' => 2,
        ];
        
        foreach ($indicators as $indicator => $detected) {
            if ($detected && isset($weights[$indicator])) {
                $spamScore += $weights[$indicator];
            }
        }
        
        Log::info('Spam detection analysis', [
            'ip' => $request->ip(),
            'email' => $request->input('email'),
            'spam_score' => $spamScore,
            'threshold' => self::SPAM_SCORE_THRESHOLD,
            'indicators' => $indicators,
            'is_spam' => $spamScore >= self::SPAM_SCORE_THRESHOLD,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
        
        return $spamScore >= self::SPAM_SCORE_THRESHOLD;
    }

    /**
     * Get comprehensive spam indicators
     */
    private function getSpamIndicators(Request $request): array
    {
        $message = $request->input('message', '');
        $email = $request->input('email', '');
        $name = $request->input('name', '');
        $contactDetails = $request->input('contact_details', '');
        
        return [
            'excessive_links' => substr_count(strtolower($message), 'http') > 2,
            'suspicious_domains' => $this->containsSuspiciousDomains($message . ' ' . $contactDetails),
            'excessive_caps' => $this->hasExcessiveCaps($message . ' ' . $name),
            'repeated_phrases' => $this->hasRepeatedPhrases($message),
            'suspicious_email_pattern' => $this->hasSuspiciousEmailPattern($email),
            'gibberish_content' => $this->isGibberish($message),
            'disposable_email' => $this->isDisposableEmail($email),
            'suspicious_timing' => $this->hasSuspiciousTiming($request),
            'duplicate_content' => $this->isDuplicateContent($request),
            'suspicious_contact_details' => $this->hasSuspiciousContactDetails($contactDetails),
        ];
    }

    /**
     * Detect suspicious behavioral patterns
     */
    private function detectSuspiciousBehavior(Request $request): bool
    {
        $userAgent = $request->userAgent();
        $suspiciousPatterns = 0;
        
        // Check for bot-like user agents
        $botPatterns = ['curl', 'wget', 'bot', 'spider', 'crawler', 'python', 'java', 'perl', 'php', 'ruby', 'scrapy'];
        foreach ($botPatterns as $pattern) {
            if (stripos($userAgent, $pattern) !== false) {
                $suspiciousPatterns++;
                break;
            }
        }
        
        // Check for missing standard headers
        $requiredHeaders = ['accept', 'accept-language', 'accept-encoding'];
        $missingHeaders = 0;
        foreach ($requiredHeaders as $header) {
            if (!$request->header($header)) {
                $missingHeaders++;
            }
        }
        
        if ($missingHeaders > 1) {
            $suspiciousPatterns++;
        }
        
        // Check for suspicious referrer
        $referer = $request->header('referer');
        if ($referer && !str_contains($referer, request()->getHost())) {
            $suspiciousPatterns++;
        }
        
        // Check for rapid-fire requests from same IP
        $recentRequests = Cache::get("requests_" . $request->ip(), 0);
        if ($recentRequests > 10) { // More than 10 requests in last minute
            $suspiciousPatterns++;
        }
        
        Log::info('Behavioral analysis', [
            'ip' => $request->ip(),
            'user_agent' => $userAgent,
            'suspicious_patterns' => $suspiciousPatterns,
            'missing_headers' => $missingHeaders,
            'recent_requests' => $recentRequests,
            'is_suspicious' => $suspiciousPatterns >= 2
        ]);
        
        return $suspiciousPatterns >= 2;
    }

    /**
     * Check if IP is in known bad IP database
     */
    private function isKnownBadIP(string $ip): bool
    {
        // Check local blacklist
        $blacklistedIPs = Cache::remember('blacklisted_ips', 3600, function() {
            return collect([
                // Add your known bad IPs here
                // You could also query a database table
            ]);
        });
        
        if ($blacklistedIPs->contains($ip)) {
            return true;
        }
        
        // Check for repeated failures from this IP
        $recentFailures = Cache::get("security_failures_{$ip}", 0);
        if ($recentFailures > 5) {
            Log::warning('IP flagged for repeated security failures', [
                'ip' => $ip,
                'failures' => $recentFailures
            ]);
            return true;
        }
        
        return false;
    }

    /**
     * Enhanced validation with security-focused rules
     */
    private function validateTicketData(Request $request): ?array
    {
        try {
            return $request->validate([
                'name' => [
                    'required', 
                    'string', 
                    'min:2', 
                    'max:100',
                    'regex:/^[a-zA-Z0-9\s\-\.\,\&\']+$/', // Only allow safe characters
                ],
                'email' => [
                    'required', 
                    'email', 
                    'max:255',
                    'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', // Strict email format
                ],
                'service' => [
                    'required', 
                    'string', 
                    'max:255',
                    'in:Fiscal Device Setup,Technical Support,Billing Inquiry,Other', // Whitelist values
                ],
                'contact_details' => [
                    'required', 
                    'string', 
                    'max:255',
                    'regex:/^[a-zA-Z0-9\s\-\.\,\+\(\)]+$/', // Safe contact characters
                ],
                'message' => [
                    'required', 
                    'string', 
                    'min:10', 
                    'max:2000',
                    function ($attribute, $value, $fail) {
                        if ($this->containsDangerousContent($value)) {
                            $fail('Message contains prohibited content.');
                        }
                    },
                ],
                'altcha-token' => [
                    'required', 
                    'string', 
                    'min:10',
                    'max:1000', // Prevent excessively long tokens
                ],
                'attachment' => [
                    'nullable', 
                    'file', 
                    'mimes:pdf,jpg,jpeg,png', 
                    'max:5120',
                ],
                'website' => 'prohibited', // Honeypot field must be empty
            ], [
                'name.required' => 'Company name is required.',
                'name.regex' => 'Company name contains invalid characters.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please provide a valid email address.',
                'email.regex' => 'Email format is invalid.',
                'service.required' => 'Please select a service.',
                'service.in' => 'Please select a valid service option.',
                'contact_details.required' => 'Contact details are required.',
                'contact_details.regex' => 'Contact details contain invalid characters.',
                'message.required' => 'Message is required.',
                'message.min' => 'Message must be at least 10 characters.',
                'message.max' => 'Message cannot exceed 2000 characters.',
                'altcha-token.required' => 'Security verification is required.',
                'altcha-token.min' => 'Invalid security token.',
                'attachment.mimes' => 'Attachment must be a PDF, JPG, JPEG, or PNG file.',
                'attachment.max' => 'Attachment size cannot exceed 5MB.',
                'website.prohibited' => 'Invalid form submission.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Enhanced ticket validation failed', [
                'errors' => $e->errors(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'user' => 'Sire-bit'
            ]);
            return null;
        }
    }

    /**
     * Enhanced file handling with deep security checks
     */
    private function handleAttachment(Request $request): ?string
    {
        if (!$request->hasFile('attachment')) {
            return null;
        }

        $file = $request->file('attachment');
        
        // Comprehensive file security validation
        if (!$this->isSecureFile($file)) {
            throw new \Exception('File failed security validation');
        }

        // Generate cryptographically secure filename
        $extension = $file->getClientOriginalExtension();
        $filename = hash('sha256', Str::uuid() . time() . $request->ip()) . '.' . $extension;
        
        // Store in organized, secure directory structure
        $path = $file->storeAs(
            'tickets/attachments/' . date('Y/m/d'),
            $filename,
            'public'
        );

        // Log file upload for audit trail
        Log::info('File uploaded successfully', [
            'original_name' => $file->getClientOriginalName(),
            'stored_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'ip' => $request->ip(),
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);

        return $path;
    }

    /**
     * Comprehensive file security validation
     */
    private function isSecureFile($file): bool
    {
        // File size check
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            Log::warning('File too large', [
                'size' => $file->getSize(),
                'max_size' => self::MAX_FILE_SIZE
            ]);
            return false;
        }

        // MIME type validation
        if (!in_array($file->getMimeType(), self::ALLOWED_MIME_TYPES)) {
            Log::warning('Invalid MIME type', [
                'mime_type' => $file->getMimeType(),
                'allowed_types' => self::ALLOWED_MIME_TYPES
            ]);
            return false;
        }

        // File extension validation
        $extension = strtolower($file->getClientOriginalExtension());
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
        if (!in_array($extension, $allowedExtensions)) {
            Log::warning('Invalid file extension', [
                'extension' => $extension,
                'allowed_extensions' => $allowedExtensions
            ]);
            return false;
        }

        // Image-specific validation
        if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
            $imageInfo = getimagesize($file->getPathname());
            if (!$imageInfo) {
                Log::warning('Invalid image file', [
                    'filename' => $file->getClientOriginalName()
                ]);
                return false;
            }

            // Check image dimensions (prevent extremely large images)
            if ($imageInfo[0] > 5000 || $imageInfo[1] > 5000) {
                Log::warning('Image dimensions too large', [
                    'width' => $imageInfo[0],
                    'height' => $imageInfo[1]
                ]);
                return false;
            }
        }

        // PDF-specific validation
        if ($extension === 'pdf') {
            // Basic PDF header check
            $fileHandle = fopen($file->getPathname(), 'rb');
            $header = fread($fileHandle, 4);
            fclose($fileHandle);
            
            if ($header !== '%PDF') {
                Log::warning('Invalid PDF file header', [
                    'header' => bin2hex($header)
                ]);
                return false;
            }
        }

        return true;
    }

    // ===================== HELPER METHODS FOR SECURITY CHECKS =====================

    private function containsSuspiciousDomains(string $text): bool
    {
        $suspiciousDomains = [
            'bit.ly', 'tinyurl.com', 'goo.gl', 't.co', 'ow.ly',
            'viagra', 'casino', 'poker', 'loan', 'crypto'
        ];
        
        foreach ($suspiciousDomains as $domain) {
            if (stripos($text, $domain) !== false) {
                return true;
            }
        }
        
        return false;
    }

    private function hasExcessiveCaps(string $text): bool
    {
        $totalChars = strlen($text);
        if ($totalChars < 10) return false;
        
        $capsCount = strlen(preg_replace('/[^A-Z]/', '', $text));
        $capsRatio = $capsCount / $totalChars;
        
        return $capsRatio > 0.4; // More than 40% caps
    }

    private function hasRepeatedPhrases(string $text): bool
    {
        $words = str_word_count(strtolower($text), 1);
        if (count($words) < 5) return false;
        
        $phrases = [];
        for ($i = 0; $i < count($words) - 2; $i++) {
            $phrase = implode(' ', array_slice($words, $i, 3));
            $phrases[] = $phrase;
        }
        
        $uniquePhrases = array_unique($phrases);
        $repetitionRatio = (count($phrases) - count($uniquePhrases)) / count($phrases);
        
        return $repetitionRatio > 0.3; // More than 30% repeated phrases
    }

    private function hasSuspiciousEmailPattern(string $email): bool
    {
        $patterns = [
            '/[0-9]{5,}@/', // Many consecutive numbers
            '/@[0-9]+\./', // Numeric domain
            '/\+.+@/', // Plus addressing (sometimes used by spammers)
            '/\.{2,}/', // Multiple consecutive dots
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $email)) {
                return true;
            }
        }
        
        return false;
    }

    private function isGibberish(string $text): bool
    {
        // Remove spaces and convert to lowercase
        $cleanText = strtolower(preg_replace('/\s+/', '', $text));
        
        if (strlen($cleanText) < 10) return false;
        
        // Check for excessive consonant/vowel patterns
        $vowels = 'aeiou';
        $consonantStreak = 0;
        $vowelStreak = 0;
        $maxConsonantStreak = 0;
        $maxVowelStreak = 0;
        
        for ($i = 0; $i < strlen($cleanText); $i++) {
            $char = $cleanText[$i];
            if (ctype_alpha($char)) {
                if (strpos($vowels, $char) !== false) {
                    $vowelStreak++;
                    $consonantStreak = 0;
                    $maxVowelStreak = max($maxVowelStreak, $vowelStreak);
                } else {
                    $consonantStreak++;
                    $vowelStreak = 0;
                    $maxConsonantStreak = max($maxConsonantStreak, $consonantStreak);
                }
            }
        }
        
        // Gibberish likely if too many consecutive consonants or vowels
        return $maxConsonantStreak > 6 || $maxVowelStreak > 4;
    }

    private function isDisposableEmail(string $email): bool
    {
        $disposableDomains = [
            '10minutemail.com', 'tempmail.org', 'guerrillamail.com',
            'mailinator.com', 'yopmail.com', 'temp-mail.org',
            'throwaway.email', 'maildrop.cc', 'sharklasers.com',
            'grr.la', 'dispostable.com', 'emkei.gq'
        ];

        $domain = strtolower(substr(strrchr($email, "@"), 1));
        return in_array($domain, $disposableDomains);
    }

    private function hasSuspiciousTiming(Request $request): bool
    {
        $formStartTime = $request->input('form_start_time');
        if (!$formStartTime) return true; // Missing timing data is suspicious
        
        $fillTime = time() - $formStartTime;
        
        // Too fast (bot) or suspiciously slow
        return $fillTime < 3 || $fillTime > 3600; // Less than 3 seconds or more than 1 hour
    }

    private function isDuplicateContent(Request $request): bool
    {
        $contentHash = md5(
            $request->input('email', '') . 
            $request->input('message', '') . 
            $request->input('name', '')
        );
        
        $cacheKey = "content_hash_{$contentHash}";
        
        if (Cache::has($cacheKey)) {
            return true;
        }
        
        // Store content hash for 24 hours
        Cache::put($cacheKey, true, now()->addDay());
        
        return false;
    }

    private function hasSuspiciousContactDetails(string $contact): bool
    {
        // Check for obviously fake numbers
        $fakePatterns = [
            '/^1{5,}/', // All 1s
            '/^0{5,}/', // All 0s
            '/^123456/', // Sequential
            '/^999999/', // Repeated 9s
        ];
        
        foreach ($fakePatterns as $pattern) {
            if (preg_match($pattern, preg_replace('/\D/', '', $contact))) {
                return true;
            }
        }
        
        return false;
    }

    private function containsDangerousContent(string $content): bool
    {
        $dangerousPatterns = [
            '/<script/i',
            '/javascript:/i',
            '/vbscript:/i',
            '/data:text\/html/i',
            '/onclick/i',
            '/onerror/i',
            '/onload/i',
        ];
        
        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }
        
        return false;
    }

    private function isLegitimateUserAgent(string $userAgent): bool
    {
        if (empty($userAgent)) return false;
        
        // Check for legitimate browser patterns
        $legitimatePatterns = [
            '/Mozilla.*Chrome/i',
            '/Mozilla.*Firefox/i',
            '/Mozilla.*Safari/i',
            '/Mozilla.*Edge/i',
        ];
        
        foreach ($legitimatePatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return true;
            }
        }
        
        return false;
    }

    private function hasRequiredHeaders(Request $request): bool
    {
        $requiredHeaders = ['accept', 'accept-language', 'accept-encoding'];
        $presentHeaders = 0;
        
        foreach ($requiredHeaders as $header) {
            if ($request->header($header)) {
                $presentHeaders++;
            }
        }
        
        return $presentHeaders >= 2; // At least 2 of 3 required headers
    }

    private function hasQualityContent(Request $request): bool
    {
        $message = $request->input('message', '');
        $wordCount = str_word_count($message);
        
        // Quality indicators
        $indicators = [
            $wordCount >= 5, // At least 5 words
            strlen($message) >= 20, // At least 20 characters
            !$this->isGibberish($message), // Not gibberish
            preg_match('/[.!?]/', $message), // Contains punctuation
        ];
        
        return array_sum($indicators) >= 3; // At least 3 quality indicators
    }

    /**
     * Calculate overall security score for logging
     */
    private function calculateSecurityScore(Request $request): int
    {
        $score = 10; // Start with full score
        
        // Deduct points for security issues
        if ($this->isKnownBadIP($request->ip())) $score -= 5;
        if (!$this->hasRequiredHeaders($request)) $score -= 2;
        if (!$this->isLegitimateUserAgent($request->userAgent())) $score -= 2;
        if ($this->hasSuspiciousTiming($request)) $score -= 1;
        
        return max(0, $score);
    }

    /**
     * Update security metrics and rate limiting
     */
    private function updateSecurityMetrics(Request $request): void
    {
        $ip = $request->ip();
        $email = $request->input('email', '');
        $userAgent = $request->userAgent();
        
        // Update rate limiting counters
        $limits = [
            "ticket_ip_{$ip}" => 3600,
            "ticket_email_{$email}" => 86400,
            "ticket_global" => 3600,
            "ticket_ua_" . md5($userAgent) => 3600,
        ];
        
        foreach ($limits as $key => $ttl) {
            $current = Cache::get($key, 0);
            Cache::put($key, $current + 1, now()->addSeconds($ttl));
        }
        
        // Track successful submissions for analytics
        $successKey = "successful_submissions_" . date('Y-m-d');
        Cache::increment($successKey);
        Cache::put($successKey, Cache::get($successKey, 0), now()->addDays(7));
        
        // Update request tracking
        $requestKey = "requests_" . $ip;
        Cache::put($requestKey, Cache::get($requestKey, 0) + 1, now()->addMinute());
    }

    /**
     * Initialize security logging
     */
    private function initializeSecurityLogging(): void
    {
        // Ensure security log channel exists
        if (!config('logging.channels.security')) {
            config(['logging.channels.security' => [
                'driver' => 'daily',
                'path' => storage_path('logs/security.log'),
                'level' => 'info',
                'days' => 30,
            ]]);
        }
    }

    /**
     * Enhanced security event logging
     */
    private function logSecurityEvent(string $event, Request $request, array $extra = []): void
    {
        Log::channel('security')->warning("🔒 SECURITY EVENT: {$event}", [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'referer' => $request->header('referer'),
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'session_id' => $request->session()->getId(),
            'headers' => $request->headers->all(),
            'user' => 'Sire-bit',
            ...$extra
        ]);
    }

    /**
     * Create ticket with enhanced security context
     */
    private function createTicket(array $validatedData, ?string $attachmentPath, Request $request): Ticket
    {
        return Ticket::create([
            'company_name' => trim($validatedData['name']),
            'contact_details' => trim($validatedData['contact_details']),
            'email' => strtolower(trim($validatedData['email'])),
            'subject' => $validatedData['service'],
            'message' => trim($validatedData['message']),
            'service' => $validatedData['service'],
            'attachment' => $attachmentPath,
            'status' => 'pending',
            'priority' => 'low',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'submitted_at' => now(),
            'security_score' => $this->calculateSecurityScore($request),
            'verification_method' => 'altcha',
        ]);
    }

    /**
     * Success response
     */
    private function successResponse(Ticket $ticket)
    {
        return redirect()->back()->with('message', 
            "Your support ticket has been submitted successfully! " .
            "Ticket ID: #{$ticket->id}. " .
            "You will receive a confirmation email shortly."
        );
    }

    /**
     * Enhanced error response
     */
    private function errorResponse($message, $errors = null)
    {
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'errors' => $errors,
                'timestamp' => now()->toISOString(),
            ], 422);
        }

        return back()
               ->with('error', $message)
               ->with('error_details', $errors)
               ->withInput();
    }

    // ===================== ALL OTHER EXISTING METHODS REMAIN THE SAME =====================

    /**
     * Dashboard index with optimized queries
     */
    public function index(Request $request)
    {
        $period = $request->input('period', 'week');
        $now = Carbon::now();
        
        $startDate = match ($period) {
            'today' => $now->copy()->startOfDay(),
            'week'  => $now->copy()->startOfWeek(),
            'month' => $now->copy()->startOfMonth(),
            'quarter' => $now->copy()->firstOfQuarter(),
            default => $now->copy()->startOfWeek(),
        };

        $previousStartDate = $startDate->copy()->sub(1, str_replace('this_', '', $period));
        $previousEndDate = $startDate->copy()->subSecond();

        // Cache expensive queries
        $cacheKey = "dashboard_stats_{$period}_" . $startDate->format('Y-m-d');
        
        $data = Cache::remember($cacheKey, 300, function () use ($startDate, $previousStartDate, $previousEndDate) {
            return $this->getDashboardData($startDate, $previousStartDate, $previousEndDate);
        });

        return view('admin.index', array_merge($data, [
            'period' => $period,
            'startDate' => $startDate,
        ]));
    }

    /**
     * Get dashboard data with optimized queries
     */
    private function getDashboardData($startDate, $previousStartDate, $previousEndDate)
    {
        $statusCounts = (object) [
            'in_progress' => 0,
            'resolved' => 0,
            'pending' => 0,
            'unassigned' => 0,
        ];
        
        $percentageChanges = [
            'in_progress' => 0,
            'resolved' => 0,
            'pending' => 0,
            'unassigned' => 0,
        ];
        
        $tickets = collect();
        
        if (Schema::hasTable('tickets')) {
            // Current period counts
            $currentCounts = Ticket::select('status', DB::raw('count(*) as count'))
                ->where('created_at', '>=', $startDate)
                ->groupBy('status')
                ->pluck('count', 'status');

            $statusCounts->in_progress = $currentCounts->get('in_progress', 0);
            $statusCounts->resolved = $currentCounts->get('resolved', 0);
            $statusCounts->pending = $currentCounts->get('pending', 0);
            $statusCounts->unassigned = Ticket::whereNull('assigned_to')
                ->where('created_at', '>=', $startDate)
                ->count();

            // Previous period counts for percentage calculation
            $previousCounts = Ticket::select('status', DB::raw('count(*) as count'))
                ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
                ->groupBy('status')
                ->pluck('count', 'status');

            // Calculate percentage changes
            foreach (['in_progress', 'resolved', 'pending', 'unassigned'] as $status) {
                $current = $statusCounts->$status;
                $previous = $status === 'unassigned' 
                    ? Ticket::whereNull('assigned_to')
                        ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
                        ->count()
                    : $previousCounts->get($status, 0);
                
                $percentageChanges[$status] = $previous > 0 
                    ? round((($current - $previous) / $previous) * 100, 1)
                    : ($current > 0 ? 100 : 0);
            }

            // Recent tickets
            $tickets = Ticket::with(['assignedTo:id,name'])
                ->where('created_at', '>=', $startDate)
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get(['id', 'subject', 'company_name', 'status', 'priority', 'updated_at', 'assigned_to']);
        }

        // Job stats
        $jobStats = [
            'pending_jobs' => 0,
            'in_progress_jobs' => 0,
            'completed_jobs' => 0,
            'total_jobs' => 0,
        ];
        
        $callLogs = collect();

        if (Schema::hasTable('call_logs')) {
            $callLogs = CallLog::where('created_at', '>=', $startDate)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            $jobStats = [
                'pending_jobs' => CallLog::where('status', 'pending')
                    ->where('created_at', '>=', $startDate)->count(),
                'in_progress_jobs' => CallLog::where('status', 'in_progress')
                    ->where('created_at', '>=', $startDate)->count(),
                'completed_jobs' => CallLog::where('status', 'completed')
                    ->where('created_at', '>=', $startDate)->count(),
                'total_jobs' => CallLog::where('created_at', '>=', $startDate)->count(),
            ];
        }

        return [
            'statusCounts' => $statusCounts,
            'percentageChanges' => $percentageChanges,
            'tickets' => $tickets,
            'callLogs' => $callLogs,
            'stats' => array_merge(['total_jobs' => $jobStats['total_jobs'] ?? 0], $jobStats),
        ];
    }

    public function allTickets(Request $request)
    {
        $query = Ticket::with(['assignedTo:id,name']) // Changed from 'assignedTechnician'
            ->select(['id', 'subject', 'company_name', 'priority', 'status', 'updated_at', 'assigned_to']);

        $this->applyFilters($query, $request);

        $tickets = $query->latest()->paginate(15)->withQueryString();

        $technicians = User::whereIn('id', function($query) {
                $query->select('assigned_to')->from('tickets')->whereNotNull('assigned_to');
            })
            ->select(['id', 'name'])
            ->get();

        $statuses = ['in_progress', 'pending', 'resolved'];
        $priorities = ['low', 'medium', 'high'];

        return view('admin.all-tickets', compact('tickets', 'technicians', 'statuses', 'priorities'));
    }

    public function show(Ticket $ticket)
    {
        // Load the correct relationships that exist in your model
        $ticket->load([
            'assignedTo:id,name' // Use 'assignedTo' instead of 'assignedTechnician'
        ]);

        // If you have comments, uncomment this after adding the relationship
        // $ticket->load([
        //     'comments' => function($query) {
        //         $query->latest()->limit(10)->with(['user:id,name']);
        //     }
        // ]);

        $technicians = User::where('role', 'technician')
            ->select(['id', 'name'])
            ->get();

        return view('admin.ticketshow', compact('ticket', 'technicians'));
    }

    public function openTickets(Request $request)
    {
        $query = Ticket::where('status', 'in_progress')
            ->with(['assignedTo:id,name'])
            ->select(['id', 'subject', 'company_name', 'priority', 'status', 'updated_at', 'assigned_to']);

        $this->applyFilters($query, $request);

        $tickets = $query->latest()->paginate(15)->withQueryString();

        $technicians = User::whereIn('id', function($query) {
                $query->select('assigned_to')->from('tickets')->whereNotNull('assigned_to');
            })
            ->select(['id', 'name'])
            ->get();

        $priorities = ['low', 'medium', 'high'];

        return view('admin.open-tickets', compact('tickets', 'technicians', 'priorities'));
    }

    public function solvedTickets(Request $request)
    {
        $query = Ticket::where('status', 'resolved')
            ->with(['assignedTo:id,name'])
            ->select(['id', 'subject', 'company_name', 'priority', 'status', 'updated_at', 'assigned_to']);

        $this->applyFilters($query, $request);

        $tickets = $query->latest()->paginate(15)->withQueryString();

        $technicians = User::whereIn('id', function($query) {
                $query->select('assigned_to')->from('tickets')->whereNotNull('assigned_to');
            })
            ->select(['id', 'name'])
            ->get();

        $priorities = ['low', 'medium', 'high'];

        return view('admin.solved-tickets', compact('tickets', 'technicians', 'priorities'));
    }

    public function pendingTickets(Request $request)
    {
        $query = Ticket::where('status', 'pending')
            ->with(['assignedTo:id,name'])
            ->select(['id', 'subject', 'company_name', 'priority', 'status', 'updated_at', 'assigned_to']);

        $this->applyFilters($query, $request);

        $tickets = $query->latest()->paginate(15)->withQueryString();

        $technicians = User::whereIn('id', function($query) {
                $query->select('assigned_to')->from('tickets')->whereNotNull('assigned_to');
            })
            ->select(['id', 'name'])
            ->get();

        $priorities = ['low', 'medium', 'high'];

        return view('admin.pending-tickets', compact('tickets', 'technicians', 'priorities'));
    }

    public function unassignedTickets(Request $request)
    {
        $query = Ticket::whereNull('assigned_to')
            ->with(['assignedTo:id,name'])
            ->select(['id', 'subject', 'company_name', 'priority', 'status', 'updated_at', 'assigned_to']);

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%$search%")
                  ->orWhere('company_name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();

        $technicians = User::where('role', 'technician')
            ->select(['id', 'name'])
            ->get();

        $statuses = ['pending', 'in_progress', 'resolved', 'closed'];
        $priorities = ['low', 'medium', 'high'];

        return view('admin.unassigned-tickets', compact('tickets', 'technicians', 'statuses', 'priorities'));
    }

    public function create()
    {
        $technicians = User::where('role', 'technician')
            ->select(['id', 'name'])
            ->get();
        return view('admin.adminticket', compact('technicians'));
    }

    public function myTickets(Request $request)
    {
        $userId = Auth::id();

        if (!$userId) {
            return redirect()->route('login')->with('error', 'You must be logged in to view your tickets.');
        }

        $query = Ticket::where('assigned_to', $userId)
            ->select(['id', 'subject', 'priority', 'status', 'updated_at']);

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('search')) {
            $query->where('subject', 'like', "%{$request->search}%");
        }

        $tickets = $query->latest()->paginate(10)->withQueryString();

        $priorities = ['low', 'medium', 'high'];

        return view('admin.my-tickets', compact('tickets', 'priorities'));
    }

    public function updatePriority(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->priority = $request->input('priority');
        $ticket->save();
        
        Log::info('🎫 TICKET PRIORITY UPDATED', [
            'ticket_id' => $ticket->id,
            'new_priority' => $ticket->priority,
            'updated_by' => Auth::user()->name ?? 'System',
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
        
        return back()->with('message', 'Priority updated.');
    }

    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $oldStatus = $ticket->status;
        $ticket->status = $request->input('status');
        $ticket->save();
        
        Log::info('🎫 TICKET STATUS UPDATED', [
            'ticket_id' => $ticket->id,
            'old_status' => $oldStatus,
            'new_status' => $ticket->status,
            'updated_by' => Auth::user()->name ?? 'System',
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
        
        // Send notification if ticket is resolved
        if ($ticket->status === 'resolved') {
            try {
                Notification::route('mail', $ticket->email)
                    ->notify(new CustomerTicketResolvedNotification($ticket));
                
                Log::info('🎫 ✅ CUSTOMER RESOLVED NOTIFICATION SENT', [
                    'ticket_id' => $ticket->id,
                    'customer_email' => $ticket->email,
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ]);
            } catch (\Exception $e) {
                Log::error('🎫 ❌ CUSTOMER RESOLVED NOTIFICATION FAILED', [
                    'ticket_id' => $ticket->id,
                    'customer_email' => $ticket->email,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return back()->with('message', 'Status updated.');
    }

    public function addComment(Request $request, Ticket $ticket)
    {
        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $comment = $ticket->comments()->create([
            'user_id' => Auth::id(),
            'body' => $request->body,
        ]);

        Log::info('🎫 COMMENT ADDED TO TICKET', [
            'ticket_id' => $ticket->id,
            'comment_id' => $comment->id,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);

        return back()->with('message', 'Comment added!');
    }

    public function updateStatusPriority(Request $request, Ticket $ticket)
{
    // Improved authorization check with type casting and logging
    $currentUserId = Auth::id();
    $assignedToId = $ticket->assigned_to;
    
    // Log the comparison for debugging
    Log::info('🎫 AUTHORIZATION CHECK', [
        'current_user_id' => $currentUserId,
        'current_user_type' => gettype($currentUserId),
        'assigned_to' => $assignedToId,
        'assigned_to_type' => gettype($assignedToId),
        'strict_comparison' => $currentUserId === $assignedToId,
        'loose_comparison' => $currentUserId == $assignedToId,
        'casted_comparison' => (int)$currentUserId === (int)$assignedToId,
        'ticket_id' => $ticket->id,
        'environment' => app()->environment()
    ]);

    // Use multiple authorization checks with type casting
    $isAuthorized = (int)$currentUserId === (int)$assignedToId || 
                   $currentUserId == $assignedToId ||
                   auth::user()->hasRole('admin'); // Add admin override if you have roles

    if (!$isAuthorized) {
        Log::warning('🎫 ❌ AUTHORIZATION FAILED', [
            'user_id' => $currentUserId,
            'user_name' => Auth::user()->name,
            'ticket_id' => $ticket->id,
            'assigned_to' => $assignedToId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
        
        abort(403, 'Unauthorized action.');
    }

    $validated = $request->validate([
        'priority' => 'required|in:low,medium,high',
        'status' => 'required|in:pending,in_progress,resolved',
    ]);

    $currentStatus = $ticket->status;
    $newStatus = $validated['status'];

    $allowedTransitions = [
        'pending' => ['in_progress'],
        'in_progress' => ['resolved'],
        'resolved' => ['in_progress'],
    ];

    if (!in_array($newStatus, $allowedTransitions[$currentStatus] ?? [])) {
        return back()->with('error', 'Invalid status transition!');
    }

    $oldPriority = $ticket->priority;
    $ticket->update($validated);

    Log::info('🎫 TICKET STATUS & PRIORITY UPDATED BY TECHNICIAN', [
        'ticket_id' => $ticket->id,
        'old_status' => $currentStatus,
        'new_status' => $newStatus,
        'old_priority' => $oldPriority,
        'new_priority' => $validated['priority'],
        'updated_by' => Auth::user()->name,
        'timestamp' => now()->format('Y-m-d H:i:s')
    ]);

    // Send notification if ticket is resolved
    if ($newStatus === 'resolved') {
        try {
            Notification::route('mail', $ticket->email)
                ->notify(new CustomerTicketResolvedNotification($ticket));
            
            Log::info('🎫 ✅ CUSTOMER RESOLVED NOTIFICATION SENT', [
                'ticket_id' => $ticket->id,
                'customer_email' => $ticket->email,
                'resolved_by' => Auth::user()->name,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            Log::error('🎫 ❌ CUSTOMER RESOLVED NOTIFICATION FAILED', [
                'ticket_id' => $ticket->id,
                'customer_email' => $ticket->email,
                'error' => $e->getMessage()
            ]);
        }
    }

    return back()->with('success', 'Ticket updated successfully.');
}

    protected function getStatusCountsByDateRange($startDate, $endDate)
    {
        return Ticket::whereBetween('created_at', [$startDate, $endDate])
            ->select([
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending"),
                DB::raw("SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress"),
                DB::raw("SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved"),
                DB::raw("SUM(CASE WHEN assigned_to IS NULL THEN 1 ELSE 0 END) as unassigned")
            ])
            ->first();
    }

    public function reopen(Ticket $ticket)
    {
        if ($ticket->status !== 'resolved') {
            return back()->with('error', 'Only resolved tickets can be reopened.');
        }
        
        $ticket->status = 'in_progress';
        $ticket->save();
        
        Log::info('🎫 TICKET REOPENED', [
            'ticket_id' => $ticket->id,
            'reopened_by' => Auth::user()->name ?? 'System',
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
        
        return back()->with('message', 'Ticket reopened successfully.');
    }

    protected function applyFilters($query, $request)
    {
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('subject', 'like', "%{$request->search}%")
                  ->orWhere('company_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Ticket;
use App\Models\CallLog;
use App\Models\CustomerContact;
use Illuminate\Support\Facades\Schema;

class AdminGlobalSearchController extends Controller
{
    /**
     * Perform global search across contacts, tickets, and jobs
     */
    public function globalSearch(Request $request)
    {
        // Validate input
        $request->validate([
            'q' => 'required|string|min:2|max:255'
        ]);

        $query = trim($request->get('q', ''));
        
        // Initialize results arrays
        $results = [
            'contacts' => [],
            'tickets' => [],
            'jobs' => [],
        ];
        
        try {
            // Search each category with error handling
            $results['contacts'] = $this->searchContacts($query);
            $results['tickets'] = $this->searchTickets($query);
            $results['jobs'] = $this->searchJobs($query);
            
            // Log successful search
            Log::info('Global search performed', [
                'user_id' => Auth::id(),
                'query' => $query,
                'results_count' => [
                    'contacts' => count($results['contacts']),
                    'tickets' => count($results['tickets']),
                    'jobs' => count($results['jobs'])
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Global search error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'query' => $query,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Search temporarily unavailable. Please try again.',
                'contacts' => [],
                'tickets' => [],
                'jobs' => []
            ], 500);
        }
        
        return response()->json($results);
    }
    
    /**
     * Search contacts in customer_contacts table
     */
    private function searchContacts($query)
    {
        try {
            if (!Schema::hasTable('customer_contacts')) {
                Log::info('customer_contacts table does not exist');
                return [];
            }
            
            $contacts = DB::table('customer_contacts')
                         ->select(['id', 'name', 'email', 'company', 'phone', 'position'])
                         ->where(function($q) use ($query) {
                             $q->where('name', 'LIKE', "%{$query}%")
                               ->orWhere('email', 'LIKE', "%{$query}%")
                               ->orWhere('company', 'LIKE', "%{$query}%")
                               ->orWhere('phone', 'LIKE', "%{$query}%")
                               ->orWhere('position', 'LIKE', "%{$query}%");
                         })
                         ->where('is_active', true) // Only active contacts
                         ->orderBy('name', 'asc')
                         ->limit(5)
                         ->get();
            
            Log::info('Contacts search result', [
                'query' => $query,
                'found' => $contacts->count()
            ]);
            
            return $contacts->map(function ($contact) {
                return [
                    'id' => $contact->id,
                    'name' => $contact->name ?? 'N/A',
                    'email' => $contact->email ?? 'N/A',
                    'company' => $contact->company ?? 'N/A',
                    'phone' => $contact->phone ?? 'N/A',
                    'type' => 'contact'
                ];
            })->toArray();
            
        } catch (\Exception $e) {
            Log::warning('Contact search failed: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Search tickets - using correct columns from your model
     */
    private function searchTickets($query)
    {
        try {
            if (!Schema::hasTable('tickets')) {
                Log::warning('Tickets table does not exist');
                return [];
            }
            
            $tickets = DB::table('tickets')
                        ->select(['id', 'subject', 'status', 'company_name', 'priority', 'created_at', 'email', 'message'])
                        ->where(function($q) use ($query) {
                            $q->where('subject', 'LIKE', "%{$query}%")
                              ->orWhere('company_name', 'LIKE', "%{$query}%")
                              ->orWhere('message', 'LIKE', "%{$query}%") // Use message instead of description
                              ->orWhere('email', 'LIKE', "%{$query}%")
                              ->orWhere('id', 'LIKE', "%{$query}%");
                        })
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
            
            Log::info('Tickets search result', [
                'query' => $query,
                'found' => $tickets->count()
            ]);
            
            return $tickets->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'subject' => $ticket->subject ?? 'No Subject',
                    'status' => ucfirst($ticket->status ?? 'unknown'),
                    'company_name' => $ticket->company_name ?? 'N/A',
                    'priority' => $ticket->priority ?? 'normal',
                    'created_at' => $ticket->created_at,
                    'type' => 'ticket'
                ];
            })->toArray();
            
        } catch (\Exception $e) {
            Log::warning('Ticket search failed: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Search jobs/call logs - using correct columns from your model
     */
    private function searchJobs($query)
    {
        try {
            if (!Schema::hasTable('call_logs')) {
                Log::warning('Call logs table does not exist');
                return [];
            }
            
            $jobs = DB::table('call_logs')
                     ->select(['id', 'customer_name', 'job_card', 'type', 'status', 'created_at', 'fault_description', 'zimra_ref'])
                     ->where(function($q) use ($query) {
                         $q->where('customer_name', 'LIKE', "%{$query}%")
                           ->orWhere('job_card', 'LIKE', "%{$query}%")
                           ->orWhere('type', 'LIKE', "%{$query}%")
                           ->orWhere('fault_description', 'LIKE', "%{$query}%") // Use fault_description instead of description
                           ->orWhere('zimra_ref', 'LIKE', "%{$query}%")
                           ->orWhere('id', 'LIKE', "%{$query}%");
                     })
                     ->orderBy('created_at', 'desc')
                     ->limit(5)
                     ->get();
            
            Log::info('Jobs search result', [
                'query' => $query,
                'found' => $jobs->count()
            ]);
            
            return $jobs->map(function ($job) {
                return [
                    'id' => $job->id,
                    'customer_name' => $job->customer_name ?? 'N/A',
                    'job_card' => $job->job_card ?? "Job #{$job->id}",
                    'job_type' => $job->type ?? 'N/A',
                    'status' => ucfirst($job->status ?? 'pending'),
                    'priority' => 'normal', // Default since column doesn't exist
                    'created_at' => $job->created_at,
                    'description' => substr($job->fault_description ?? '', 0, 100), // Truncate for display
                    'type' => 'job'
                ];
            })->toArray();
            
        } catch (\Exception $e) {
            Log::warning('Job search failed: ' . $e->getMessage());
            return [];
        }
    }
}
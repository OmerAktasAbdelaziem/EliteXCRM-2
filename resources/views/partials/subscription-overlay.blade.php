<!-- Subscription Inactive Overlay -->
@if(isset($subscription_inactive) && $subscription_inactive)
<div id="subscription-overlay" class="subscription-overlay">
    <div class="subscription-blur-background"></div>
    <div class="subscription-popup-card">
        <div class="card border-0 shadow-lg">
            <div class="card-body text-center p-5">
                <div class="mb-4">
                    <i class="bx bx-error-circle text-danger" style="font-size: 4rem;"></i>
                </div>
                <h3 class="text-danger mb-3">Your subscription is not active</h3>
                <p class="text-muted mb-4">Please contact your administrator to renew your subscription to continue using the system.</p>
                <div class="d-flex justify-content-center gap-3">
                    @if(Auth::user() && Auth::user()->role && Auth::user()->role->name === 'Admin')
                        <a href="{{ route('subscription.index') }}" class="btn btn-primary">
                            <i class="bx bx-cog me-2"></i>Manage Subscription
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .subscription-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: all;
    }
    
    .subscription-blur-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        pointer-events: all;
    }
    
    .subscription-popup-card {
        position: relative;
        z-index: 100000;
        max-width: 500px;
        width: 90%;
        animation: subscriptionPulse 2s ease-in-out infinite;
        pointer-events: all;
    }
    
    @keyframes subscriptionPulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.02);
        }
    }
    
    .subscription-popup-card .card {
        border-radius: 15px;
        border: 3px solid #dc3545;
        box-shadow: 0 20px 60px rgba(220, 53, 69, 0.3);
    }
    
    .subscription-popup-card .card-body {
        background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
        border-radius: 12px;
    }
    
    /* Disable all interactions with page content behind overlay */
    body.subscription-locked {
        overflow: hidden;
        pointer-events: none;
    }
    
    body.subscription-locked .subscription-overlay {
        pointer-events: all;
    }
    
    /* Prevent any buttons, links, or form elements from working */
    body.subscription-locked a:not(.subscription-overlay a),
    body.subscription-locked button:not(.subscription-overlay button),
    body.subscription-locked input:not(.subscription-overlay input),
    body.subscription-locked textarea:not(.subscription-overlay textarea),
    body.subscription-locked select:not(.subscription-overlay select) {
        pointer-events: none !important;
        cursor: not-allowed !important;
    }
</style>

<script>
    @if(isset($subscription_inactive) && $subscription_inactive)
    document.addEventListener('DOMContentLoaded', function() {
        // Lock the body to prevent any interaction
        document.body.classList.add('subscription-locked');
        
        // Prevent all keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Allow only basic navigation keys for admins in subscription management
            const allowedKeys = ['F5', 'F12']; // Allow refresh and dev tools
            if (!allowedKeys.includes(e.key) && !e.target.closest('.subscription-overlay')) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        }, true);
        
        // Prevent right-click context menu
        document.addEventListener('contextmenu', function(e) {
            if (!e.target.closest('.subscription-overlay')) {
                e.preventDefault();
                return false;
            }
        });
        
        // Prevent text selection
        document.addEventListener('selectstart', function(e) {
            if (!e.target.closest('.subscription-overlay')) {
                e.preventDefault();
                return false;
            }
        });
        
        // Prevent any clicks outside the overlay
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.subscription-overlay')) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        }, true);
        
        // Block all form submissions except in overlay
        document.addEventListener('submit', function(e) {
            if (!e.target.closest('.subscription-overlay')) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        }, true);
        
        // Prevent any navigation attempts
        window.addEventListener('beforeunload', function(e) {
            // Only allow navigation to subscription management for admins
            if (!window.location.href.includes('/subscription')) {
                @if(Auth::user() && Auth::user()->role && Auth::user()->role->name !== 'Admin')
                e.preventDefault();
                e.returnValue = '';
                return '';
                @endif
            }
        });
    });
    @endif
</script>
@endif

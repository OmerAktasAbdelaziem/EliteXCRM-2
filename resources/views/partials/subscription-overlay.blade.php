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
                <p class="text-muted mb-4">Please contact your administrator to renew your subscription.</p>
                <div class="d-flex justify-content-center gap-3">
                    @if(Auth::user() && Auth::user()->role && Auth::user()->role->name === 'Admin')
                        <a href="{{ route('subscription.index') }}" class="btn btn-primary">
                            <i class="bx bx-cog me-2"></i>Manage Subscription
                        </a>
                    @endif
                    <button type="button" class="btn btn-outline-secondary" onclick="closeSubscriptionOverlay()">
                        <i class="bx bx-x me-2"></i>Close
                    </button>
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
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .subscription-blur-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    
    .subscription-popup-card {
        position: relative;
        z-index: 10000;
        max-width: 500px;
        width: 90%;
        animation: subscriptionFadeIn 0.3s ease-out;
    }
    
    @keyframes subscriptionFadeIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    .subscription-popup-card .card {
        border-radius: 15px;
        border: 2px solid #dc3545;
    }
    
    .subscription-popup-card .card-body {
        background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
        border-radius: 13px;
    }
    
    @keyframes subscriptionFadeOut {
        from {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
        to {
            opacity: 0;
            transform: scale(0.9) translateY(-20px);
        }
    }
</style>

<script>
    function closeSubscriptionOverlay() {
        const overlay = document.getElementById('subscription-overlay');
        if (overlay) {
            overlay.style.animation = 'subscriptionFadeOut 0.3s ease-in forwards';
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 300);
        }
    }

    // Prevent interaction with background content when overlay is active
    @if(isset($subscription_inactive) && $subscription_inactive)
    document.addEventListener('DOMContentLoaded', function() {
        // Disable scrolling
        document.body.style.overflow = 'hidden';
        
        // Prevent clicks on background elements
        const overlay = document.getElementById('subscription-overlay');
        if (overlay) {
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay || e.target.classList.contains('subscription-blur-background')) {
                    // Optional: Show shake animation to indicate the overlay can't be dismissed by clicking background
                    const card = overlay.querySelector('.subscription-popup-card');
                    card.style.animation = 'subscriptionShake 0.5s ease-in-out';
                    setTimeout(() => {
                        card.style.animation = 'subscriptionFadeIn 0.3s ease-out';
                    }, 500);
                }
            });
        }
    });
    
    // Add shake animation for when user tries to click background
    const shakeStyle = document.createElement('style');
    shakeStyle.textContent = `
        @keyframes subscriptionShake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
    `;
    document.head.appendChild(shakeStyle);
    @endif
</script>
@endif

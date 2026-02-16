// ============================================
// GOOGLE ADS CONVERSION TRACKING HELPER
// ============================================

/**
 * Tracks a Google Ads conversion event
 * This function reads the conversion configuration and sends the event to Google Ads
 */
function trackGoogleAdsConversion() {
    // Check if gtag is available
    if (typeof gtag === 'undefined') {
        console.log('Google Ads tracking not available');
        return;
    }

    // Fetch tracking configuration
    fetch('data/tracking.json')
        .then(response => response.json())
        .then(config => {
            if (config.google_ads && config.google_ads.enabled) {
                const conversionId = config.google_ads.conversion_id;
                const conversionLabel = config.google_ads.conversion_label;

                // Send conversion event
                gtag('event', 'conversion', {
                    'send_to': `${conversionId}/${conversionLabel}`,
                    'value': 1.0,
                    'currency': 'COP'
                });

                console.log('Google Ads conversion tracked');
            }
        })
        .catch(error => {
            console.error('Error loading tracking config:', error);
        });
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { trackGoogleAdsConversion };
}

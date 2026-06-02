<?php

namespace Tests\Feature\Reservel;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Language Switching Tests — SE-04, SE-05
 *
 * Covers switching the app locale to Arabic (RTL)
 * and switching back to French (LTR).
 */
class LanguageSwitchingTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // SE-04: Switching to Arabic sets locale to 'ar' in session
    // -------------------------------------------------------------------------

    /**
     * SE-04 (Positive)
     * User visits the Arabic locale switch route.
     * Expects session locale is set to 'ar'.
     */
    public function test_switching_to_arabic_sets_locale_in_session(): void
    {
        $response = $this->get('/lang/ar');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'ar');
    }

    // -------------------------------------------------------------------------
    // SE-05: Switching back to French sets locale to 'fr' in session
    // -------------------------------------------------------------------------

    /**
     * SE-05 (Positive)
     * User visits the French locale switch route after being in Arabic.
     * Expects session locale is set to 'fr'.
     */
    public function test_switching_to_french_sets_locale_in_session(): void
    {
        // Start in Arabic
        $this->get('/lang/ar');

        // Switch back to French
        $response = $this->get('/lang/fr');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'fr');
    }

    // -------------------------------------------------------------------------
    // Extra: Invalid locale is ignored
    // -------------------------------------------------------------------------

    /**
     * (Negative)
     * User visits an unsupported locale.
     * Expects session locale is NOT updated.
     */
    public function test_invalid_locale_is_ignored(): void
    {
        $this->get('/lang/fr'); // set a known locale first

        $this->get('/lang/zh'); // unsupported

        $this->assertNotEquals('zh', session('locale'));
    }
}

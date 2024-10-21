<?php

namespace Modules\Certificate\Tests\Feature\CertificateController;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Certificate\Models\Certificate;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_account_certificate()
    {
        $certificate = Certificate::factory()->create();

        $this->getJson(
            route('api.account.certificates.show', [
                'account' => $certificate->account,
                'certificate' => $certificate,
            ])
        )
            ->assertSuccessful();
    }
}

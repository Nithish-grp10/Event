<?php

namespace Tests\Feature;

use App\Modules\Events\Models\Event;
use App\Modules\LegacyForms\Models\Form;
use App\Modules\LegacyForms\Models\FormField;
use App\Modules\Users\Models\User;
use App\Modules\Applications\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class Phase3VerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        \Spatie\Permission\Models\Role::create(['name' => 'staff']);
        \Spatie\Permission\Models\Role::create(['name' => 'super-admin']);
    }

    private function setupEventWithForm($status = 'published')
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $event = Event::create([
            'title' => 'Test Event',
            'slug' => 'test-event-' . Str::random(6),
            'start_date' => now()->addDays(1),
            'status' => $status,
            'created_by' => $admin->id,
        ]);

        $form = Form::create([
            'title' => 'Test Form',
            'created_by' => $admin->id,
        ]);

        $field1 = FormField::create([
            'form_id' => $form->id,
            'label' => 'Why do you want to attend?',
            'field_type' => 'text',
            'is_required' => true,
            'sort_order' => 1,
        ]);

        $event->forms()->attach($form->id);

        return [$admin, $event, $form, $field1];
    }

    public function test_public_apply_page_renders_for_published_event()
    {
        [$admin, $event, $form, $field1] = $this->setupEventWithForm('published');

        $response = $this->get('/events/' . $event->slug . '/apply');

        $response->assertOk();
        $response->assertSee($field1->label);
    }

    public function test_draft_event_returns_real_404()
    {
        [$admin, $event, $form, $field1] = $this->setupEventWithForm('draft');

        $response = $this->get('/events/' . $event->slug . '/apply');

        $response->assertNotFound();
    }

    public function test_submission_creates_an_application()
    {
        [$admin, $event, $form, $field1] = $this->setupEventWithForm('published');

        $response = $this->post('/events/' . $event->slug . '/apply', [
            'applicant_name' => 'Alice Test',
            'applicant_email' => 'alice@example.com',
            'field_' . $field1->id => 'I love coding',
        ]);

        $application = Application::where('applicant_email', 'alice@example.com')->first();
        $this->assertNotNull($application);
        $this->assertEquals(1, Application::count());

        $response->assertRedirect(route('public.apply.confirmation', $application));
    }

    public function test_duplicate_email_overwrites_not_duplicates()
    {
        [$admin, $event, $form, $field1] = $this->setupEventWithForm('published');

        // First post
        $this->post('/events/' . $event->slug . '/apply', [
            'applicant_name' => 'Bob Test',
            'applicant_email' => 'bob@example.com',
            'field_' . $field1->id => 'First reason',
        ]);

        // Second post with confirm_overwrite=1
        $this->post('/events/' . $event->slug . '/apply', [
            'confirm_overwrite' => 1,
            'applicant_name' => 'Bob Updated',
            'applicant_email' => 'bob@example.com',
            'field_' . $field1->id => 'Second reason',
        ]);

        $this->assertEquals(1, Application::where('applicant_email', 'bob@example.com')->count());
        $app = Application::where('applicant_email', 'bob@example.com')->first();
        $this->assertEquals('Bob Updated', $app->applicant_name);
        $this->assertEquals('Second reason', $app->data[$field1->id]);
    }

    public function test_duplicate_without_confirmation_is_blocked()
    {
        [$admin, $event, $form, $field1] = $this->setupEventWithForm('published');

        // First post
        $this->post('/events/' . $event->slug . '/apply', [
            'applicant_name' => 'Charlie Test',
            'applicant_email' => 'charlie@example.com',
            'field_' . $field1->id => 'First reason',
        ]);

        // Second post without confirm_overwrite
        $response = $this->post('/events/' . $event->slug . '/apply', [
            'applicant_name' => 'Charlie Hacker',
            'applicant_email' => 'charlie@example.com',
            'field_' . $field1->id => 'Hacked reason',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('duplicate_warning');

        $this->assertEquals(1, Application::where('applicant_email', 'charlie@example.com')->count());
        $app = Application::where('applicant_email', 'charlie@example.com')->first();
        $this->assertEquals('Charlie Test', $app->applicant_name); // unchanged
    }

    public function test_staff_cannot_view_unassigned_application_via_real_request()
    {
        [$admin, $event, $form, $field1] = $this->setupEventWithForm('published');

        // Create application
        $app = Application::create([
            'event_id' => $event->id,
            'form_id' => $form->id,
            'applicant_name' => 'Dave Test',
            'applicant_email' => 'dave@example.com',
            'data' => [$field1->id => 'Reason'],
        ]);

        $staffUser = User::factory()->create();
        $staffUser->assignRole('staff');
        // NOT attached to event

        $response = $this->actingAs($staffUser)->get('/applications/' . $app->id);

        $response->assertForbidden();
    }

    public function test_admin_can_view_their_own_events_applications()
    {
        [$admin, $event, $form, $field1] = $this->setupEventWithForm('published');

        // Create application
        $app = Application::create([
            'event_id' => $event->id,
            'form_id' => $form->id,
            'applicant_name' => 'Eve Test',
            'applicant_email' => 'eve@example.com',
            'data' => [$field1->id => 'Reason'],
        ]);

        $response = $this->actingAs($admin)->get('/applications');

        $response->assertOk();
        $response->assertSee('Eve Test');
        $response->assertSee('eve@example.com');
    }
}

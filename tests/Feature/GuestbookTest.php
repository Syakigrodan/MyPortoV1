<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GuestbookTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_contact_section_renders_comments(): void
    {
        $this->seed();

        Comment::create([
            'name' => 'Syakirana Aurieli Pasa',
            'message' => 'Selamat datang di guestbook.',
            'is_admin' => true,
            'is_pinned' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Hubungi Saya')
            ->assertSee('Connect With Me')
            ->assertSee('Comments')
            ->assertSee('Pinned Comment')
            ->assertSee('Admin')
            ->assertSee('Selamat datang di guestbook.');
    }

    public function test_pinned_comment_is_listed_first(): void
    {
        $newest = Comment::create(['name' => 'Rani', 'message' => 'Latest message']);
        $pinned = Comment::create(['name' => 'Admin', 'message' => 'Pinned message', 'is_pinned' => true]);

        $this->assertTrue($pinned->is_pinned);

        $ordered = Comment::orderByDesc('is_pinned')->orderByDesc('created_at')->get();

        $this->assertSame($pinned->id, $ordered->first()->id);
        $this->assertSame($newest->id, $ordered->last()->id);
    }

    public function test_a_guest_can_post_a_comment(): void
    {
        $response = $this->post('/comments', [
            'name' => 'Bagas',
            'message' => 'Keren banget portofolionya!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('comments', [
            'name' => 'Bagas',
            'message' => 'Keren banget portofolionya!',
            'is_admin' => false,
            'is_pinned' => false,
        ]);
    }

    public function test_a_guest_can_post_a_comment_with_a_profile_photo(): void
    {
        Storage::fake('public');

        $this->post('/comments', [
            'name' => 'Nadia',
            'message' => 'Hello from the guestbook.',
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ])->assertRedirect();

        $comment = Comment::firstWhere('name', 'Nadia');

        $this->assertNotNull($comment->avatar_path);
        Storage::disk('public')->assertExists($comment->avatar_path);
    }

    public function test_comment_validation_requires_name_and_message(): void
    {
        $this->post('/comments', [])->assertSessionHasErrors(['name', 'message']);

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_a_guest_cannot_flag_themselves_as_admin(): void
    {
        $this->post('/comments', [
            'name' => 'Sneaky',
            'message' => 'Let me pin myself.',
            'is_admin' => '1',
            'is_pinned' => '1',
        ])->assertRedirect();

        $comment = Comment::firstWhere('name', 'Sneaky');

        $this->assertFalse($comment->is_admin);
        $this->assertFalse($comment->is_pinned);
    }

    public function test_contact_form_stores_a_message(): void
    {
        $this->post('/contact', [
            'name' => 'Rani',
            'email' => 'rani@example.com',
            'message' => 'Punya tugas besar, butuh bantuan.',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('contact_submissions', ['email' => 'rani@example.com']);
    }
}

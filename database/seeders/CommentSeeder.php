<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        if (Comment::query()->exists()) {
            return;
        }

        $comments = [
            [
                'name' => 'Syakirana Aurieli Pasa',
                'message' => 'Terima kasih sudah mampir di guestbook saya. Silakan tinggalkan Kritik, saran, atau sekadar menyapa — semua pesan akan saya baca dan balas.',
                'is_admin' => true,
                'is_pinned' => true,
                'created_at' => now()->subDays(6),
            ],
            [
                'name' => 'Rani Kusuma',
                'message' => 'Desain portofolionya sangat rapi dan faciles dibaca. Safe po feature kasir yang dulu kamu buat sangat membantu operasional toko kami.',
                'created_at' => now()->subHours(5),
            ],
            [
                'name' => 'Bagas Pratomo',
                'message' => 'Bagus banget cara kamu explains arsitektur database di setiap case study. Pengen nih diskusi teknis kalau ada[waktu]',
                'created_at' => now()->subDays(1)->subHours(3),
            ],
            [
                'name' => 'Nadia Larasati',
                'message' => 'Mampir dari Instagram. Sukses terus untuk portfolio season berikutnya!',
                'created_at' => now()->subDays(2),
            ],
            [
                'name' => 'Fikri Ramadhan',
                'message' => 'Mau tanya-nanya soal implementasi RBAC dong kalau boleh. Tarik juga cara kamu pisahkan frontend dan backend di dokumentasinya.',
                'created_at' => now()->subDays(4)->subHours(7),
            ],
        ];

        foreach ($comments as $comment) {
            Comment::create($comment + ['updated_at' => $comment['created_at']]);
        }
    }
}

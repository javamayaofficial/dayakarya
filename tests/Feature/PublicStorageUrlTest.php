<?php

namespace Tests\Feature;

use App\Models\Work;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicStorageUrlTest extends TestCase
{
    public function test_public_disk_uses_root_storage_url_for_fastpanel_proxy(): void
    {
        config([
            'app.url' => 'https://dayakarya.test',
            'filesystems.disks.public.url' => 'https://dayakarya.test/storage',
        ]);

        $this->assertSame(
            'https://dayakarya.test/storage/work-covers/demo.png',
            Storage::disk('public')->url('work-covers/demo.png')
        );
    }

    public function test_work_cover_accessor_keeps_existing_storage_paths_stable(): void
    {
        config([
            'app.url' => 'https://dayakarya.test',
            'filesystems.disks.public.url' => 'https://dayakarya.test/storage',
        ]);

        $rootStorageWork = Work::make([
            'cover' => '/storage/work-covers/cover.png',
        ]);
        $legacyStorageWork = Work::make([
            'cover' => '/public/storage/work-covers/cover.png',
        ]);
        $relativePathWork = Work::make([
            'cover' => 'work-covers/cover.png',
        ]);

        $this->assertSame('/storage/work-covers/cover.png', $rootStorageWork->cover);
        $this->assertSame('/public/storage/work-covers/cover.png', $legacyStorageWork->cover);
        $this->assertSame('https://dayakarya.test/storage/work-covers/cover.png', $relativePathWork->cover);
    }
}

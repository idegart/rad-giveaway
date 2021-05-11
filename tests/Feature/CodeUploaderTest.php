<?php

namespace Tests\Feature;

use App\Models\Code;
use File;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;

class CodeUploaderTest extends TestCase
{
    use DatabaseMigrations;

    /** @test **/
    public function can_upload_codes(): void
    {
        $path = Storage::path('tests/codes.txt');

        self::assertFileExists($path);

        $file = new UploadedFile($path, 'codes.txt', filesize($path), null, true);

        $this->postJson(route('storeCodes'), [
            'file' => $file,
        ])
            ->assertExactJson([
                'success' => true,
            ])
            ->assertSuccessful();

        $content = File::get($path);

        foreach (explode(PHP_EOL, $content) as $code) {
            $this->assertDatabaseHas((new Code)->getTable(), [
                'code' => $code,
                'valid_date' => now()->toDateString(),
            ]);
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Image;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateToLinodeS3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'photos:migrate-to-ls3';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all existing photos from local storage to Linode S3';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting the migration of photos to Linode...');

        // Fetch all images from the database
        // $images = Image::all();
        // $images = Image::take(3)->get();
        $images = Image::whereIn('id', [4, 31, 32])->get();

        $bar = $this->output->createProgressBar($images->count());
        $bar->start();

        foreach ($images as $image) {
            try {
                $localPath = $image->folder . '/' . $image->filename;

                // Check if the file exists in the local storage
                if (Storage::disk('public')->exists($localPath)) {
                    // Read the file from local storage
                    $fileContent = Storage::disk('public')->get($localPath);

                    // Save the file to linode-s3
                    Storage::disk('linode-s3')->put($localPath, $fileContent, 'public');

                    // Optionally delete the file from local storage after uploading
                    Storage::disk('public')->delete($localPath);

                    $this->info("Migrated: {$localPath}");
                } else {
                    $this->warn("File not found: {$localPath}");
                }
            } catch (\Exception $e) {
                $this->error("Error migrating {$localPath}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->info("\nMigration completed successfully!");

        return 0;
    }
}

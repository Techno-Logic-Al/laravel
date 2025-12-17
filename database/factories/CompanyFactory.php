<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Company>
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        static $iconFiles;
        static $index = 0;

        // Load the list of PNG icon files once per process.
        if ($iconFiles === null) {
            $files = Storage::disk('public')->files('seed-icons/PNG');

            $iconFiles = collect($files)
                ->filter(function (string $path): bool {
                    return str_ends_with(strtolower($path), '.png');
                })
                ->values()
                ->all();
        }

        // Fallback to simple fake data if no icon files are found.
        if (empty($iconFiles)) {
            $companyName = $this->faker->company();
            $slug = Str::slug($companyName, '-');
            $website = $slug . '.com';

            return [
                'name' => $companyName,
                'email' => 'info@' . $website,
                'website' => $website,
                'logo' => null,
            ];
        }

        // Rotate through the available icons so each gets used.
        $file = $iconFiles[$index % count($iconFiles)];
        $index++;

        $filename = pathinfo($file, PATHINFO_FILENAME);
        $companyName = str_replace(['-', '_'], ' ', $filename);
        $slug = Str::slug($companyName, '-');
        $website = $slug . '.com';

        return [
            'name' => $companyName,
            'email' => 'info@' . $website,
            'website' => $website,
            // Stored relative to the "public" disk root, e.g. "seed-icons/PNG/foo.png"
            'logo' => $file,
        ];
    }
}


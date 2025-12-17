<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmployeeFromAvatarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();

        if ($companies->isEmpty()) {
            return;
        }

        $disk = Storage::disk('public');

        $femaleFiles = collect($disk->files('seed-avatars/female'))
            ->filter(function (string $path): bool {
                return str_ends_with(strtolower($path), '.webp');
            })
            ->map(function (string $path): array {
                return ['path' => $path, 'gender' => 'Female'];
            });

        $maleFiles = collect($disk->files('seed-avatars/male'))
            ->filter(function (string $path): bool {
                return str_ends_with(strtolower($path), '.webp');
            })
            ->map(function (string $path): array {
                return ['path' => $path, 'gender' => 'Male'];
            });

        $avatars = $femaleFiles->merge($maleFiles)->shuffle()->values();

        if ($avatars->isEmpty()) {
            return;
        }

        $totalToUse = min(60, $avatars->count());
        $avatars = $avatars->take($totalToUse)->all();

        $companyIds = $companies->pluck('id')->all();
        $companyCounts = [];
        foreach ($companyIds as $id) {
            $companyCounts[$id] = 0;
        }

        // Ensure at least 1 employee per company
        foreach ($companyIds as $companyId) {
            if (empty($avatars)) {
                break;
            }

            $avatar = array_shift($avatars);
            $this->createEmployeeFromAvatar($avatar, $companyId);
            $companyCounts[$companyId]++;
        }

        // Distribute remaining employees, max 5 per company
        while (!empty($avatars)) {
            $eligible = array_keys(array_filter(
                $companyCounts,
                static function (int $count): bool {
                    return $count < 5;
                }
            ));

            if (empty($eligible)) {
                break;
            }

            $companyId = $eligible[array_rand($eligible)];

            $avatar = array_shift($avatars);
            $this->createEmployeeFromAvatar($avatar, $companyId);
            $companyCounts[$companyId]++;
        }
    }

    /**
     * Create a single employee from an avatar specification.
     */
    protected function createEmployeeFromAvatar(array $avatar, int $companyId): void
    {
        $company = Company::find($companyId);
        if (!$company) {
            return;
        }

        [$firstName, $lastName] = $this->namesFromFilename($avatar['path']);
        $gender = $avatar['gender'];

        $domain = $company->website ?: 'example.com';

        $localPart = strtolower(Str::ascii(substr($firstName, 0, 1) . $lastName));
        $localPart = preg_replace('/[^a-z0-9]+/i', '', $localPart);
        if ($localPart === '') {
            $localPart = 'user' . $companyId;
        }

        $email = $localPart . '@' . strtolower($domain);

        Employee::factory()->create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'gender' => $gender,
            'company_id' => $companyId,
            'email' => $email,
            'phone' => fake()->phoneNumber(),
            'avatar' => $avatar['path'],
        ]);
    }

    /**
     * Derive first and last names from an avatar filename.
     */
    protected function namesFromFilename(string $path): array
    {
        $base = pathinfo($path, PATHINFO_FILENAME);
        $base = str_replace(['_', '-'], ' ', $base);
        $parts = preg_split('/\s+/', trim($base)) ?: [];

        if (count($parts) >= 2) {
            $first = ucfirst($parts[0]);
            $last = ucfirst(implode(' ', array_slice($parts, 1)));
        } else {
            $first = ucfirst($base);
            $last = 'Employee';
        }

        return [$first, $last];
    }
}


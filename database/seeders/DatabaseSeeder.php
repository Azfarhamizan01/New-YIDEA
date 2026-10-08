<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Membership;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@yidea.test'],
            [
                'name' => 'YIDEA Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $client = User::updateOrCreate(
            ['email' => 'client@yidea.test'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'client',
                'specialty' => 'Brand Owner',
            ]
        );

        $designer = User::updateOrCreate(
            ['email' => 'designer@yidea.test'],
            [
                'name' => 'Rian Pratama',
                'password' => Hash::make('password'),
                'role' => 'designer',
                'specialty' => 'Logo & Brand Identity',
                'rating' => 4.95,
                'completed_projects' => 52,
            ]
        );

        foreach ([$client, $designer] as $user) {
            Membership::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'plan' => $user->id === $designer->id ? 'pro' : 'free',
                    'price' => $user->id === $designer->id ? 49000 : 0,
                    'starts_at' => now(),
                    'status' => 'active',
                ]
            );
        }

        Project::updateOrCreate(
            [
                'title' => 'Karakter Maskot 3D & Ilustrasi Branding Skincare "AuraGlow"'
            ],
            [
                'client_id' => $client->id,
                'category' => 'ILLUSTRATION & MASCOT',
                'description' => 'Kami ingin membuat karakter maskot 3D bernama Glowie beserta 5 pose ekspresi untuk branding skincare.',
                'budget' => 2800000,
                'timeline_days' => 5,
                'tags' => [
                    '3D Soft Glossy',
                    'Pastel Pink & Coral',
                    'Cute & Friendly'
                ],
                'status' => 'published',
            ]
        );

        Project::updateOrCreate(
            [
                'title' => 'Desain UI/UX Mobile App Fintech "DompetKreatif"'
            ],
            [
                'client_id' => $client->id,
                'category' => 'UI/UX MOBILE & WEB',
                'description' => 'Desain 12 screen utama aplikasi e-wallet lokal dengan dashboard saldo, transfer, invoice generator dan QRIS.',
                'budget' => 6500000,
                'timeline_days' => 14,
                'tags' => [
                    'Clean Modern',
                    'Dark/Light Support',
                    'Fintech Trust Blue'
                ],
                'status' => 'proposals_received',
            ]
        );

        Project::updateOrCreate(
            [
                'title' => 'Desain Brand Identity & Kemasan Kopi Artisan "Kopi Senja"'
            ],
            [
                'client_id' => $client->id,
                'category' => 'LOGO & BRAND IDENTITY',
                'description' => 'Identitas visual lengkap, typography, color palette dan tiga varian packaging pouch standing zipper.',
                'budget' => 3500000,
                'timeline_days' => 7,
                'tags' => [
                    'Minimalist',
                    'Earthy & Warm',
                    'Luxury Craft'
                ],
                'status' => 'paid',
                'winner_designer_id' => $designer->id,
            ]
        );
    }
}
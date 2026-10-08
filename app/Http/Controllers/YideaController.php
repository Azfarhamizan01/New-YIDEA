<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class YideaController extends Controller
{
    private function projectsData(): array
    {
        return [
            [
                'id' => 1,
                'category' => 'ILLUSTRATION & MASCOT',
                'status' => 'PUBLISHED',
                'title' => 'Karakter Maskot 3D & Ilustrasi Branding Skincare "AuraGlow"',
                'description' => 'Kami ingin membuat karakter maskot 3D bernama "Glowie" (bentuk tetesan embun ceria dan manis) beserta 5 pose ekspresi.',
                'tags' => ['#3D Soft Glossy', '#Pastel Pink & Coral', '#Cute & Friendly'],
                'budget' => 'Rp 2.800.000',
                'timeline' => '5 Days',
                'proposals' => 2,
            ],
            [
                'id' => 2,
                'category' => 'UI/UX MOBILE & WEB',
                'status' => 'PROPOSALS RECEIVED',
                'title' => 'Desain UI/UX Mobile App Fintech "DompetKreatif" (iOS & Android)',
                'description' => 'Mencari UI/UX designer handal untuk mendesain 12 screen utama aplikasi e-wallet lokal. Termasuk dashboard saldo, transfer instan, invoice generator, QRIS.',
                'tags' => ['#Clean Modern', '#Dark/Light Support', '#Fintech Trust Blue'],
                'budget' => 'Rp 6.500.000',
                'timeline' => '14 Days',
                'proposals' => 3,
            ],
            [
                'id' => 3,
                'category' => 'LOGO & BRAND IDENTITY',
                'status' => 'PAID',
                'title' => 'Desain Brand Identity & Kemasan Kopi Artisan "Kopi Senja"',
                'description' => 'Kami membutuhkan identitas visual lengkap (Logo mark, Typography, Color palette) dan 3 varian desain packaging pouch standing zipper untuk biji kopi single origin.',
                'tags' => ['#Minimalist', '#Earthy & Warm', '#Luxury Craft'],
                'budget' => 'Rp 3.500.000',
                'timeline' => '7 Days',
                'proposals' => 4,
            ],
            [
                'id' => 4,
                'category' => 'SOCIAL MEDIA & MARKETING KIT',
                'status' => 'COMPLETED',
                'title' => 'Desain 30 Konten Feed & Carousel Brand Fashion',
                'description' => 'Paket desain konten Instagram dengan sistem visual yang konsisten untuk campaign bulanan.',
                'tags' => ['#Editorial', '#Fashion', '#Social Kit'],
                'budget' => 'Rp 4.200.000',
                'timeline' => '10 Days',
                'proposals' => 6,
            ],
        ];
    }

    public function home()
    {
        $featured = array_slice($this->projectsData(), 0, 3);
        return view('home', compact('featured'));
    }

    public function projects(Request $request)
    {
        $projects = collect($this->projectsData());

        if ($request->filled('category') && $request->category !== 'All Categories') {
            $projects = $projects->filter(fn ($p) => $p['category'] === strtoupper($request->category));
        }

        if ($request->filled('q')) {
            $q = strtolower($request->q);
            $projects = $projects->filter(
                fn ($p) => str_contains(strtolower($p['title']), $q)
                    || str_contains(strtolower($p['description']), $q)
                    || str_contains(strtolower($p['category']), $q)
            );
        }

        return view('projects', [
            'projects' => $projects->values(),
            'categories' => [
                'All Categories',
                'Logo & Brand Identity',
                'UI/UX Mobile & Web',
                'Packaging & Label Produk',
                'Social Media & Marketing Kit',
                'Illustration & Mascot',
                '3D & Motion Graphics',
            ],
        ]);
    }

    public function project(int $project)
    {
        $item = collect($this->projectsData())->firstWhere('id', $project);

        abort_unless($item, 404);

        return view('project', ['project' => $item]);
    }

    public function membership()
    {
        return view('membership');
    }

    public function selectPlan(Request $request, string $plan)
    {
        $plans = [
            'free' => 'Desainer Free',
            'pro' => 'Desainer PRO',
            'studio' => 'Studio / Agency',
        ];

        abort_unless(isset($plans[$plan]), 404);

        return redirect()
            ->route('membership')
            ->with('success', "Paket {$plans[$plan]} berhasil dipilih. Hubungkan tombol ini ke payment gateway pada tahap berikutnya.");
    }

    public function workspace()
    {
        return view('workspace');
    }
}

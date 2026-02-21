<?php
// views/dashboard.php
$username = $_SESSION['username'];
$role = $_SESSION['role'];
$assigned_folder = $_SESSION['assigned_folder'];

// Mock scanning logic (In production, use glob())
$reports = [];
if ($role === 'admin' || $assigned_folder === 'reload-sanctuary') {
    $reports[] = [
        'name' => 'Reload Sanctuary - Feb 2026',
        'slug' => 'reload-sanctuary/index.html',
        'date' => 'Feb 19, 2026'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - 741 Studio</title>
    <link rel="icon" href="/assets/images/favicon.jpg" type="image/jpeg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;700&family=Montserrat:wght@400;600&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #F8F8F8;
            color: #000;
        }

        h1,
        h2,
        h3 {
            font-family: 'Jost', sans-serif;
        }

        .hero-bg {
            background: linear-gradient(135deg, #FCB141 0%, #F9A825 100%);
            color: black;
        }

        .report-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: 2px solid transparent;
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-color: #FCB141;
        }

        .btn-pill {
            background-color: #000;
            color: #FCB141;
            border-radius: 50px;
            padding: 8px 24px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            transition: all 0.2s;
        }

        .btn-pill:hover {
            background-color: #333;
            transform: scale(1.05);
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 h-20 flex justify-between items-center">
            <img src="https://741.studio/wp-content/uploads/2022/09/741studio-03-1-1.svg" alt="741 Studio" class="h-10">
            <div class="flex items-center gap-6">
                <span class="text-sm font-semibold hidden md:block">Hello,
                    <?= htmlspecialchars(ucfirst($username)) ?></span>
                <a href="index.php?route=profile"
                    class="text-sm font-bold hover:text-[#FCB141] transition border-b-2 border-transparent hover:border-[#FCB141]">PROFILE</a>
                <a href="index.php?route=logout"
                    class="text-sm font-bold hover:text-[#FCB141] transition border-b-2 border-transparent hover:border-[#FCB141]">LOGOUT</a>
            </div>
        </div>
    </header>

    <!-- Hero -->
    <div class="hero-bg py-16 px-6">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Your Reports</h1>
            <p class="text-lg font-medium opacity-80">Access your latest performance data and insights.</p>
        </div>
    </div>

    <!-- Content -->
    <main class="flex-grow px-6 py-12 -mt-10">
        <div class="max-w-6xl mx-auto grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($reports as $report): ?>
                <a href="index.php?route=view&report=<?= urlencode($report['slug']) ?>"
                    class="report-card bg-white p-8 rounded-2xl shadow-lg block group relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-black text-[#FCB141] text-xs font-bold px-3 py-1 rounded-bl-lg">
                        NEW</div>

                    <div class="mb-6">
                        <div
                            class="w-12 h-12 bg-[#FCB141]/20 rounded-full flex items-center justify-center mb-4 text-[#FCB141]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2 group-hover:text-[#FCB141] transition">
                            <?= htmlspecialchars($report['name']) ?>
                        </h3>
                        <p class="text-gray-500 text-sm">Published: <?= htmlspecialchars($report['date']) ?></p>
                    </div>

                    <div class="flex items-center justify-between mt-4 border-t border-gray-100 pt-4">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">HTML Interactive</span>
                        <span class="btn-pill">Open Report</span>
                    </div>
                </a>
            <?php endforeach; ?>

            <?php if (empty($reports)): ?>
                <div
                    class="col-span-3 text-center py-20 bg-white rounded-2xl shadow-sm border border-dashed border-gray-300">
                    <p class="text-gray-400 font-medium">No reports found for your account.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-black text-white py-8 text-center border-t-4 border-[#FCB141]">
        <p class="text-xs opacity-50 font-medium tracking-wide">&copy; <?= date('Y') ?> 741 STUDIO. ALL RIGHTS RESERVED.
        </p>
    </footer>
</body>

</html>
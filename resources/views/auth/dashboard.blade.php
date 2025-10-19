<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MemoryMaster</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <h1 class="text-2xl font-bold text-gray-800">MemoryMaster</h1>
            </div>
            <nav class="flex items-center space-x-6">
                <a href="#" class="text-gray-600 hover:text-blue-600">Methods</a>
                <a href="#" class="text-gray-600 hover:text-blue-600">Study Notes</a>
                <a href="#" class="text-gray-600 hover:text-blue-600">Progress</a>
                <span class="text-gray-600">Welcome, {{ session('user')['name'] }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-800">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <section class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">
                Master Your Memory With<br>
                <span class="text-blue-600">4 Legendary Methods</span>
            </h2>
            <p class="text-xl text-gray-600 mb-8">
                Discover and practice the 4 most famous memory techniques in the world. 
                From memory palaces to spaced repetition, transform learning into an art.
            </p>
            <div class="flex justify-center space-x-4">
                <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                    Start Learning Now →
                </button>
                <button class="border border-blue-600 text-blue-600 px-6 py-3 rounded-lg hover:bg-blue-50">
                    Learn More
                </button>
            </div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Cards</h3>
                <p class="text-3xl font-bold text-gray-800">1,247</p>
                <p class="text-green-600 text-sm">+20.1% from last month</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Study Streak</h3>
                <p class="text-3xl font-bold text-gray-800">15 days</p>
                <p class="text-green-600 text-sm">Personal record</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Accuracy</h3>
                <p class="text-3xl font-bold text-gray-800">89.5%</p>
                <p class="text-green-600 text-sm">+2.5% this week</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Study Time</h3>
                <p class="text-3xl font-bold text-gray-800">2.5h</p>
                <p class="text-gray-600 text-sm">Today</p>
            </div>
        </section>

        <section class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button class="bg-blue-100 text-blue-700 p-4 rounded-lg hover:bg-blue-200 text-left">
                    <h4 class="font-semibold">Create New Note</h4>
                    <p class="text-sm">Start a new study session</p>
                </button>
                
                <button class="bg-green-100 text-green-700 p-4 rounded-lg hover:bg-green-200 text-left">
                    <h4 class="font-semibold">Vocabulary Practice</h4>
                    <p class="text-sm">Learn new words</p>
                </button>
                
                <button class="bg-purple-100 text-purple-700 p-4 rounded-lg hover:bg-purple-200 text-left">
                    <h4 class="font-semibold">Formulas Review</h4>
                    <p class="text-sm">Practice math & science</p>
                </button>
            </div>
        </section>
    </main>
</body>
</html>
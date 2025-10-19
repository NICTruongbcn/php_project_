

<?php $__env->startSection('title', 'Dashboard - MemoryMaster'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-7xl mx-auto px-4 py-8">
        <section class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">
                Welcome back, <?php echo e(Auth::user()->name); ?>!
            </h2>
            <p class="text-xl text-gray-600 mb-8">
                Continue your memory mastery journey with our legendary techniques.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="<?php echo e(route('home')); ?>" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                    ← Back to Home
                </a>
                <button class="border border-blue-600 text-blue-600 px-6 py-3 rounded-lg hover:bg-blue-50">
                    Continue Learning
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

        <section class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button class="bg-blue-100 text-blue-700 p-4 rounded-lg hover:bg-blue-200 text-left transition-colors">
                    <h4 class="font-semibold">Create New Note</h4>
                    <p class="text-sm">Start a new study session</p>
                </button>
                
                <button class="bg-green-100 text-green-700 p-4 rounded-lg hover:bg-green-200 text-left transition-colors">
                    <h4 class="font-semibold">Vocabulary Practice</h4>
                    <p class="text-sm">Learn new words</p>
                </button>
                
                <button class="bg-purple-100 text-purple-700 p-4 rounded-lg hover:bg-purple-200 text-left transition-colors">
                    <h4 class="font-semibold">Formulas Review</h4>
                    <p class="text-sm">Practice math & science</p>
                </button>
            </div>
        </section>

        <section class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Recent Notes</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h4 class="font-semibold text-gray-800">English Vocabulary</h4>
                        <p class="text-sm text-gray-600">45 cards • Last studied: 2 hours ago</p>
                    </div>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Continue
                    </button>
                </div>
                
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h4 class="font-semibold text-gray-800">Math Formulas</h4>
                        <p class="text-sm text-gray-600">32 cards • Last studied: 1 day ago</p>
                    </div>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Review
                    </button>
                </div>
                
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h4 class="font-semibold text-gray-800">Science Concepts</h4>
                        <p class="text-sm text-gray-600">28 cards • Last studied: 3 days ago</p>
                    </div>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Start
                    </button>
                </div>
            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\study\NIC\DGL-123(intoduction PHP)\php_project\php_project_\resources\views/auth/dashboard.blade.php ENDPATH**/ ?>
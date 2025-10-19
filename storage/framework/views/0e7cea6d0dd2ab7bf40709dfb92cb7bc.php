<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MemoryMaster - Master Your Memory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .method-card:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
        .feature-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-full"></div>
                    <span class="text-xl font-bold text-gray-800">MemoryMaster</span>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#methods" class="text-gray-600 hover:text-blue-600 font-medium">Methods</a>
                    <a href="#features" class="text-gray-600 hover:text-blue-600 font-medium">Features</a>
                    <a href="#about" class="text-gray-600 hover:text-blue-600 font-medium">About</a>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="<?php echo e(route('login')); ?>" class="text-gray-600 hover:text-blue-600 font-medium">Login</a>
                    <a href="<?php echo e(route('register')); ?>" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero-gradient text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                    Master Your Memory With<br>
                    <span class="text-yellow-300">4 Legendary Methods</span>
                </h1>
                
                <p class="text-xl md:text-2xl mb-8 text-blue-100 max-w-3xl mx-auto leading-relaxed">
                    Discover and practice the 4 most famous memory techniques in the world. 
                    From memory palaces to spaced repetition, transform learning into an art.
                </p>

                <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6 mb-12">
                    <a href="<?php echo e(route('register')); ?>" class="bg-yellow-400 text-gray-900 px-8 py-4 rounded-lg hover:bg-yellow-300 font-bold text-lg shadow-lg transition-all duration-300 transform hover:scale-105">
                        Start Learning Now <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    <a href="#methods" class="border-2 border-white text-white px-8 py-4 rounded-lg hover:bg-white hover:text-blue-600 font-bold text-lg transition-all duration-300">
                        Learn More
                    </a>
                </div>

                <div class="flex justify-center space-x-8 mt-12">
                    <div class="text-center">
                        <div class="w-3 h-3 bg-yellow-400 rounded-full mx-auto mb-2"></div>
                        <span class="text-blue-100">Method of Loci</span>
                    </div>
                    <div class="text-center">
                        <div class="w-3 h-3 bg-green-400 rounded-full mx-auto mb-2"></div>
                        <span class="text-blue-100">Spaced Repetition</span>
                    </div>
                    <div class="text-center">
                        <div class="w-3 h-3 bg-purple-400 rounded-full mx-auto mb-2"></div>
                        <span class="text-blue-100">Mnemonic Devices</span>
                    </div>
                    <div class="text-center">
                        <div class="w-3 h-3 bg-red-400 rounded-full mx-auto mb-2"></div>
                        <span class="text-blue-100">Active Recall</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="methods" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">
                    4 Legendary Memory Methods
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Used and proven effective by world-class geniuses, scientists, and educators around the globe
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="method-card bg-gradient-to-br from-purple-50 to-blue-50 rounded-xl p-6 shadow-lg border border-purple-100">
                    <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-landmark text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Method of Loci</h3>
                    <p class="text-purple-600 font-semibold mb-3">Memory Palace</p>
                    <p class="text-gray-600 mb-4">
                        Use familiar spaces to store information. A technique used by Roman orators and memory champions.
                    </p>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Long-term retention
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Perfect for long lists
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Unlimited capacity
                        </div>
                    </div>
                    <button class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 font-medium transition-colors">
                        Learn More →
                    </button>
                </div>

                <div class="method-card bg-gradient-to-br from-green-50 to-blue-50 rounded-xl p-6 shadow-lg border border-green-100">
                    <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Spaced Repetition</h3>
                    <p class="text-green-600 font-semibold mb-3">Scientific Review</p>
                    <p class="text-gray-600 mb-4">
                        Review information at increasing intervals to optimize memory retention and learning efficiency.
                    </p>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Highly effective
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Time-saving
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Science-based
                        </div>
                    </div>
                    <button class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 font-medium transition-colors">
                        Learn More →
                    </button>
                </div>

                <div class="method-card bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl p-6 shadow-lg border border-yellow-100">
                    <div class="w-12 h-12 bg-yellow-600 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-lightbulb text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Mnemonic Devices</h3>
                    <p class="text-yellow-600 font-semibold mb-3">Memory Aids</p>
                    <p class="text-gray-600 mb-4">
                        Create associations, rhymes, and acronyms to transform hard-to-remember information into memorable content.
                    </p>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Creative
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Flexible
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Engaging
                        </div>
                    </div>
                    <button class="w-full bg-yellow-600 text-white py-2 rounded-lg hover:bg-yellow-700 font-medium transition-colors">
                        Learn More →
                    </button>
                </div>

                <div class="method-card bg-gradient-to-br from-red-50 to-pink-50 rounded-xl p-6 shadow-lg border border-red-100">
                    <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-brain text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Active Recall</h3>
                    <p class="text-red-600 font-semibold mb-3">Test Yourself</p>
                    <p class="text-gray-600 mb-4">
                        Actively test your knowledge instead of passive re-reading to strengthen memory consolidation.
                    </p>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Self-assessment
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Identify gaps
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Reinforce learning
                        </div>
                    </div>
                    <button class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 font-medium transition-colors">
                        Learn More →
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">
                    Powerful Learning Features
                </h2>
                <p class="text-xl text-gray-600">
                    Everything you need to master any subject efficiently
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="w-16 h-16 feature-icon rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-sticky-note text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Smart Notes</h3>
                    <p class="text-gray-600">
                        Create organized notes for vocabulary, formulas, and concepts with intelligent categorization.
                    </p>
                </div>

                <div class="text-center p-6">
                    <div class="w-16 h-16 feature-icon rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-bar text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Progress Tracking</h3>
                    <p class="text-gray-600">
                        Monitor your learning journey with detailed analytics and performance insights.
                    </p>
                </div>

                <div class="text-center p-6">
                    <div class="w-16 h-16 feature-icon rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-medal text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Achievement System</h3>
                    <p class="text-gray-600">
                        Stay motivated with badges, streaks, and milestones as you progress.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="py-20 bg-blue-600">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-white mb-6">
                Ready to Transform Your Memory?
            </h2>
            <p class="text-xl text-blue-100 mb-8">
                Join thousands of learners who have mastered the art of memory with our proven techniques.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
                <a href="<?php echo e(route('register')); ?>" class="bg-white text-blue-600 px-8 py-4 rounded-lg hover:bg-blue-50 font-bold text-lg shadow-lg transition-all duration-300 transform hover:scale-105">
                    Start Your Journey Free
                </a>
                <a href="#methods" class="border-2 border-white text-white px-8 py-4 rounded-lg hover:bg-white hover:text-blue-600 font-bold text-lg transition-all duration-300">
                    Explore Methods
                </a>
            </div>
        </div>
    </section>

    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-blue-600 rounded-full"></div>
                        <span class="text-xl font-bold">MemoryMaster</span>
                    </div>
                    <p class="text-gray-400">
                        Master your memory with legendary techniques used by memory champions worldwide.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Methods</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Method of Loci</a></li>
                        <li><a href="#" class="hover:text-white">Spaced Repetition</a></li>
                        <li><a href="#" class="hover:text-white">Mnemonic Devices</a></li>
                        <li><a href="#" class="hover:text-white">Active Recall</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Resources</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Blog</a></li>
                        <li><a href="#" class="hover:text-white">Tutorials</a></li>
                        <li><a href="#" class="hover:text-white">Research</a></li>
                        <li><a href="#" class="hover:text-white">Support</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Connect</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 MemoryMaster. All rights reserved.(Pham Hong Truong)</p>
            </div>
        </div>
    </footer>

    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html><?php /**PATH C:\study\NIC\DGL-123(intoduction PHP)\web_project\php-web-project-milestone-1-NICTruongbcn\web_project\resources\views/welcome.blade.php ENDPATH**/ ?>
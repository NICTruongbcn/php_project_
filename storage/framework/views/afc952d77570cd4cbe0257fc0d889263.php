

<?php $__env->startSection('title', $note->title . ' - MemoryMaster'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <?php if($note->type === 'vocab'): ?>
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book text-green-600"></i>
                        </div>
                    <?php elseif($note->type === 'formula'): ?>
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-square-root-alt text-purple-600"></i>
                        </div>
                    <?php else: ?>
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-sticky-note text-blue-600"></i>
                        </div>
                    <?php endif; ?>
                    <h1 class="text-3xl font-bold text-gray-800"><?php echo e($note->title); ?></h1>
                </div>
                
                <?php if($note->description): ?>
                    <p class="text-gray-600"><?php echo e($note->description); ?></p>
                <?php endif; ?>
                
                <div class="flex items-center space-x-4 mt-3 text-sm text-gray-500">
                    <span class="flex items-center">
                        <i class="fas fa-layer-group mr-1"></i>
                        <?php echo e($pages->count()); ?>/<?php echo e($note->page_limit); ?> pages
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-calendar mr-1"></i>
                        Created <?php echo e($note->created_at->format('M d, Y')); ?>

                    </span>
                    <?php if($note->subject): ?>
                        <span class="flex items-center">
                            <i class="fas fa-tag mr-1"></i>
                            <?php echo e(ucfirst($note->subject)); ?>

                        </span>
                    <?php endif; ?>
                    <span class="flex items-center">
                        <i class="fas fa-<?php echo e($note->type === 'vocab' ? 'book' : ($note->type === 'formula' ? 'square-root-alt' : 'sticky-note')); ?> mr-1"></i>
                        <?php echo e(ucfirst($note->type)); ?> Note
                    </span>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <?php if(!$note->is_completed): ?>
                    <a href="<?php echo e(route('pages.create', $note->id)); ?>" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        + Add Page
                    </a>
                <?php endif; ?>
                <?php if($pages->count() > 0): ?>
                    <a href="<?php echo e(route('study.show', $note->id)); ?>" 
                       class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                        Start Studying
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mt-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Progress</span>
                <span class="text-sm text-gray-500"><?php echo e($pages->count()); ?>/<?php echo e($note->page_limit); ?> pages</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-blue-600 h-3 rounded-full transition-all duration-500" 
                     style="width: <?php echo e(min(($pages->count() / $note->page_limit) * 100, 100)); ?>%"></div>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Pages List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-xl font-semibold text-gray-800">Pages (<?php echo e($pages->count()); ?>)</h2>
        </div>

        <?php if($pages->count() > 0): ?>
            <div class="divide-y divide-gray-200">
                <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-sm font-medium">
                                        #<?php echo e($page->position); ?>

                                    </span>
                                    <?php if($page->meta && isset(json_decode($page->meta, true)['difficulty'])): ?>
                                        <?php
                                            $difficulty = json_decode($page->meta, true)['difficulty'];
                                            $colorClasses = [
                                                'easy' => 'bg-green-100 text-green-800',
                                                'medium' => 'bg-yellow-100 text-yellow-800',
                                                'hard' => 'bg-red-100 text-red-800'
                                            ];
                                        ?>
                                        <span class="text-xs font-semibold px-2 py-1 rounded <?php echo e($colorClasses[$difficulty] ?? 'bg-gray-100 text-gray-800'); ?>">
                                            <?php echo e(ucfirst($difficulty)); ?>

                                        </span>
                                    <?php endif; ?>

                                    <!-- Hiển thị word type cho vocab notes -->
                                    <?php if($note->type === 'vocab' && $page->meta && isset(json_decode($page->meta, true)['word_type'])): ?>
                                        <?php
                                            $wordType = json_decode($page->meta, true)['word_type'];
                                            $wordTypeColors = [
                                                'noun' => 'bg-blue-100 text-blue-800',
                                                'verb' => 'bg-green-100 text-green-800', 
                                                'adjective' => 'bg-yellow-100 text-yellow-800',
                                                'adverb' => 'bg-purple-100 text-purple-800',
                                                'phrase' => 'bg-indigo-100 text-indigo-800',
                                                'idiom' => 'bg-pink-100 text-pink-800',
                                                'general' => 'bg-gray-100 text-gray-800'
                                            ];
                                        ?>
                                        <span class="text-xs font-semibold px-2 py-1 rounded <?php echo e($wordTypeColors[$wordType] ?? 'bg-gray-100 text-gray-800'); ?>">
                                            <?php echo e(ucfirst($wordType)); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Front Content -->
                                    <div>
                                        <h4 class="font-semibold text-gray-700 mb-2 text-sm">
                                            <?php if($note->type === 'vocab'): ?> Term
                                            <?php elseif($note->type === 'formula'): ?> Formula
                                            <?php else: ?> Front <?php endif; ?>
                                        </h4>
                                        <div class="text-gray-800">
                                            <?php if($page->front_text): ?>
                                                <?php if($note->type === 'vocab'): ?>
                                                    <div class="font-semibold text-lg text-gray-900 mb-2"><?php echo e($page->front_text); ?></div>
                                                <?php else: ?>
                                                    <p class="mb-2"><?php echo e($page->front_text); ?></p>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php if($page->front_latex): ?>
                                                <div class="bg-gray-100 p-3 rounded font-mono text-sm mb-2">
                                                    <div class="font-semibold text-xs text-gray-500 mb-1">LaTeX:</div>
                                                    <?php echo e($page->front_latex); ?>

                                                </div>
                                            <?php endif; ?>
                                            <?php if($page->front_image): ?>
                                                <div class="mt-2">
                                                    <div class="font-semibold text-xs text-gray-500 mb-1">Image:</div>
                                                    <img src="<?php echo e(Storage::url($page->front_image)); ?>" 
                                                         alt="Front image" 
                                                         class="max-w-full h-auto rounded-lg border border-gray-300 max-h-48 object-contain">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Back Content -->
                                    <div>
                                        <h4 class="font-semibold text-gray-700 mb-2 text-sm">
                                            <?php if($note->type === 'vocab'): ?> Definition
                                            <?php elseif($note->type === 'formula'): ?> Explanation
                                            <?php else: ?> Back <?php endif; ?>
                                        </h4>
                                        <div class="text-gray-800">
                                            <?php if($page->back_text): ?>
                                                <p class="mb-2"><?php echo e($page->back_text); ?></p>
                                            <?php endif; ?>
                                            <?php if($page->back_latex): ?>
                                                <div class="bg-gray-100 p-3 rounded font-mono text-sm mb-2">
                                                    <div class="font-semibold text-xs text-gray-500 mb-1">LaTeX:</div>
                                                    <?php echo e($page->back_latex); ?>

                                                </div>
                                            <?php endif; ?>
                                            <?php if($page->back_image): ?>
                                                <div class="mt-2">
                                                    <div class="font-semibold text-xs text-gray-500 mb-1">Image:</div>
                                                    <img src="<?php echo e(Storage::url($page->back_image)); ?>" 
                                                         alt="Back image" 
                                                         class="max-w-full h-auto rounded-lg border border-gray-300 max-h-48 object-contain">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hiển thị example sentence cho vocab notes -->
                                <?php if($note->type === 'vocab' && $page->meta && isset(json_decode($page->meta, true)['example_sentence'])): ?>
                                    <?php
                                        $exampleSentence = json_decode($page->meta, true)['example_sentence'];
                                    ?>
                                    <?php if($exampleSentence): ?>
                                        <div class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                            <div class="font-semibold text-sm text-blue-800 mb-1">Example Sentence:</div>
                                            <p class="text-blue-900 italic">"<?php echo e($exampleSentence); ?>"</p>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if($page->meta && isset(json_decode($page->meta, true)['tags'])): ?>
                                    <?php
                                        $tags = json_decode($page->meta, true)['tags'];
                                    ?>
                                    <?php if(!empty($tags)): ?>
                                        <div class="mt-3 flex flex-wrap gap-1">
                                            <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">
                                                    <?php echo e($tag); ?>

                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>

                            <div class="flex space-x-2 ml-4">
                                <a href="<?php echo e(route('pages.edit', $page->id)); ?>" 
                                   class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="<?php echo e(route('pages.destroy', $page->id)); ?>" 
                                      class="inline" onsubmit="return confirm('Are you sure you want to delete this page?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-sticky-note text-gray-400 text-2xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-600 mb-2">No pages yet</h4>
                <p class="text-gray-500 mb-6">Start by adding your first page to this note</p>
                <a href="<?php echo e(route('pages.create', $note->id)); ?>" 
                   class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    Add First Page
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Quick Stats -->
    <?php if($pages->count() > 0): ?>
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-layer-group text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Pages</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo e($pages->count()); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Completion</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo e(min(round(($pages->count() / $note->page_limit) * 100), 100)); ?>%</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-clock text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Created</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo e($note->created_at->format('M d')); ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Action Buttons -->
    <div class="flex justify-between items-center mt-6">
        <a href="<?php echo e(route('dashboard')); ?>" 
           class="text-blue-600 hover:text-blue-800 transition-colors font-semibold">
            ← Back to Dashboard
        </a>
        
        <div class="flex space-x-3">
            <a href="<?php echo e(route('notes.edit', $note->id)); ?>" 
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                Edit Note
            </a>
            <?php if($pages->count() > 0): ?>
                <a href="<?php echo e(route('study.show', $note->id)); ?>" 
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors font-semibold">
                    Start Studying
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.object-contain {
    object-fit: contain;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\study\NIC\DGL-123(intoduction PHP)\php_project\php_project_\resources\views/notes/show.blade.php ENDPATH**/ ?>
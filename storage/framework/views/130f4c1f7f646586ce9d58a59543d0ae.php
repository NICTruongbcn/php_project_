

<?php $__env->startSection('title', 'Add Page - MemoryMaster'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Add New Page</h1>
                    <p class="text-gray-600">Add content to your note: "<?php echo e($note->title); ?>"</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500 mb-1">Progress</div>
                    <div class="flex items-center space-x-2">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" 
                                 style="width: <?php echo e(min(($note->pages()->count() / $note->page_limit) * 100, 100)); ?>%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-700">
                            <?php echo e($note->pages()->count()); ?>/<?php echo e($note->page_limit); ?>

                        </span>
                    </div>
                </div>
            </div>

            <?php if(session('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>

        <form method="POST" action="<?php echo e(route('pages.store', $note->id)); ?>" class="space-y-6">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Front Side</h3>
                        <span class="text-sm text-gray-500">Question / Term</span>
                    </div>
                    
                    <div>
                        <label for="front_text" class="block text-sm font-medium text-gray-700 mb-2">
                            Text Content
                        </label>
                        <textarea name="front_text" id="front_text" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                  placeholder="Enter the question, term, or front content"><?php echo e(old('front_text')); ?></textarea>
                    </div>

                    <div>
                        <label for="front_latex" class="block text-sm font-medium text-gray-700 mb-2">
                            LaTeX Formula (Optional)
                        </label>
                        <textarea name="front_latex" id="front_latex" rows="2"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors font-mono text-sm"
                                  placeholder="Enter LaTeX formula (e.g., E = mc^2)"><?php echo e(old('front_latex')); ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">For mathematical formulas and equations</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Back Side</h3>
                        <span class="text-sm text-gray-500">Answer / Definition</span>
                    </div>
                    
                    <div>
                        <label for="back_text" class="block text-sm font-medium text-gray-700 mb-2">
                            Text Content
                        </label>
                        <textarea name="back_text" id="back_text" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                  placeholder="Enter the answer, definition, or back content"><?php echo e(old('back_text')); ?></textarea>
                    </div>

                    <div>
                        <label for="back_latex" class="block text-sm font-medium text-gray-700 mb-2">
                            LaTeX Formula (Optional)
                        </label>
                        <textarea name="back_latex" id="back_latex" rows="2"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors font-mono text-sm"
                                  placeholder="Enter LaTeX formula"><?php echo e(old('back_latex')); ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">For mathematical formulas and equations</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Additional Options</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">
                            Difficulty Level
                        </label>
                        <select name="difficulty" id="difficulty"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="easy">Easy</option>
                            <option value="medium" selected>Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                    </div>

                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                            Tags (Optional)
                        </label>
                        <input type="text" name="tags" id="tags"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Enter tags separated by commas"
                               value="<?php echo e(old('tags')); ?>">
                        <p class="text-xs text-gray-500 mt-1">e.g., vocabulary, math, important</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                <div class="flex space-x-4">
                    <a href="<?php echo e(route('notes.show', $note->id)); ?>" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        ← Back to Note
                    </a>
                </div>
                
                <div class="flex space-x-4">
                    <button type="submit" name="add_another" value="true"
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                        Save & Add Another
                    </button>
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                        Save & Finish
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Preview</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 min-h-[200px]">
                <h4 class="font-semibold text-gray-700 mb-3">Front Preview</h4>
                <div id="front-preview" class="text-gray-600">
                    Content will appear here...
                </div>
            </div>

            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 min-h-[200px]">
                <h4 class="font-semibold text-gray-700 mb-3">Back Preview</h4>
                <div id="back-preview" class="text-gray-600">
                    Content will appear here...
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const frontText = document.getElementById('front_text');
    const backText = document.getElementById('back_text');
    const frontLatex = document.getElementById('front_latex');
    const backLatex = document.getElementById('back_latex');
    const frontPreview = document.getElementById('front-preview');
    const backPreview = document.getElementById('back-preview');

    function updatePreview() {
        let frontContent = frontText.value;
        if (frontLatex.value) {
            frontContent += (frontContent ? '<br><br>' : '') + 
                           '<div class="bg-gray-100 p-3 rounded font-mono text-sm">' + 
                           'LaTeX: ' + frontLatex.value + 
                           '</div>';
        }
        frontPreview.innerHTML = frontContent || 'Content will appear here...';

        let backContent = backText.value;
        if (backLatex.value) {
            backContent += (backContent ? '<br><br>' : '') + 
                          '<div class="bg-gray-100 p-3 rounded font-mono text-sm">' + 
                          'LaTeX: ' + backLatex.value + 
                          '</div>';
        }
        backPreview.innerHTML = backContent || 'Content will appear here...';
    }

    [frontText, backText, frontLatex, backLatex].forEach(element => {
        element.addEventListener('input', updatePreview);
    });

    updatePreview();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\study\NIC\DGL-123(intoduction PHP)\php_project\php_project_\resources\views/pages/create.blade.php ENDPATH**/ ?>
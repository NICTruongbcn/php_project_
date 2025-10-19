<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Verify Your Email Address</h4>
                    </div>
                    <div class="card-body">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success">
                                <?php echo e(session('success')); ?>

                            </div>
                        <?php endif; ?>

                        <?php if(session('warning')): ?>
                            <div class="alert alert-warning">
                                <?php echo e(session('warning')); ?>

                            </div>
                        <?php endif; ?>

                        <div class="alert alert-info">
                            <p>A verification email has been sent to your email address.</p>
                            <p>Please check your inbox and click the verification link to activate your account.</p>
                        </div>

                        <div class="mt-3">
                            <p>Didn't receive the email?</p>
                            <form action="<?php echo e(route('verification.resend')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="email" value="<?php echo e(old('email')); ?>">
                                <button type="submit" class="btn btn-primary">Resend Verification Email</button>
                            </form>
                        </div>

                        <div class="mt-3">
                            <a href="<?php echo e(route('login')); ?>" class="btn btn-link">Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\study\NIC\DGL-123(intoduction PHP)\web_project\php-web-project-milestone-1-NICTruongbcn\web_project\resources\views/auth/verify-email-notice.blade.php ENDPATH**/ ?>
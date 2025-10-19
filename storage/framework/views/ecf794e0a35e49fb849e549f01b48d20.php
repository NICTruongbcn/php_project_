<!DOCTYPE html>
<html>
<head>
    <title>Verify Your Email</title>
</head>
<body>
    <h2>Hello <?php echo e($user->name); ?>,</h2>
    
    <p>Thank you for registering! Please click the button below to verify your email address:</p>
    
    <a href="<?php echo e($verificationUrl); ?>" 
       style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
        Verify Email Address
    </a>
    
    <p>Or copy and paste this link in your browser:</p>
    <p><?php echo e($verificationUrl); ?></p>
    
    <p>If you did not create an account, no further action is required.</p>
    
    <p>Thank you,<br><?php echo e(config('app.name')); ?></p>
</body>
</html><?php /**PATH C:\study\NIC\DGL-123(intoduction PHP)\web_project\php-web-project-milestone-1-NICTruongbcn\web_project\resources\views/emails/verification.blade.php ENDPATH**/ ?>
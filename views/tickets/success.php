<?php include base_path('views/partials/header.php'); ?>

<div style="width: 50%; margin: 0 auto">
    <h1>Ticket Purchase Confirmation</h1>
    <p>Thank you, <strong><?= $attributes['name'] ?></strong>, for purchasing tickets.</p>
    <p>We have sent a confirmation email to <strong><?= $attributes['email'] ?></strong>.</p>
    <p>We may contact you at <strong><?= $attributes['phone'] ?></strong> if necessary.</p>
    <a href="/">Go Back</a>
</div>

<?php include base_path('views/partials/footer.php'); ?>
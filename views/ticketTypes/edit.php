<?php include base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Edit Ticket Type "<?= $ticketType['type'] ?>"</h1>
        <div class="action-buttons">
            <a href="/types" class="btn btn-primary">Go Back</a>
        </div>
    </div>
    <hr>
    <form class="mt-3" action="/types/update" method="POST">
        <input type="hidden" name="_method" value="PATCH">
        <input type="hidden" name="id" value="<?= $ticketType['id'] ?>">
        <div class="d-grid align-items-center gap-1 column-gap-4" style="grid-template-columns: 1fr 1fr 1fr">
            <div class="">
                <label for="type" class="mt-1 form-label">Ticket Type</label>
                <input type="text" class="form-control" id="type" name="type" value="<?= $ticketType['type'] ?>">
            </div>
            <div class="">
                <label for="price" class="mt-1 form-label">Ticket Price</label>
                <input type="text" class="form-control" id="price" name="price" value="<?= $ticketType['price'] ?>">
            </div>
            <div class="" style="margin-top: 2rem">
                <button type="submit" class="btn btn-primary" >Save</button>
            </div>
            <p style="color: red; font-size: small; margin: 0;"><?= $errors['type'] ?? '' ?></p>
            <p style="color: red; font-size: small; margin: 0;"><?= $errors['price'] ?? '' ?></p>
        </div>
    </form>
</main>

<?php include base_path('views/partials/footer.php'); ?>
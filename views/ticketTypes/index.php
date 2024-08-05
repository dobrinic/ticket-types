<?php include base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Ticket Types</h1>
        <div class="action-buttons">
            <a href="/" class="btn btn-primary">Home</a>
            <a href="/types/create" class="btn btn-primary">Add new</a>
        </div>
    </div>
    <hr>
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $message['type'] ?> alert-dismissible fade show" role="alert">
            <?= $message['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>id</th>
                <th>Type</th>
                <th>Price</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ticketTypes as $ticketType): ?>
                <tr>
                    <td><?= $ticketType['id'] ?></td>
                    <td><?= $ticketType['type'] ?></td>
                    <td><?= $ticketType['price'] ?></td>
                    <td style="width: 110px">
                        <a href="/types/edit?id=<?= $ticketType['id'] ?>" class="btn btn-info"><i class="bi bi-pencil"></i></a>
                        <form id="delete-form" class="hidden d-inline" method="POST" action="/types/destroy">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="id" value="<?= $ticketType['id'] ?>">
                            <button class="btn btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include base_path('views/partials/footer.php'); ?>

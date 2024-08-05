<?php include base_path('views/partials/header.php'); ?>

<main class="container my-3 d-flex flex-column flex-grow-1">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Purchase Tickets</h1>
        <div class="action-buttons">
            <a href="/types" class="btn btn-primary">Ticket Types</a>
        </div>
    </div>
    <hr>
    <form action="/tickets" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <?php if (isset($errors['name'])) : ?>
            <p style="color: red; font-size: small; margin-top: 5px;"><?= $errors['name'] ?></p>
        <?php endif; ?>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <?php if (isset($errors['email'])) : ?>
            <p style="color: red; font-size: small; margin-top: 5px;"><?= $errors['email'] ?></p>
        <?php endif; ?>
        <br><br>

        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" required>
        <?php if (isset($errors['phone'])) : ?>
            <p style="color: red; font-size: small; margin-top: 5px;"><?= $errors['phone'] ?></p>
        <?php endif; ?>
        <br><br>

        <div id="tickets">
            <h2>Tickets</h2>
            <div class="ticket">
                <label for="ticket_type">Ticket Type:</label>
                <select id="ticket_type" name="tickets[0][type_id]" class="ticketTypeSelect" onchange="updatePrice(this)"
                        required>
                    <?php foreach ($ticketTypes as $ticketType): ?>
                        <option value="<?php echo htmlspecialchars($ticketType['id']); ?>"
                                data-price="<?php echo htmlspecialchars($ticketType['price']); ?>">
                            <?php echo htmlspecialchars($ticketType['type']); ?> -
                            $<?php echo htmlspecialchars($ticketType['price']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['tickets[0][type_id]'])) : ?>
                    <p style="color: red; font-size: small; margin-top: 5px;"><?= $errors['tickets[0][type_id]'] ?></p>
                <?php endif; ?>
                <br>
                <br>

                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="tickets[0][quantity]" class="ticketQuantityInput" min="1"
                       onchange="updatePrice(this)" required>
                <?php if (isset($errors['tickets[0][quantity]'])) : ?>
                    <p style="color: red; font-size: small; margin-top: 5px;"><?= $errors['tickets[0][quantity]'] ?></p>
                <?php endif; ?>
                <br><br>

                <label for="total_price">Total Price:</label>
                <input type="text" id="total_price" name="tickets[0][total_price]" class="ticketTotalPrice" readonly><br><br>
            </div>
        </div>

        <button type="button" onclick="addTicket()">Add Another Ticket</button><br><br>

        <input type="submit" value="Purchase">

        <input type="hidden" id="ticketTypes" value='<?php echo json_encode($ticketTypes); ?>'>
    </form>
</main>
<script>
    let ticketCount = 1;

    function addTicket() {
        const ticketsDiv = document.getElementById('tickets');
        const newTicketDiv = document.createElement('div');
        newTicketDiv.className = 'ticket';
        ``
        const ticketTypes = JSON.parse(document.getElementById('ticketTypes').value);
        let optionsHtml = '';
        ticketTypes.forEach(ticketType => {
            optionsHtml += `<option value="${ticketType.id}" data-price="${ticketType.price}">${ticketType.type} - $${ticketType.price}</option>`;
        });

        newTicketDiv.innerHTML = `
        <label for="ticket_type">Ticket Type:</label>
        <select name="tickets[${ticketCount}][type_id]" class="ticketTypeSelect" onchange="updatePrice(this)" required>
            ${optionsHtml}
        </select><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" name="tickets[${ticketCount}][quantity]" class="ticketQuantityInput" min="1" onchange="updatePrice(this)" required><br><br>

        <label for="total_price">Total Price:</label>
        <input type="text" name="tickets[${ticketCount}][total_price]" class="ticketTotalPrice" readonly><br><br>
    `;

        ticketsDiv.appendChild(newTicketDiv);
        ticketCount++;
    }

    function updatePrice(element) {
        const ticketDiv = element.closest('.ticket');
        const quantityInput = ticketDiv.querySelector('.ticketQuantityInput');

        if (!quantityInput.value) {
            return;
        }

        const ticketTypeSelect = ticketDiv.querySelector('.ticketTypeSelect');
        const totalPriceInput = ticketDiv.querySelector('.ticketTotalPrice');

        const price = parseFloat(ticketTypeSelect.selectedOptions[0].getAttribute('data-price'));
        const quantity = parseInt(quantityInput.value);

        const totalPrice = price * quantity;
        totalPriceInput.value = `$${totalPrice.toFixed(2)}`;
    }
</script>
</body>
</html>

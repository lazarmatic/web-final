var OrdersService = {
    // ✅ Task 1: Load orders into table
    load_orders: function () {
        RestClient.get('orders/report', function (data) {
            let tbody = $("#order-details tbody").empty();
            tbody.empty();

            data.forEach(function (order) {
                let row = `
                  <tr>
                    <td class="text-center">
                      <button type="button" class="btn btn-success btn-details" data-id="${order.order_number}" data-bs-toggle="modal" data-bs-target="#order-details-modal">
                        Details
                      </button>
                    </td>
                    <td>${order.order_number}</td>
                    <td>${order.total_amount}</td>
                  </tr>`;
                tbody.append(row);
            });

            // Optional: The table fully paginated with search and order. (Makes regular table a DataTable) 
            if (!$.fn.DataTable.isDataTable("#order-details")) {
                $("#order-details").DataTable();
            }
        });
    },

    // ✅ Task 2: Load details for a single order
    load_order_details: function(order_id) {
    RestClient.get('order/details/${order_id}', function(data) {
        let tbody = $("#order-details-modal tbody");
        tbody.empty();

        let total = 0;
        data.forEach(function(item, index) {
            const subtotal = item.quantityOrdered * item.priceEach;
            total += subtotal;
            const row = `
              <tr>
                <th scope="row">${index + 1}</th>
                <td>${item.productName}</td>
                <td>${item.quantityOrdered}</td>
                <td>${item.priceEach}</td>
              </tr>`;
            tbody.append(row);
        });

        tbody.append(`
          <tr>
            <td colspan="3"><strong>Total bill</strong></td>
            <td><strong>${total.toFixed(2)}</strong></td>
          </tr>
        `);
    });
}
};

// ✅ Document ready
$(document).ready(function () {
    // Load orders on page load
    OrdersService.load_orders();
                
    // Handle Details button click (delegated because buttons are dynamic)
    $("#order-details").on("click", ".btn-details", function () {
        let order_id = $(this).data("id");
        OrdersService.load_order_details(order_id);
    });
});

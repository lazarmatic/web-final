
var EmployeesService = {

  // ✅ Task 1: Load employees into table
    load_employees: function () {
        RestClient.get(`employees/performance`, function (data) {
            let tbody = $("#employee-performance tbody");
            tbody.empty();

            data.forEach(function (emp) {
                let row = `
                  <tr>
                    <td class="text-center">
                      <div class="btn-group" role="group">
                        <button type="button" class="btn btn-warning" onclick="EmployeesService.edit_employee(${emp.id})">Edit</button>
                        <button type="button" class="btn btn-danger" onclick="EmployeesService.delete_employee(${emp.id})">Delete</button>
                      </div>
                    </td>
                    <td>${emp.fullName}</td>
                    <td>${emp.email}</td>
                    <td>${emp.total}</td>
                  </tr>`;
                tbody.append(row);
            });
        });
    },

  // ✅ Task 2: Delete logic
    delete_employee: function (employee_id) {
        if (confirm("Do you want to delete employee with the id " + employee_id + "?")) {
            RestClient.delete(`employee/delete/${employee_id}`, function (response) {
                toastr.success(response.message);
                EmployeesService.load_employees(); // reload table
            });
        }
    },

//                                     A LITTLE HARDER WAY TO EDIT -- EASIER IN FINAL-MAKEUP

    // ✅ Task 3a: Open modal and populate with data
    edit_employee: function (employee_id) {
        RestClient.get(`employee/${employee_id}`, function (data) {
            $("#employeeNumber").val(data.employeeNumber);
            $("#firstName").val(data.firstName);
            $("#lastName").val(data.lastName);
            $("#email").val(data.email);

            $("#edit-employee-modal").modal("show");
        });
    },

    // ✅ Task 3b: Handle modal save
    edit_modal_submit: function() {
        $("#edit-employee-modal form").on("submit", function(e) {
            e.preventDefault();
            let employee_id = $("#employeeNumber").val();
            let data = {
                firstName: $("#firstName").val(),
                lastName: $("#lastName").val(),
                email: $("#email").val()
            };
            RestClient.put(`employee/edit/${employee_id}`, data, function() {
                toastr.success("Employee updated successfully");
                $("#edit-employee-modal").modal("hide");
                EmployeesService.load_employees();
            });
        });
    }
};

$(document).ready(function () {
    EmployeesService.load_employees();
    EmployeesService.edit_modal_submit();
});

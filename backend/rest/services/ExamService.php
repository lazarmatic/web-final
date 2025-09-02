<?php
require_once __DIR__ . "/../dao/ExamDao.php";

class ExamService
{
    protected $dao;

    public function __construct()
    {
        $this->dao = new ExamDao();
    }

    /** TODO
     * Implement service method used to get employees performance report
     */
    public function employees_performance_report()
    {
        return $this->dao->employees_performance_report();
    }

    /** TODO
     * Implement service method used to delete employee by id
     */
    public function delete_employee($employee_id)
    {
        return $this->dao->delete_employee($employee_id);
    }

    /** TODO
     * Implement service method used to edit employee data
     */
    public function edit_employee($employee_id, $data)
    {
        return $this->dao->edit_employee($employee_id, $data);
    }

    /** TODO
     * Implement service method used to get orders report
     */
    public function get_orders_report()
    {
        $data = $this->dao->get_orders_report();
        foreach ($data as &$row) {
            $id = $row['order_number'];
            $row['details'] =
                '<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#order-details-modal" data-bs-id="@' . $id . '">Details</button>';
        }
        return $data;
    }

    /** TODO
     * Implement service method used to get all products in a single order
     */
    public function get_order_details($order_id)
    {
        return $this->dao->get_order_details($order_id);
    }

    public function get_employee($employee_id)
    {
        return $this->dao->get_employee($employee_id);
    }
}

<?php

class ExamDao
{

  private $conn;

  /**
   * constructor of dao class
   */
  public function __construct()
  {
    try {
      $dsn = "mysql:host=localhost;port=3306;dbname=webfinale";
      $user = "root";
      $passw = "zollero0000";

      $this->conn = new PDO($dsn, $user, $passw);

      /** TODO
       * List parameters such as servername, username, password, schema. Make sure to use appropriate port
       */

      /** TODO
       * Create new connection
       */
      echo "Connected successfully";
    } catch (PDOException $e) {
      echo "Connemployees_performance_reportection failed: " . $e->getMessage();
    }
  }

  // helper function

  public function query($sql, $params = [])
  {
    $result = $this->conn->prepare($sql);
    $result->execute($params);
    return $result;
  }

  /** TODO
   * Implement DAO method used to get employees performance report
   */
  public function employees_performance_report()
  {
    $sql = "SELECT e.employeeNumber as id, CONCAT(e.firstName, ' ' , e.lastName) as fullName , e.email , IFNULL(SUM(p.amount),0) as total 
    FROM employees e 
    left join customers c on e.employeeNumber = c.salesRepEmployeeNumber 
    left join payments p on c.customerNumber = p.customerNumber
    group by e.employeeNumber";
    return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }

  /** TODO
   * Implement DAO method used to delete employee by id
   */
  public function delete_employee($employee_id)
  {
    $sql = "DELETE FROM employees e WHERE e.employeeNumber = :employee_id";
    return $this->query($sql, ['employee_id' => $employee_id]);
  }

  /** TODO
   * Implement DAO method used to edit employee data
   */
  public function edit_employee($employee_id, $data)
  {
    $sql = "UPDATE employees SET firstName = :firstName , lastName = :lastName , email = :email WHERE employeeNumber = :employee_id";
    return $this->query($sql, [
      'firstName' => $data['firstName'],
      'lastName' => $data['lastName'],
      'email' => $data['email'],
      'employee_id' => $employee_id
    ]);
  }

  /** TODO
   * Implement DAO method used to get orders report
   */
  public function get_orders_report()
  {
    $sql = "SELECT o.orderNumber as order_number, 
                  IFNULL(SUM(od.quantityOrdered * od.priceEach),0) as total_amount
                  from orders o left join orderdetails od on o.orderNumber = od.orderNumber
                  group by o.orderNumber";

    return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }

  /** TODO
   * Implement DAO method used to get all products in a single order
   */
  public function get_order_details($order_id)
  {
    $sql = "SELECT p.productName , od.quantityOrdered , od.priceEach 
    from orderdetails od
    left join products p on od.productCode = p.productCode
    where od.orderNumber = :order_id";
    return $this->query($sql, ['order_id' => $order_id])->fetchAll(PDO::FETCH_ASSOC);
  }

  // additional 

  public function get_employee($employee_id)
  {
    $sql = "SELECT e.employeeNumber, e.firstName , e.lastName , e.email from employees e where e.employeeNumber = :employee_id";
    return $this->query($sql, ['employee_id' => $employee_id])->fetch(PDO::FETCH_ASSOC);
  }
}

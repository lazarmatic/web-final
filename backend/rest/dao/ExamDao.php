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
      $dsn = 'mysql:dbname=webfinale;host=localhost;port=3306';
      $this->conn = new PDO($dsn, 'root', 'zollero0000', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
      ]);
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

  //helper function

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
    $sql = "
        SELECT e.employeeNumber AS id,
               CONCAT(e.firstName, ' ', e.lastName) AS full_name,
               e.email,
               IFNULL(SUM(p.amount),0) AS total
        FROM employees e
        LEFT JOIN customers c ON e.employeeNumber = c.salesRepEmployeeNumber
        LEFT JOIN payments p ON c.customerNumber = p.customerNumber
        GROUP BY e.employeeNumber
    ";

    return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }

  /** TODO
   * Implement DAO method used to delete employee by id
   */
  public function delete_employee($employee_id)
  {
    $sql = "DELETE FROM employees e WHERE e.employeeNumber = (:employee_id)";
    return $this->query($sql, ['employee_id' => $employee_id]);
  }

  /** TODO
   * Implement DAO method used to edit employee data
   */
  public function edit_employee($employee_id, $data)
  {
    $fields = "";
    foreach ($data as $key => $value) {
      $fields .= "$key = :$key, ";
    }
    $fields = rtrim($fields, ", ");
    $sql = "UPDATE employees e SET $fields WHERE e.employeeNumber = :id";
    $stmt = $this->conn->prepare($sql);
    $data['id'] = $employee_id;
    $stmt->execute($data);

    $stmt = $this->conn->prepare("
        SELECT e.employeeNumber AS id,
               e.firstName AS first_name,
               e.lastName AS last_name,
               e.email
        FROM employees e
        WHERE e.employeeNumber = :id
    ");
    $stmt->execute(['id' => $employee_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /** TODO
   * Implement DAO method used to get orders report
   */
  public function get_orders_report()
  {
    $stmt = $this->conn->prepare("
        SELECT o.orderNumber AS order_number,
               SUM(od.quantityOrdered * od.priceEach) AS total_amount
        FROM orders o
        JOIN orderdetails od ON o.orderNumber = od.orderNumber
        GROUP BY o.orderNumber
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /** TODO
   * Implement DAO method used to get all products in a single order
   */
  public function get_order_details($order_id)
  {
    $stmt = $this->conn->prepare("SELECT p.productName , o.quantityOrdered , o.priceEach FROM orderdetails o JOIN products p ON o.productCode = p.productCode WHERE o.orderNumber = :order_id");
    $stmt->bindValue(":order_id", $order_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}

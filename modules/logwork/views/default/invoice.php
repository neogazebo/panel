<!DOCTYPE html>
<html>
<head>
<?php
$this->registerCss("
table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    text-align: left;
    padding: 8px;
}

tr:nth-child(even){background-color: #f2f2f2}

th {
    background-color: #478DCB;
    color: white;
}
");

?>
</head>
<body>
<section class="invoice">
    <h2 class="page-header">
      <i class="fa fa-globe"></i> Report
      <small class="pull-right">Date: 2/10/2014</small>
    </h2>
    <ul>
      <li>Username : </li>
      <li>Country : </li>
    </ul>
<table>
  <tr>
    <th>Firstname</th>
    <th>Lastname</th>
    <th>Savings</th>
  </tr>
  <tr>
    <td>Peter</td>
    <td>Griffin</td>
    <td>$100</td>
  </tr>
  <tr>
    <td>Lois</td>
    <td>Griffin</td>
    <td>$150</td>
  </tr>
  <tr>
    <td>Joe</td>
    <td>Swanson</td>
    <td>$300</td>
  </tr>
  <tr>
    <td>Cleveland</td>
    <td>Brown</td>
    <td>$250</td>
</tr>
</table>
</section>

</body>
</html>

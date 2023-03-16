<?php 
require_once('navbar.php');

 ?>


<div class="container streamdata">
     <div class="table-responsive" id="addstudent">
        <form id="addstudent" method="post" action="sql_actions.php">
            <table class="table table-bordered adddata" cellspacing="0">
                <tr>
                    <th>Academic Year</th>
                    <th>Stream</th>
                    <th>Semester</th>
                    <th>Fee Type</th>
                    <th>Amount</th>

                </tr>
            </table>
        </form>
    </div>
    </div>


<?php
  include_once('footer.php');
?>

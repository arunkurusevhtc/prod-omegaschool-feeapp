
<?php 
require_once('admnavbar.php');

 ?>





<div class="container streamdata">
	<div class="row">
    <form class="form-inline" id ="addform">
       <div class="form-group col-md-2">
           <label for="sel1">Stream:</label>
            <select class="form-control stream" id="sel1">
              <option></option>
            
           </select>
          </div>

           <div class="col-md-2">

             <label for="sel1">Semester</label>
               <select class="form-control semester" id="sel1">
                <option></option>
            
              </select>
            </div>  

          <div class="col-md-4">
            <label for="sel1">Fee Type</label>
              <select class="form-control feetype" id="sel1">
              <option></option>

             </select>

          </div>



      <div class="col-md-4 form-group">
         <label for="sel1">Due date</label> <input type="text" id="datepicker">
      </div>

     <div class="text-right">
      <button type="submit" formtarget="_self" class="btn btn-primary  btn-md" id="addstudent">Add Data</button>
     </div>
</form>

    <div class="container streamdata" id="add">
     <div class="table-responsive" id="addstudent">
        <form id="addstudent" method="post">
            <table class="table table-bordered adddata" id="mytable" cellspacing="0">
                <tr>
                  <th rowspan="2">Academic Year</th>
                    <th rowspan="2">Stream</th>
                    <th rowspan="2">Semester</th>
                    <th rowspan="2">Fee Type</th>
                    <th colspan="20">Amount</th>
                 </tr>
                 <tr>
                   
                 </tr>         
                </table>
             </form>
          </div>
       </div>
                 
                  

   
   </div>
</div>

	
<?php
  include_once('footer.php');
?>



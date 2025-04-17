<div class="container add-outcome-wrapper">
   <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6"> <!-- Responsive column -->
         <form action="" method="post">

            <div class="form-group mb-3">
               <label for="bill_name">Bill Name</label>
               <input type="text" name="bill_name" id="bill_name" placeholder="Bill Name" class="form-control" required>
            </div>

            <div class="form-group mb-3">
               <label for="bill_price">Bill Price</label>
               <input type="number" name="bill_price" id="bill_price" placeholder="Bill Price" class="form-control" required>
            </div>

            <div class="form-group mb-3">
               <label for="bill_date">Bill Date</label>
               <input type="date" name="bill_date" id="bill_date" placeholder="Bill Date" class="form-control" required>
            </div>

            <div class="form-group">
               <input type="submit" name="add-outcome-bill" id="add-outcome-bill" value="Add Bill" class="btn btn-success w-100">
            </div>

         </form>
      </div>
   </div>
</div>
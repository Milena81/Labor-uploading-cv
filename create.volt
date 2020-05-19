{{ content() }}

<br /><br />

{{ flashSession.output() }}

<form action="" method="post" class="form-inline" multipart="" enctype="multipart/form-data">
  <div class="form-row align-items-center">
      <div class="col-auto">
      <h2> Labor information: </h2>
      <br />
        <label class="sr-only" for="inlineFormInput">Username</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text">
                 &#128214; &#128214; &#128214; &#128214; &#128214; &#128214; &#128214; &#128214; &#128214; &#128214; &#128214; &#128214; &#128214; &#128214; &#128214; &#128214;
                </div>
               </div>
               <input type="text" name="username" class="form-control mb-2" id="inlineFormInput" placeholder="Username">
            </div>
      </div>
      <div class="col-auto">
        <label class="sr-only" for="inlineFormInputGroup">Position</label>
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                <br />
                    <div class="input-group-text">
                     &#128214; &#128214; &#128214; &#128214; &#128214; &#128214; &#128214; &#128214; &#128214; &#128214; &#128214; &#128214; &#128214; &#128214; &#128214; &#128214;
                    </div>
                </div>
                <input type="text" name="position" class="form-control" id="inlineFormInputGroup" placeholder="Position">
            </div>
      </div>
      <div class="form-group">
      <br />
         <label for="exampleFormControlFile1">Add New CV:</label>
         <input type="file" class="btn btn-primary" name="cv" id="cv">
     </div>
     <br />
     <br />
      <div class="col-auto">
        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        <a href="/labor/index/" class="btn btn-success">Labor information</a>
      </div>
   </div>
</form>
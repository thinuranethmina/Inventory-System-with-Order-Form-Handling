<?php

require "util/userStatus.php";

if (User::is_allow()) {
?>
    <div class="row">
        <div class="modal--header">
            <div class="d-flex ">
                <h2 class="my-auto ">Add Category</h2>
                <span class="close ml-auto my-auto" onclick="closeModal1();">&times;</span>
            </div>
            <hr class="text-dark my-2">
        </div>
        <div class="modal--body">

            <div class="row my-3 px-3">
                <div class="col-12">
                    <h6 class="form-text-1">Category Name:</h6>
                </div>
                <div class="col-12">
                    <span>
                        <input type="text" id="name" class="form-control" />
                    </span>
                </div>
            </div>

        </div>
        <div class="modal--footer d-sm-flex text-secondary">
            <div class="ml-auto float-right p-2">
                <button class="btn close-btn mr-1" onclick="closeModal1();">Cancel</button>
                <button class="btn submit-btn" onclick="addCategory();">Add</button>
            </div>
        </div>
    </div>
<?php
} else {
    echo "reload";
}

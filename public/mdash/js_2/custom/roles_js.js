function roleField() {
    let field = `
        <div class="row field_holder">
        <div class="col-sm-12"><hr style="color: #fff;"></div>
                            <div class="col-sm-6">

                                <div class="widget-text-box">
                                    <h4>Roles</h4>
                                    <div class="form-select-list">
                                        <input type="text" name="role[]" class="form-control"  placeholder="Role">

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="widget-text-box">
                                    <h4>Description</h4>
                                    <div class="form-select-list">
                                        <textarea name="description[]" class="form-control" placeholder="Description"></textarea>

                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                            <button class="btn btn-danger deleteRoleField"  title="Delete Role Field" type="button"><span class="fa fa-trash"></span></button>
                            </div>

                        </div>
    `;

    $(field).insertBefore("#add_field");
}

$(document).on("click", "#roleFieldBtn", function () {
    roleField();
});

function userTypeField() {
    let field = `
        <div class="row field_holder">
        <div class="col-sm-12"><hr style="color: #fff;"></div>
                            <div class="col-sm-6">

                                <div class="widget-text-box">
                                    <h4>Type of User</h4>
                                    <div class="form-select-list">
                                        <input type="text" name="type_of_user[]" class="form-control"  placeholder="Type of User">

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="widget-text-box">
                                    <h4>Description</h4>
                                    <div class="form-select-list">
                                        <textarea name="description[]" class="form-control" placeholder="Description"></textarea>

                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                            <button class="btn btn-danger deleteRoleField"  title="Delete Role Field" type="button"><span class="fa fa-trash"></span></button>
                            </div>

                        </div>
    `;

    $(field).insertBefore("#add_field");
}

$(document).on("click", "#userTypeFieldBtn", function () {
    userTypeField();
});

function deleteRoleField(a) {
    $(a).closest(".field_holder").remove();
}

$(document).on("click", ".deleteRoleField", function () {
    deleteRoleField($(this));
});

//chek the check box
$(document).on("click", ".mainCheckBox", function () {
    if ($(this).is(":checked")) {
        $(".smallCheckBox").prop("checked", true);
    } else {
        $(".smallCheckBox").prop("checked", false);
    }
});

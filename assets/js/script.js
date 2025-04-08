window.addEventListener('online', () => {
    Swal.fire({
        position: 'bottom',
        icon: 'success',
        title: 'Online Back',
        showConfirmButton: false,
        timer: 2500,
        toast: "position",
        width: "250px"
    })
});

window.addEventListener('offline', () => {
    Swal.fire({
        position: 'bottom',
        icon: 'error',
        title: 'No Internet',
        showConfirmButton: false,
        timer: 10000,
        toast: "position",
        width: "250px"
    })
});

function startLoading() {
    
    
    var r = new XMLHttpRequest();

    r.onreadystatechange = function() {
        if (r.readyState == 4) {
            var t = r.responseText;
            if (t == "Success") {
                const para = document.createElement("div");
                para.id = "loadingDiv";
                para.innerHTML = text;
                document.body.prepend(para);
            }
        }
    };
    r.open("GET", "loading.php", true);
    r.send();
    

    // fetch("loading.php", {
    //         method: "POST",
    //     }).then(response => response.text())
    //     .then(text => {
    //         const para = document.createElement("div");
    //         para.id = "loadingDiv";
    //         para.innerHTML = text;
    //         document.body.prepend(para);
    //     }).catch(error => {
    //         console.error('Fetch error:', error);
    //     })
}

function endLoading() {
    
    if(document.getElementById('loadingDiv')){
        document.getElementById('loadingDiv').remove();
    }
    
}

function refreshStock() {

    fetch(".//../controller/stockContent.php", {
            method: "POST",
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {
                document.getElementById("stockContent").innerHTML = text;
            }

        }).catch(error => {
            console.error('Fetch error:', error);
        })

}

function openModal1() {
    document.getElementById("modal-1").style.display = "block";
}

function closeModal1() {
    document.getElementById("content-modal-1").classList.remove("show-modal");
    document.getElementById("content-modal-1").classList.add("hide-modal");

    document.getElementById("modal-1").classList.remove("show-modal");
    document.getElementById("modal-1").classList.add("hide-modal");

    setTimeout(function() {
        document.getElementById("modal-1").style.display = "none";
        document.getElementById("content-modal-1").classList.remove("hide-modal");
        document.getElementById("content-modal-1").classList.add("show-modal");

        document.getElementById("modal-1").classList.add("show-modal");
        document.getElementById("modal-1").classList.remove("hide-modal");
    }, 800);
}

// SETTINGS

function changePassword() {
    let opassword = document.getElementById('opassword').value;
    let npassword = document.getElementById('npassword').value;
    let cpassword = document.getElementById('cpassword').value;

    let form = new FormData();
    form.append("opassword", opassword);
    form.append("npassword", npassword);
    form.append("cpassword", cpassword);

    startLoading();
    fetch(".//../controller/changePasswordProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            endLoading();
            if (text == "success") {
                Swal.fire({
                    title: 'Success',
                    text: "Password changed successfuly!",
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = "index.php";
                    }
                })
            } else if (text == 'reload') {
                window.location.reload();
            } else {
                Swal.fire({
                    title: 'Error',
                    text: text,
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                })
            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })


}

function addImage(id) {
    let newImg = document.getElementById("imgchooser" + id); //file chooser
    let imgWindow = document.getElementById("image" + id); //image tag


    newImg.onchange = function() {
        let file0 = this.files[0];

        let form = new FormData();
        form.append("image", newImg.files[0]);

        fetch(".//../controller/image_validate_process.php", {
                method: "POST",
                body: form,
            }).then(response => response.text())
            .then(text => {
                if (text == 'success') {
                    if (document.getElementById('removeStauts' + id) != null) {
                        document.getElementById('removeStauts' + id).value = 'remove';
                    }

                    let url0 = window.URL.createObjectURL(file0);

                    document.getElementById("remove" + id).style.display = 'inline-block';

                    imgWindow.src = url0;


                } else if (text == 'reload') {
                    window.location.reload();
                } else {
                    document.getElementById("imgchooser" + id).value = "";
                    Swal.fire({
                        title: 'Error',
                        text: text,
                        icon: 'error',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    })
                }
            }).catch(error => {
                console.error('Fetch error:', error);
            })

    }
}

function removeImage(id) {
    let imgWindow = document.getElementById("image" + id); //image tag

    imgWindow.src = "assets/images/icons/add_product.png";
    document.getElementById("imgchooser" + id).value = '';

    document.getElementById("remove" + id).style.display = 'none';

    if (document.getElementById("removeStauts" + id) != null) {
        document.getElementById("removeStauts" + id).value = 'remove';
    }
}

function addProduct() {
    var description = quill.root.innerHTML;

    let title = document.getElementById('title').value;
    let category = document.getElementById('category').value;
    let model = document.getElementById('model').value;
    let mqty = document.getElementById('mqty').value;
    let rprice = document.getElementById('rprice').value.toString().replaceAll(',', '');
    let creditPrice = document.getElementById('creditPrice').value.toString().replaceAll(',', '');
    let cashPrice = document.getElementById('cashPrice').value.toString().replaceAll(',', '');
    let qty = document.getElementById('qty').value;
    let image1 = document.getElementById('imgchooser0').files[0];
    let image2 = document.getElementById('imgchooser1').files[0];
    let image3 = document.getElementById('imgchooser2').files[0];

    let form = new FormData();
    form.append("title", title);
    form.append("category", category);
    form.append("model", model);
    form.append("mqty", mqty);
    form.append("rprice", rprice);
    form.append("creditPrice", creditPrice);
    form.append("cashPrice", cashPrice);
    form.append("qty", qty);
    form.append("description", description);
    form.append("image1", image1);
    form.append("image2", image2);
    form.append("image3", image3);

    if (document.getElementById('subCategory') != null) {
        form.append("subCategory", document.getElementById('subCategory').value);
    }


    startLoading();

    fetch(".//../controller/addProductProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            endLoading();
            if (text == 'success') {
                Swal.fire({
                    title: "Success",
                    text: "New product has been added to database.",
                    icon: 'success',
                }).then((result) => {

                    if (result.isConfirmed) {
                        window.location = "./";
                    } else {
                        window.location = "./";
                    }
                })

            } else if (text == 'reload') {
                window.location.reload();
            } else {
                Swal.fire({
                    title: "Error",
                    text: text,
                    icon: 'error',
                })
            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function addShop(element) {
    element.disable = true;
    startLoading();

    let name = document.getElementById('name').value;
    let mobile = document.getElementById('mobile').value;
    let omobile = document.getElementById('omobile').value;
    let address = document.getElementById('address').value;
    let city = document.getElementById('city').value;
    var description = quill.root.innerHTML;
    let location = document.getElementById('location').value.split(',');
    let image = document.getElementById('imgchooser0').files[0];


    let form = new FormData();
    form.append("name", name);
    form.append("mobile", mobile);
    form.append("omobile", omobile);
    form.append("address", address);
    form.append("city", city);
    form.append("description", description);
    form.append("latitude", location[0]);
    form.append("longitude", location[1]);
    form.append("image", image);


    fetch(".//../controller/addShopProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            endLoading();
            element.disable = false;
            if (text == 'success') {
                Swal.fire({
                    title: "Success",
                    text: "New Shop has been added to database.",
                    icon: 'success',
                }).then((result) => {

                    if (result.isConfirmed) {
                        window.location = "./";
                    } else {
                        window.location = "./";
                    }
                })

            } else if (text == 'reload') {
                window.location.reload();
            } else {
                Swal.fire({
                    title: "Error",
                    text: text,
                    icon: 'error',
                })
            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })


}

function viewModal(id, page) {

    let form = new FormData();
    form.append("id", id);

    fetch(".//../controller/" + page + "ModalContent.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {
                document.getElementById('modal-1').innerHTML = text;
                openModal1();
            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })

}

function changeResult(page) {

    let form = new FormData();
    if (page == 'activity') {

        let user = document.getElementById("user").value;
        let priorityElement = document.getElementById("priority");
        let priorityItems = [];

        for (var i = 0; i < priorityElement.options.length; i++) {
            if (priorityElement.options[i].selected) {
                priorityItems.push(priorityElement.options[i].value);
            }
        }

        form.append("user", user);
        form.append("priority", priorityItems);

    } else if (page == 'smsHistory') {
        let message_send_id = document.getElementById("message_send_id").value;
        let shop = document.getElementById("shop").value;
        let search = document.getElementById("search").value;

        form.append("text", search);
        form.append("message_send_id", message_send_id);
        form.append("shop", shop);


    } else if (page == 'stockHistory') {
        let product = document.getElementById("product").value;
        let user = document.getElementById("user").value;
        let operation = document.getElementById("operation").value;
        let stockType = document.getElementById("stockType").value;

        form.append("product", product);
        form.append("user", user);
        form.append("operation", operation);
        form.append("stockType", stockType);

    } else if (page == 'orderHistory') {
        let search = document.getElementById("search").value;
        let city = document.getElementById("city").value;
        let district = document.getElementById("district").value;
        let user = document.getElementById("user").value;
        let shop = document.getElementById("shop").value;
        let isDelete = document.getElementById("status").value;
        let product = document.getElementById("product").value;
        let deliver = document.getElementById("deliver").value;
        let payment = document.getElementById("payment").value;
        let from = document.getElementById("from").value;
        let to = document.getElementById("to").value;

        form.append("product", product);
        form.append("deliver", deliver);
        form.append("payment", payment);
        form.append("isDelete", isDelete);
        form.append("user", user);
        form.append("text", search);
        form.append("city", city);
        form.append("district", district);
        form.append("shop", shop);
        form.append("from", from);
        form.append("to", to);

    } else if (page == 'orderSummery') {
        let user = document.getElementById("user").value;
        let from = document.getElementById("from").value;
        let to = document.getElementById("to").value;
        let shops = document.querySelector('.select-chosen');

        form.append("user", user);
        form.append("from", from);
        form.append("to", to);
        
        for (let option of shops.selectedOptions) {
            form.append('shops[]', option.value);
        }


    } else if (page == 'orderShopSummery') {
        let user = document.getElementById("user").value;
        let from = document.getElementById("from").value;
        let to = document.getElementById("to").value;

        form.append("user", user);
        form.append("from", from);
        form.append("to", to);


    } else if (page == 'product') {

        let search = document.getElementById("search").value;
        form.append("text", search);

    } else if (page == 'shop') {
        let province = document.getElementById("province").value;
        let district = document.getElementById("district").value;
        let city = document.getElementById("city").value;
        let search = document.getElementById("search").value;

        form.append("province", province);
        form.append("district", district);
        form.append("city", city);
        form.append("text", search);

    } else if (page == 'user') {
        let search = document.getElementById("search").value;

        form.append("text", search);
    }


    fetch(".//../controller/" + page + "TableContent.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {
                document.getElementById('resultContent').innerHTML = text;
            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })

}


function downloadOrdersPdf(element) {
    
    element.disabled = true;
    element.innerText = "Generating...";
    
    let search = document.getElementById("search").value;
    let city = document.getElementById("city").value;
    let district = document.getElementById("district").value;
    let user = document.getElementById("user").value;
    let shop = document.getElementById("shop").value;
    let isDelete = document.getElementById("status").value;
    let product = document.getElementById("product").value;
    let deliver = document.getElementById("deliver").value;
    let payment = document.getElementById("payment").value;
    let from = document.getElementById("from").value;
    let to = document.getElementById("to").value;

    let form = new FormData();

    form.append("product", product);
    form.append("deliver", deliver);
    form.append("payment", payment);
    form.append("isDelete", isDelete);
    form.append("user", user);
    form.append("text", search);
    form.append("city", city);
    form.append("district", district);
    form.append("shop", shop);
    form.append("from", from);
    form.append("to", to);
    
    
    fetch(`generate-pdf.php`, {
        method: 'POST',
        body: form,
    })
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = `orders.pdf`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            element.disabled = false;
            element.innerText = "Download PDF";
        })
        // .then(response => response.text())
        // .then(text => {
        //     alert(text);
        // })
        .catch(error => {
            console.error('Error:', error);

            element.disabled = false;
            element.innerText = "Download PDF";
        });

}

function updateStockProductViewer() {

    document.getElementById("operation").value = "0";
    let product = document.getElementById("product").value;
    let stockType = document.getElementById("stockType").value;

    let form = new FormData();
    form.append("product", product);
    form.append("stockType", stockType);

    fetch(".//../controller/productPreviewForUpdateStock.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {

            if (text == 'reload') {
                window.location.reload();
            } else {
                document.getElementById('productPreview').innerHTML = text;
            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function loadUpdateStockContent() {
    let stockType = document.getElementById("stockType").value;

    let form = new FormData();
    form.append("stock", stockType);

    fetch(".//../controller/updateStockContent.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {

            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('productPreview').innerHTML = '';
                document.getElementById('stockOperatonContent').innerHTML = text;
                quill = new Quill('#editor', {
                    modules: {
                        toolbar: toolbarOptions,

                    },
                    theme: 'snow'
                });
            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function operationInfoViewer() {
    let product = document.getElementById("product").value;
    let operation = document.getElementById("operation").value;
    let stockType = document.getElementById("stockType").value;

    let form = new FormData();
    form.append("product", product);
    form.append("operation", operation);
    form.append("stockType", stockType);

    fetch(".//../controller/operationPreviewForUpdateStock.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {

            if (text == 'reload') {
                window.location.reload();
            } else {
                document.getElementById('operation-content').innerHTML = text;
            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function updateStock() {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to update this product stock!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3d9eff',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Update Now!'
    }).then((result) => {
        if (result.isConfirmed) {
            startLoading();

            let product = document.getElementById("product").value;
            let operation = document.getElementById("operation").value;
            let cqty = document.getElementById("cqty").value;
            let stockType = document.getElementById("stockType").value;

            var note = quill.root.innerHTML;

            let form = new FormData();
            form.append("product", product);
            form.append("operation", operation);
            form.append("cqty", cqty);
            form.append("note", note);
            form.append("stockType", stockType);

            fetch(".//../controller/stockUpdateProcess.php", {
                    method: "POST",
                    body: form,
                }).then(response => response.text())
                .then(text => {
                    endLoading();

                    if (text == 'reload') {
                        window.location.reload();
                    } else if (text == 'success') {

                        // document.getElementById('operation-content').innerHTML = "";
                        document.getElementById("product").value = "0";
                        document.getElementById("operation").value = "0";
                        document.getElementById("stockType").value = "0";
                        document.getElementById("cqty").value = "";

                        updateStockProductViewer();
                        operationInfoViewer();

                        quill.root.innerHTML = "";

                        Swal.fire(
                            'Success',
                            'Product stock has been updated',
                            'success'
                        )
                    } else {
                        

                        Swal.fire(
                            'Error',
                            text,
                            'error'
                        )
                    }
                }).catch(error => {
                        alert(error);
                    console.error('Fetch error:', error);
                })

        } else {

            document.getElementById("product").value = 0;
            document.getElementById("operation").value = 0;
            document.getElementById("cqty").value = "";
            quill.root.innerHTML = "";

            Swal.fire(
                'Cancelled',
                'Cancelled your operation',
                'info'
            )
        }
    })

}

function updateSendSMSShopViewer() {

    let shop = document.getElementById('shop').value;

    let form = new FormData();
    form.append("shop", shop);

    fetch(".//../controller/shopPreviewForSendSMS.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {
                document.getElementById('shopPreview').innerHTML = text;
            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })

}

function sendSMS() {
    let shop = document.getElementById('shop').value;
    let send_id = document.getElementById('send_id').value;
    let msg = document.getElementById('msg').value;

    let form = new FormData();
    form.append("shop", shop);
    form.append("text", msg);
    form.append("send_id", send_id);

    startLoading();

    fetch(".//../controller/SMSSendProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            endLoading();

            var responce = JSON.parse(text);

            if (responce.status == 'reload') {
                window.location.reload();
            } else {


                if (responce.status == 'error') {
                    Swal.fire(
                        'Error',
                        responce.errors,
                        'info'
                    );
                } else if (responce.status == 'success') {
                    Swal.fire(
                        'Success',
                        'SMS has been sent.',
                        'success'
                    ).then((result) => {

                        if (result.isConfirmed) {
                            window.location.reload();
                        } else {
                            window.location.reload();
                        }
                    })

                } else {
                    Swal.fire(
                        'Error',
                        responce.status,
                        'error'
                    )
                }
            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function sendMobileSMS() {
    let phone = document.getElementById('phone').value;
    let send_id = document.getElementById('send_id').value;
    let msg = document.getElementById('msg').value;

    let form = new FormData();
    form.append("phone", phone);
    form.append("text", msg);
    form.append("send_id", send_id);

    startLoading();

    fetch(".//../controller/directSMSSendProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            endLoading();

            var responce = JSON.parse(text);

            if (responce.status == 'reload') {
                window.location.reload();
            } else {


                if (responce.status == 'error') {
                    Swal.fire(
                        'Error',
                        responce.errors,
                        'info'
                    );
                } else if (responce.status == 'success') {
                    Swal.fire(
                        'Success',
                        'SMS has been sent.',
                        'success'
                    ).then((result) => {

                        if (result.isConfirmed) {
                            window.location.reload();
                        } else {
                            window.location.reload();
                        }
                    })

                } else {
                    Swal.fire(
                        'Error',
                        responce.status,
                        'error'
                    )
                }
            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

// Add Product

function viewAddProductModalInAddOrder() {

    let form = new FormData();

    var table = document.getElementById('invoiceTable');
    var rows = table.tBodies[0].rows.length;
    form.append("rows", rows);

    if (rows > 1) {
        for (var x = 1; x < (rows); x++) {
            var id = table.tBodies[0].rows[x - 1].cells['0'].id;
            form.append("p" + x, id);
        }
    }

    fetch(".//../controller/addOrderAddProductModalTemp.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('content-modal-1').innerHTML = text;
                openModal1();

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function viewAddProductModalInUpdateAddOrder() {

    let form = new FormData();

    var table = document.getElementById('invoiceTable');
    var rows = table.tBodies[0].rows.length;
    form.append("rows", rows);

    if (rows > 1) {
        for (var x = 1; x < (rows); x++) {
            var id = table.tBodies[0].rows[x - 1].cells['0'].id;
            form.append("p" + x, id);
        }
    }

    fetch(".//../controller/addOrderUpdateAddProductModalTemp.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('content-modal-1').innerHTML = text;
                openModal1();

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function viewAddProductModalInAddReturnOrder() {

    let form = new FormData();

    var table = document.getElementById('returnItemTable');
    var rows = table.tBodies[0].rows.length;
    form.append("rows", rows);

    if (rows > 1) {
        for (var x = 1; x < (rows); x++) {
            var id = table.tBodies[0].rows[x - 1].cells['0'].id;
            form.append("p" + x, id);
        }
    }

    fetch(".//../controller/addOrderAddReturnProductModalTemp.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('content-modal-1').innerHTML = text;
                openModal1();

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function updateAddOrderProductViewer() {

    let product = document.getElementById('product').value;
    let priceType = document.getElementById('priceType').value;

    let form = new FormData();
    form.append("product", product);
    form.append("priceType", priceType);


    fetch(".//../controller/addOrderProductPreview.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('productPreview').innerHTML = text;

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })

}

var isPressed = false;

function addProducttoInvoice() {

    if (!isPressed) {
        isPressed = true;

        let product = document.getElementById('product').value;
        let qty = document.getElementById('qty').value;
        let fqty = document.getElementById('fqty').value;
        let sprice = document.getElementById('sprice').value.toString().replaceAll(',', '');
        let priceType = document.getElementById('priceType').value;

        var table = document.getElementById('invoiceTable');
        var id = table.tBodies[0].rows.length;

        let form = new FormData();
        form.append("product", product);
        form.append("qty", qty);
        form.append("fqty", fqty);
        form.append("sprice", sprice);
        form.append("priceType", priceType);
        form.append("nextRow", id);


        fetch(".//../controller/invoiceTableRow.php", {
                method: "POST",
                body: form,
            }).then(response => response.text())
            .then(text => {
                isPressed = false;

                if (text == 'reload') {
                    window.location.reload();
                } else {

                    if (text == 'msg1') {
                        Swal.fire(
                            'Error',
                            'Please select product',
                            'error'
                        )
                    } else if (text == 'msg2') {
                        Swal.fire(
                            'Error',
                            'Please enter valid qty',
                            'error'
                        )
                    } else if (text == 'error') {
                        Swal.fire(
                            'Error',
                            'Unexpected error',
                            'error'
                        )
                    } else {
                        var row = table.insertRow(id);
                        row.innerHTML = text;
                        updateOrderSummery();
                        closeModal1();
                    }


                }
            }).catch(error => {
                console.error('Fetch error:', error);
            })
    }


}

function addProductUpdatetoInvoice() {

    if (!isPressed) {
        isPressed = true;

        let product = document.getElementById('product').value;
        let qty = document.getElementById('qty').value;
        let fqty = document.getElementById('fqty').value;
        let sprice = document.getElementById('sprice').value.toString().replaceAll(',', '');
        let priceType = document.getElementById('priceType').value;

        var table = document.getElementById('invoiceTable');
        var id = table.tBodies[0].rows.length;

        let form = new FormData();
        form.append("product", product);
        form.append("qty", qty);
        form.append("fqty", fqty);
        form.append("sprice", sprice);
        form.append("priceType", priceType);
        form.append("nextRow", id);


        fetch(".//../controller/invoiceTableRow.php", {
                method: "POST",
                body: form,
            }).then(response => response.text())
            .then(text => {
                isPressed = false;

                if (text == 'reload') {
                    window.location.reload();
                } else {

                    if (text == 'msg1') {
                        Swal.fire(
                            'Error',
                            'Please select product',
                            'error'
                        )
                    } else if (text == 'msg2') {
                        Swal.fire(
                            'Error',
                            'Please enter valid qty',
                            'error'
                        )
                    } else if (text == 'error') {
                        Swal.fire(
                            'Error',
                            'Unexpected error',
                            'error'
                        )
                    } else {
                        var row = table.insertRow(id);
                        row.innerHTML = text;
                        updateOrderSummeryInEdit();
                        closeModal1();
                    }


                }
            }).catch(error => {
                console.error('Fetch error:', error);
            })
    }


}

function addReturnProducttoReturnTable() {

    if (!isPressed) {
        isPressed = true;

        let product = document.getElementById('product').value;
        let qty = document.getElementById('qty').value;
        let price = document.getElementById('price').value.toString().replaceAll(',', '');
        let priceType = document.getElementById('priceType').value;

        var table = document.getElementById('returnItemTable');
        var id = table.tBodies[0].rows.length;

        let form = new FormData();
        form.append("product", product);
        form.append("qty", qty);
        form.append("price", price);
        form.append("priceType", priceType);
        form.append("nextRow", id);

        fetch(".//../controller/invoiceReturnTableRow.php", {
                method: "POST",
                body: form,
            }).then(response => response.text())
            .then(text => {
                isPressed = false;

                if (text == 'reload') {
                    window.location.reload();
                } else {

                    if (text == 'msg1') {
                        Swal.fire(
                            'Error',
                            'Please select product',
                            'error'
                        )
                    } else if (text == 'msg2') {
                        Swal.fire(
                            'Error',
                            'Please enter valid price',
                            'error'
                        )
                    } else if (text == 'msg3') {
                        Swal.fire(
                            'Error',
                            'Please enter valid qty',
                            'error'
                        )
                    } else if (text == 'error') {
                        Swal.fire(
                            'Error',
                            'Unexpected error',
                            'error'
                        )
                    } else {
                        var row = table.insertRow(id);
                        row.innerHTML = text;
                        if (window.location.pathname.endsWith("update-order.php")) {
                            updateOrderSummeryInEdit();
                        } else {
                            updateOrderSummery();
                        }
                        closeModal1();
                    }


                }
            }).catch(error => {
                console.error('Fetch error:', error);
            })
    }


}

function showOptions(id) {
    var elements = document.getElementsByClassName('icon-on-hover' + id);

    for (var i = 0; i < elements.length; i++) {
        elements[i].classList.toggle("d-none");
    }
}

function showOptions2(id) {
    var elements = document.getElementsByClassName('icon-on-hover2' + id);

    for (var i = 0; i < elements.length; i++) {
        elements[i].classList.toggle("d-none");
    }
}



function updateOrderSummery() {

    var table = document.getElementById('invoiceTable');
    var return_table = document.getElementById('returnItemTable');
    let priceType = document.getElementById('priceType').value;
    var rows = table.tBodies[0].rows.length;
    var return_table_rows = return_table.tBodies[0].rows.length;

    let form = new FormData();
    form.append("rows", rows);
    form.append("return_table_rows", return_table_rows);
    form.append("priceType", priceType);

    if (rows > 1) {
        for (var x = 1; x < (rows); x++) {
            var id = table.tBodies[0].rows[x - 1].cells['0'].id;
            form.append("p" + x, id);
            form.append("price" + x, table.tBodies[0].rows[x - 1].cells['5'].innerHTML.toString().replaceAll(',', ''));
            form.append("qty" + x, table.tBodies[0].rows[x - 1].cells['4'].innerHTML);
        }
    }

    if (return_table_rows > 1) {
        for (var x = 1; x < (return_table_rows); x++) {
            var id = return_table.tBodies[0].rows[x - 1].cells['0'].id;
            form.append("rp" + x, id);
            form.append("rprice" + x, return_table.tBodies[0].rows[x - 1].cells['4'].innerHTML.toString().replaceAll(',', ''));
            form.append("rqty" + x, return_table.tBodies[0].rows[x - 1].cells['3'].innerHTML);
        }
    }

    fetch(".//../controller/addOrderOrderSummeryTemp.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('orderSummeryContent').innerHTML = text;

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}


function updateOrderSummeryInEdit() {

    var table = document.getElementById('invoiceTable');
    var return_table = document.getElementById('returnItemTable');
    let priceType = document.getElementById('priceType').value;
    var rows = table.tBodies[0].rows.length;
    var return_table_rows = return_table.tBodies[0].rows.length;

    let form = new FormData();
    form.append("rows", rows);
    form.append("return_table_rows", return_table_rows);
    form.append("priceType", priceType);

    if (rows > 1) {
        for (var x = 1; x < (rows); x++) {
            var id = table.tBodies[0].rows[x - 1].cells['0'].id;
            form.append("p" + x, id);
            form.append("price" + x, table.tBodies[0].rows[x - 1].cells['5'].innerHTML.toString().replaceAll(',', ''));
            form.append("qty" + x, table.tBodies[0].rows[x - 1].cells['4'].innerHTML);
        }
    }

    if (return_table_rows > 1) {
        for (var x = 1; x < (return_table_rows); x++) {
            var id = return_table.tBodies[0].rows[x - 1].cells['0'].id;
            form.append("rp" + x, id);
            form.append("rprice" + x, return_table.tBodies[0].rows[x - 1].cells['4'].innerHTML.toString().replaceAll(',', ''));
            form.append("rqty" + x, return_table.tBodies[0].rows[x - 1].cells['3'].innerHTML);
        }
    }

    fetch(".//../controller/addOrderOrderSummeryTempInEdit.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('orderSummeryContent').innerHTML = text;

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}


function updateOrderSummeryPayments() {

    let discount = document.getElementById('discount').value;
    let paidAmount = document.getElementById("paidAmount").value.toString().replaceAll(',', '');
    let priceType = document.getElementById('priceType').value;

    var return_table = document.getElementById('returnItemTable');
    var return_table_rows = return_table.tBodies[0].rows.length;

    var table = document.getElementById('invoiceTable');
    var rows = table.tBodies[0].rows.length;

    let form = new FormData();
    form.append("rows", rows);
    form.append("discount", discount);
    form.append("paidAmount", paidAmount);
    form.append("priceType", priceType);
    form.append("return_table_rows", return_table_rows);

    if (rows > 1) {
        for (var x = 1; x < (rows); x++) {
            var id = table.tBodies[0].rows[x - 1].cells['0'].id;
            form.append("p" + x, id);
            form.append("price" + x, table.tBodies[0].rows[x - 1].cells['5'].innerHTML.toString().replaceAll(',', ''));
            form.append("qty" + x, table.tBodies[0].rows[x - 1].cells['4'].innerHTML);
        }
    }

    if (return_table_rows > 1) {
        for (var x = 1; x < (return_table_rows); x++) {
            var id = return_table.tBodies[0].rows[x - 1].cells['0'].id;
            form.append("rp" + x, id);
            form.append("rprice" + x, return_table.tBodies[0].rows[x - 1].cells['4'].innerHTML.toString().replaceAll(',', ''));
            form.append("rqty" + x, return_table.tBodies[0].rows[x - 1].cells['3'].innerHTML);
        }
    }

    fetch(".//../controller/addOrderOrderSummeryPaymentCalculator.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {

            if (text == 'reload') {
                window.location.reload();
            } else {

                var responce = JSON.parse(text);

                if (responce.status == "success") {
                    document.getElementById('balanceView').innerHTML = responce.balance;
                    document.getElementById('totalView').innerHTML = responce.total;
                    document.getElementById('returnView').innerHTML = responce.return;
                } else {
                    Swal.fire(
                        'Error',
                        'Unexpected error',
                        'error'
                    )
                    updateOrderSummery();
                }

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })

}

function updateOrderSummeryPaymentsInEdit() {


    let discount = document.getElementById('discount').value;
    let priceType = document.getElementById('priceType').value;

    var return_table = document.getElementById('returnItemTable');
    var return_table_rows = return_table.tBodies[0].rows.length;

    var table = document.getElementById('invoiceTable');
    var rows = table.tBodies[0].rows.length;

    let form = new FormData();
    form.append("rows", rows);
    form.append("discount", discount);
    form.append("priceType", priceType);
    form.append("return_table_rows", return_table_rows);

    if (rows > 1) {
        for (var x = 1; x < (rows); x++) {
            var id = table.tBodies[0].rows[x - 1].cells['0'].id;
            form.append("p" + x, id);
            form.append("price" + x, table.tBodies[0].rows[x - 1].cells['5'].innerHTML.toString().replaceAll(',', ''));
            form.append("qty" + x, table.tBodies[0].rows[x - 1].cells['4'].innerHTML);
        }
    }

    if (return_table_rows > 1) {
        for (var x = 1; x < (return_table_rows); x++) {
            var id = return_table.tBodies[0].rows[x - 1].cells['0'].id;
            form.append("rp" + x, id);
            form.append("rprice" + x, return_table.tBodies[0].rows[x - 1].cells['4'].innerHTML.toString().replaceAll(',', ''));
            form.append("rqty" + x, return_table.tBodies[0].rows[x - 1].cells['3'].innerHTML);
        }
    }

    fetch(".//../controller/addOrderOrderSummeryPaymentCalculatorInEdit.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {

            if (text == 'reload') {
                window.location.reload();
            } else {

                var responce = JSON.parse(text);

                if (responce.status == "success") {
                    document.getElementById('balanceView').innerHTML = responce.balance;
                    document.getElementById('totalView').innerHTML = responce.total;
                    document.getElementById('returnView').innerHTML = responce.return;
                } else {
                    Swal.fire(
                        'Error',
                        'Unexpected error',
                        'error'
                    )
                    updateOrderSummeryInEdit();

                }

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })

}

function removeProduct(rid) {

    var table = document.getElementById('invoiceTable');
    var rows = table.tBodies[0].rows.length;
    let priceType = document.getElementById('priceType').value;

    let form = new FormData();
    form.append("rows", rows);
    form.append("rid", rid);
    form.append("priceType", priceType);

    if (rows > 1) {
        for (var x = 1; x < (rows); x++) {
            var id = table.tBodies[0].rows[x - 1].cells['0'].id;
            form.append("p" + x, id);
            form.append("sprice" + x, table.tBodies[0].rows[x - 1].cells['5'].innerHTML.toString().replaceAll(',', ''));
            form.append("fqty" + x, table.tBodies[0].rows[x - 1].cells['3'].innerHTML);
            form.append("qty" + x, table.tBodies[0].rows[x - 1].cells['4'].innerHTML);
        }
    }

    fetch(".//../controller/addOrderRemoveProduct.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                table.tBodies[0].innerHTML = text;
                if (window.location.pathname.endsWith("update-order.php")) {
                    updateOrderSummeryInEdit();
                } else {
                    updateOrderSummery();
                }
            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })

}

function removeReturnProduct(rid) {

    var table = document.getElementById('returnItemTable');
    var rows = table.tBodies[0].rows.length;
    let priceType = document.getElementById('priceType').value;

    let form = new FormData();
    form.append("rows", rows);
    form.append("rid", rid);
    form.append("priceType", priceType);

    if (rows > 1) {
        for (var x = 1; x < (rows); x++) {
            var id = table.tBodies[0].rows[x - 1].cells['0'].id;
            form.append("p" + x, id);
            form.append("price" + x, table.tBodies[0].rows[x - 1].cells['4'].innerHTML.toString().replaceAll(',', ''));
            form.append("qty" + x, table.tBodies[0].rows[x - 1].cells['3'].innerHTML);
        }
    }

    fetch(".//../controller/addOrderRemoveReturnProduct.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                table.tBodies[0].innerHTML = text;
                if (window.location.pathname.endsWith("update-order.php")) {
                    updateOrderSummeryInEdit();
                } else {
                    updateOrderSummery();
                }

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })

}

function editProductModalOpen(eid, erow) {

    var table = document.getElementById('invoiceTable');
    let priceType = document.getElementById('priceType').value;

    let form = new FormData();
    form.append("eid", eid);
    form.append("erow", erow - 1);
    form.append("efqty", table.tBodies[0].rows[erow - 1].cells['3'].innerHTML);
    form.append("eqty", table.tBodies[0].rows[erow - 1].cells['4'].innerHTML);
    form.append("sprice", table.tBodies[0].rows[erow - 1].cells['5'].innerHTML.toString().replaceAll(',', ''));
    form.append("priceType", priceType);


    fetch(".//../controller/addOrderEditProductModalTemp.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('content-modal-1').innerHTML = text;
                openModal1();

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function editReturnProductModalOpen(eid, erow) {

    var table = document.getElementById('returnItemTable');
    let priceType = document.getElementById('priceType').value;

    let form = new FormData();
    form.append("eid", eid);
    form.append("erow", erow - 1);
    form.append("eqty", table.tBodies[0].rows[erow - 1].cells['3'].innerHTML);
    form.append("price", table.tBodies[0].rows[erow - 1].cells['4'].innerHTML.toString().replaceAll(',', ''));
    form.append("priceType", priceType);


    fetch(".//../controller/addOrderEditReturnProductModalTemp.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('content-modal-1').innerHTML = text;
                openModal1();

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function updateProductInInvoice(erow, eid) {

    var table = document.getElementById('invoiceTable');
    var efqty = document.getElementById('efqty').value;
    var eqty = document.getElementById('eqty').value;
    let priceType = document.getElementById('priceType').value;
    let sprice = document.getElementById('sprice').value.toString().replaceAll(',', '');

    let form = new FormData();
    form.append("product", eid);
    form.append("qty", eqty);
    form.append("fqty", efqty);
    form.append("nextRow", erow + 1);
    form.append("priceType", priceType);
    form.append("sprice", sprice);

    fetch(".//../controller/invoiceTableRow.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                if (text == 'msg1') {
                    Swal.fire(
                        'Error',
                        'Please select product',
                        'error'
                    )
                } else if (text == 'msg2') {
                    Swal.fire(
                        'Error',
                        'Please enter valid qty',
                        'error'
                    )
                } else if (text == 'error') {
                    Swal.fire(
                        'Error',
                        'Unexpected error',
                        'error'
                    )
                } else {
                    table.tBodies[0].rows[erow].innerHTML = text;
                    if (window.location.pathname.endsWith("update-order.php")) {
                        updateOrderSummeryInEdit();
                    } else {
                        updateOrderSummery();
                    }
                    closeModal1();
                }


            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })


}

function updateReturnProductInInvoice(erow, eid) {

    var table = document.getElementById('returnItemTable');
    var eqty = document.getElementById('eqty').value;
    let price = document.getElementById('price').value.toString().replaceAll(',', '');

    let form = new FormData();
    form.append("product", eid);
    form.append("qty", eqty);
    form.append("nextRow", erow + 1);
    form.append("price", price);

    fetch(".//../controller/invoiceReturnTableRow.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                if (text == 'msg1') {
                    Swal.fire(
                        'Error',
                        'Please select product',
                        'error'
                    )
                } else if (text == 'msg2') {
                    Swal.fire(
                        'Error',
                        'Please enter valid qty',
                        'error'
                    )
                } else if (text == 'error') {
                    Swal.fire(
                        'Error',
                        'Unexpected error',
                        'error'
                    )
                } else {
                    table.tBodies[0].rows[erow].innerHTML = text;

                    if (window.location.pathname.endsWith("update-order.php")) {
                        updateOrderSummeryInEdit();
                    } else {
                        updateOrderSummery();
                    }

                    closeModal1();
                }


            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })


}

function selectPaymentType(element){
    
    let chequeTermRow = document.getElementById("chequeTermRow");
        
    if(element.value == '3'){
        chequeTermRow.classList.remove('d-none');
    }else{
        chequeTermRow.classList.add('d-none');
    }
}

function AddOrderForm() {

    if (!isPressed) {
        isPressed = true;

        let shop = document.getElementById("shop").value;
        let note = document.getElementById("note").value;
        let priceType = document.getElementById('priceType').value;
        let chequeTerm = document.getElementById('chequeTerm').value;

        var table = document.getElementById('invoiceTable');
        var rows = table.tBodies[0].rows.length;

        var return_table = document.getElementById('returnItemTable');
        var return_table_rows = return_table.tBodies[0].rows.length;

        let form = new FormData();
        form.append("shop", shop);
        form.append("rows", rows);
        form.append("note", note);
        form.append("priceType", priceType);
        form.append("chequeTerm", chequeTerm);
        form.append("return_table_rows", return_table_rows);


        if (document.getElementById('discount') != null) {
            let discount = document.getElementById('discount').value;
            form.append("discount", discount);
        }

        if (document.getElementById('paidAmount') != null) {
            let paidAmount = document.getElementById('paidAmount').value.toString().replaceAll(',', '');
            form.append("paidAmount", paidAmount);
        }

        if (document.getElementById('paymentType') != null) {
            let paymentType = document.getElementById('paymentType').value;
            form.append("paymentType", paymentType);
        }


        if (rows > 1) {
            for (var x = 1; x < (rows); x++) {
                var id = table.tBodies[0].rows[x - 1].cells['0'].id;
                form.append("p" + x, id);
                form.append("qty" + x, table.tBodies[0].rows[x - 1].cells['4'].innerHTML);
                form.append("fqty" + x, table.tBodies[0].rows[x - 1].cells['3'].innerHTML);
                form.append("sprice" + x, table.tBodies[0].rows[x - 1].cells['5'].innerHTML.toString().replaceAll(',', ''));

            }
        }

        if (return_table_rows > 1) {
            for (var x = 1; x < (return_table_rows); x++) {
                var id = return_table.tBodies[0].rows[x - 1].cells['0'].id;
                form.append("rp" + x, id);
                form.append("rprice" + x, return_table.tBodies[0].rows[x - 1].cells['4'].innerHTML.toString().replaceAll(',', ''));
                form.append("rqty" + x, return_table.tBodies[0].rows[x - 1].cells['3'].innerHTML);
            }
        }

        fetch(".//../controller/addInvoiceProcess.php", {
                method: "POST",
                body: form,
            }).then(response => response.text())
            .then(text => {
                if (text == 'reload') {
                    window.location.reload();
                } else {
                    isPressed = false;

                    if (text == 'success') {
                        Swal.fire({
                            title: "Success",
                            text: "Order has been added to the database.",
                            icon: 'success',
                        }).then((result) => {

                            if (result.isConfirmed) {
                                window.location.reload();
                            } else {
                                window.location.reload();
                            }
                        })

                    } else {
                        Swal.fire({
                            title: "Error",
                            text: text,
                            icon: 'error',
                        })
                    }

                }
            }).catch(error => {
                console.error('Fetch error:', error);
            })

    }



}



// Add Retail Product

function viewAddProductModalInAddRetailOrder() {

    let form = new FormData();

    var table = document.getElementById('invoiceTable');
    var rows = table.tBodies[0].rows.length;
    form.append("rows", rows);

    if (rows > 1) {
        for (var x = 1; x < (rows); x++) {
            var id = table.tBodies[0].rows[x - 1].cells['0'].id;
            form.append("p" + x, id);
        }
    }

    fetch(".//../controller/addRetailOrderAddProductModalTemp.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('content-modal-1').innerHTML = text;
                openModal1();

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function updateAddRetailOrderProductViewer() {

    let product = document.getElementById('product').value;

    let form = new FormData();
    form.append("product", product);


    fetch(".//../controller/addRetailOrderProductPreview.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('productPreview').innerHTML = text;

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function addProducttoRetailInvoice() {

    let product = document.getElementById('product').value;
    let sprice = document.getElementById('sprice').value.toString().replaceAll(',', '');
    let qty = document.getElementById('qty').value;
    let pqty = document.getElementById('pqty').value;
    let oqty = document.getElementById('oqty').value;
    let fqty = document.getElementById('fqty').value;

    var table = document.getElementById('invoiceTable');
    var id = table.tBodies[0].rows.length;

    let form = new FormData();
    form.append("product", product);
    form.append("sprice", sprice);
    form.append("qty", qty);
    form.append("oqty", oqty);
    form.append("pqty", pqty);
    form.append("fqty", fqty);
    form.append("nextRow", id);

    fetch(".//../controller/retailInvoiceTableRow.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                if (text == 'msg1') {
                    Swal.fire(
                        'Error',
                        'Please select product',
                        'error'
                    )
                } else if (text == 'msg2') {
                    Swal.fire(
                        'Error',
                        'Please enter valid qty',
                        'error'
                    )
                } else if (text == 'msg3') {
                    Swal.fire(
                        'Error',
                        'Buying qty not matching with primary stock qty and ongoing stock qty',
                        'error'
                    )
                } else if (text == 'NotEnoughPrimarystock') {
                    Swal.fire(
                        'Error',
                        'Not enough primary stock',
                        'error'
                    )
                } else if (text == 'NotEnoughOngoingstock') {
                    Swal.fire(
                        'Error',
                        'Not enough ongoing stock',
                        'error'
                    )
                } else if (text == 'error') {
                    Swal.fire(
                        'Error',
                        'Unexpected error',
                        'error'
                    )
                } else {
                    var row = table.insertRow(id);
                    row.innerHTML = text;
                    updateRetailOrderSummery();
                    closeModal1();
                }


            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })

}

function updateRetailOrderSummery() {

    var table = document.getElementById('invoiceTable');
    var rows = table.tBodies[0].rows.length;

    let form = new FormData();
    form.append("rows", rows);

    if (rows > 1) {
        for (var x = 1; x < (rows); x++) {
            var id = table.tBodies[0].rows[x - 1].cells['0'].id;
            form.append("p" + x, id);
            form.append("price" + x, table.tBodies[0].rows[x - 1].cells['5'].innerHTML.toString().replaceAll(',', ''));
            form.append("qty" + x, table.tBodies[0].rows[x - 1].cells['4'].innerHTML);
        }
    }

    fetch(".//../controller/addOrderRetailOrderSummeryTemp.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('orderSummeryContent').innerHTML = text;

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function updateRetailOrderSummeryPayments() {

    let discount = document.getElementById('discount').value;
    let paidAmount = document.getElementById("paidAmount").value.toString().replaceAll(',', '');

    var table = document.getElementById('invoiceTable');
    var rows = table.tBodies[0].rows.length;

    let form = new FormData();
    form.append("rows", rows);
    form.append("discount", discount);
    form.append("paidAmount", paidAmount);

    if (rows > 1) {
        for (var x = 1; x < (rows); x++) {
            var id = table.tBodies[0].rows[x - 1].cells['0'].id;
            form.append("p" + x, id);
            form.append("price" + x, table.tBodies[0].rows[x - 1].cells['5'].innerHTML.toString().replaceAll(',', ''));
            form.append("qty" + x, table.tBodies[0].rows[x - 1].cells['4'].innerHTML);
        }
    }

    fetch(".//../controller/addOrderRetailOrderSummeryPaymentCalculator.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                var responce = JSON.parse(text);

                if (responce.status == "success") {
                    document.getElementById('balanceView').innerHTML = responce.balance;
                    document.getElementById('totalView').innerHTML = responce.total;
                } else {
                    Swal.fire(
                        'Error',
                        'Unexpected error',
                        'error'
                    )
                    updateRetailOrderSummery();
                }

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })

}

function editRetailProductModalOpen(eid, erow) {

    var table = document.getElementById('invoiceTable');

    let form = new FormData();
    form.append("eid", eid);
    form.append("erow", erow - 1);
    form.append("eqty", table.tBodies[0].rows[erow - 1].cells['4'].innerHTML);
    form.append("efqty", table.tBodies[0].rows[erow - 1].cells['3'].innerHTML);
    form.append("sprice", table.tBodies[0].rows[erow - 1].cells['5'].innerHTML.toString().replaceAll(',', ''));
    form.append("epqty", table.tBodies[0].rows[erow - 1].cells['0'].getAttribute('data-primaryStock'));
    form.append("eoqty", table.tBodies[0].rows[erow - 1].cells['0'].getAttribute('data-ongoingStock'));

    fetch(".//../controller/addOrderEditRetailProductModalTemp.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('content-modal-1').innerHTML = text;
                openModal1();

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function updateRetailProductInInvoice(erow, eid) {

    var table = document.getElementById('invoiceTable');
    var efqty = document.getElementById('efqty').value;
    var eqty = document.getElementById('eqty').value;
    var epqty = document.getElementById('epqty').value;
    var eoqty = document.getElementById('eoqty').value;
    let sprice = document.getElementById('sprice').value.toString().replaceAll(',', '');

    let form = new FormData();
    form.append("product", eid);
    form.append("qty", eqty);
    form.append("fqty", efqty);
    form.append("pqty", epqty);
    form.append("oqty", eoqty);
    form.append("sprice", sprice);
    form.append("nextRow", erow + 1);

    fetch(".//../controller/retailInvoiceTableRow.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                if (text == 'msg1') {
                    Swal.fire(
                        'Error',
                        'Please select product',
                        'error'
                    )
                } else if (text == 'msg2') {
                    Swal.fire(
                        'Error',
                        'Please enter valid qty',
                        'error'
                    )
                } else if (text == 'msg3') {
                    Swal.fire(
                        'Error',
                        'Buying qty not matching with primary stock qty and ongoing stock qty',
                        'error'
                    )
                } else if (text == 'NotEnoughPrimarystock') {
                    Swal.fire(
                        'Error',
                        'Not enough primary stock',
                        'error'
                    )
                } else if (text == 'NotEnoughOngoingstock') {
                    Swal.fire(
                        'Error',
                        'Not enough ongoing stock',
                        'error'
                    )
                } else if (text == 'error') {
                    Swal.fire(
                        'Error',
                        'Unexpected error',
                        'error'
                    )
                } else {
                    table.tBodies[0].rows[erow].innerHTML = text;
                    updateRetailOrderSummery();
                    closeModal1();
                }


            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })


}


function removeRetailProduct(rid) {

    var table = document.getElementById('invoiceTable');
    var rows = table.tBodies[0].rows.length;

    let form = new FormData();
    form.append("rows", rows);
    form.append("rid", rid);

    if (rows > 1) {
        for (var x = 1; x < (rows); x++) {
            var id = table.tBodies[0].rows[x - 1].cells['0'].id;
            form.append("p" + x, id);
            form.append("sprice" + x, table.tBodies[0].rows[x - 1].cells['5'].innerHTML.toString().replaceAll(',', ''));
            form.append("fqty" + x, table.tBodies[0].rows[x - 1].cells['3'].innerHTML);
            form.append("qty" + x, table.tBodies[0].rows[x - 1].cells['4'].innerHTML);
        }
    }

    fetch(".//../controller/addOrderRemoveRetailProduct.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                table.tBodies[0].innerHTML = text;
                updateRetailOrderSummery();

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })

}


function AddRetailOrderForm() {

    let note = document.getElementById("note").value;

    var table = document.getElementById('invoiceTable');
    var rows = table.tBodies[0].rows.length;

    let form = new FormData();
    form.append("rows", rows);
    form.append("note", note);

    if (document.getElementById('discount') != null) {
        let discount = document.getElementById('discount').value;
        form.append("discount", discount);
    }

    if (document.getElementById('paidAmount') != null) {
        let paidAmount = document.getElementById('paidAmount').value.toString().replaceAll(',', '');
        form.append("paidAmount", paidAmount);
    }

    if (rows > 1) {
        for (var x = 1; x < (rows); x++) {
            var id = table.tBodies[0].rows[x - 1].cells['0'].id;
            form.append("p" + x, id);
            form.append("qty" + x, table.tBodies[0].rows[x - 1].cells['4'].innerHTML);
            form.append("fqty" + x, table.tBodies[0].rows[x - 1].cells['3'].innerHTML);
            form.append("pqty" + x, table.tBodies[0].rows[x - 1].cells['0'].getAttribute('data-primaryStock'));
            form.append("oqty" + x, table.tBodies[0].rows[x - 1].cells['0'].getAttribute('data-ongoingStock'));
            form.append("sprice" + x, table.tBodies[0].rows[x - 1].cells['5'].innerHTML.toString().replaceAll(',', ''));
        }
    }

    startLoading();
    fetch(".//../controller/addRetailInvoiceProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {
                endLoading();

                if (text == 'success') {
                    Swal.fire({
                        title: "Success",
                        text: "Order has been added to the database.",
                        icon: 'success',
                    }).then((result) => {

                        if (result.isConfirmed) {
                            window.location = "./";
                        } else {
                            window.location = "./";
                        }
                    })

                } else {
                    Swal.fire({
                        title: "Error",
                        text: text,
                        icon: 'error',
                    })
                }

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })


}


function updateProductPrice(id) {

    let rprice = document.getElementById("rprice").value.toString().replaceAll(',', '');
    let cashPrice = document.getElementById("cashPrice").value.toString().replaceAll(',', '');
    let creditPrice = document.getElementById("creditPrice").value.toString().replaceAll(',', '');

    let form = new FormData();
    form.append("id", id);
    form.append("rprice", rprice);
    form.append("cashPrice", cashPrice);
    form.append("creditPrice", creditPrice);

    startLoading();

    fetch(".//../controller/updateProductPriceProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {

            endLoading();

            if (text == 'success') {
                Swal.fire({
                    title: "Success",
                    text: "Product price has been updated.",
                    icon: 'success',
                }).then((result) => {

                    if (result.isConfirmed) {
                        window.location = "./";
                    } else {
                        window.location = "./";
                    }
                })

            } else if (text == 'reload') {
                window.location.reload();
            } else {
                Swal.fire({
                    title: "Error",
                    text: text,
                    icon: 'error',
                })
            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })

}


function updateProduct(id) {

    var description = quill.root.innerHTML;

    let title = document.getElementById('title').value;
    let model = document.getElementById('model').value;
    let mqty = document.getElementById('mqty').value;
    let category = document.getElementById("category").value;
    let image1 = document.getElementById('imgchooser0').files[0];
    let image2 = document.getElementById('imgchooser1').files[0];
    let image3 = document.getElementById('imgchooser2').files[0];
    var removeStauts0 = document.getElementById("removeStauts0").value;
    var removeStauts1 = document.getElementById("removeStauts1").value;
    var removeStauts2 = document.getElementById("removeStauts2").value;

    let form = new FormData();
    form.append("id", id);
    form.append("title", title);
    form.append("category", category);
    form.append("model", model);
    form.append("mqty", mqty);
    form.append("description", description);
    form.append("image1", image1);
    form.append("image2", image2);
    form.append("image3", image3);
    form.append("removeStatus1", removeStauts0);
    form.append("removeStatus2", removeStauts1);
    form.append("removeStatus3", removeStauts2);

    if (document.getElementById('subCategory') != null) {
        form.append("subCategory", document.getElementById('subCategory').value);
    }

    startLoading();

    fetch(".//../controller/updateProductProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            endLoading();
            if (text == 'success') {
                Swal.fire({
                    title: "Success",
                    text: "Product has been updated.",
                    icon: 'success',
                }).then((result) => {

                    if (result.isConfirmed) {
                        window.location = "./";
                    } else {
                        window.location = "./";
                    }
                })

            } else if (text == 'reload') {
                window.location.reload();
            } else {
                Swal.fire({
                    title: "Error",
                    text: text,
                    icon: 'error',
                })
            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function changeStatus(page, id) {
    let form = new FormData();
    form.append("id", id);

    fetch(".//../controller/change" + page + "StatusProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {

            if (text == 'active') {
                document.getElementById("status" + id).innerHTML = ' <div class="status status-active mx-auto">Active</div> ';
            } else if (text == 'deactive') {
                document.getElementById("status" + id).innerHTML = ' <div class="status status-deactive mx-auto">Deactive</div> ';
            } else if (text == 'reload') {
                window.location.reload();
            }

        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function loadDistricts() {
    var province = document.getElementById("province").value;

    let form = new FormData();
    form.append("id", province);

    fetch(".//../controller/loadDistrictsProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {

            if (text == 'reload') {
                window.location.reload();
            } else {
                loadCities();
                document.getElementById('district').innerHTML = text;
            }

        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function loadCities() {
    var district = document.getElementById("district").value;

    let form = new FormData();
    form.append("id", district);

    fetch(".//../controller/loadCitiesProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {

            if (text == 'reload') {
                window.location.reload();
            } else {
                document.getElementById('city').innerHTML = text;
            }

        }).catch(error => {
            console.error('Fetch error:', error);
        })
}


function loadSubCategories(element) {
    var category = element.value;

    let form = new FormData();
    form.append("id", category);

    fetch(".//../controller/loadSubCategoryProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {

            if (text == 'reload') {
                window.location.reload();
            } else {
                var content = document.getElementById('subCategoryContent');
                if (text.replace(/\s/g, "") == "") {
                    content.classList.add("d-none");
                } else {
                    content.innerHTML = text;
                    content.classList.remove("d-none");
                }
            }

        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function updateShop(id) {

    let name = document.getElementById('name').value;
    let mobile = document.getElementById('mobile').value;
    let omobile = document.getElementById('omobile').value;
    let address = document.getElementById('address').value;
    let city = document.getElementById('city').value;
    var description = quill.root.innerHTML;
    let location = document.getElementById('location').value.split(',');
    let image = document.getElementById('imgchooser0').files[0];

    let form = new FormData();
    form.append("id", id);
    form.append("name", name);
    form.append("mobile", mobile);
    form.append("omobile", omobile);
    form.append("address", address);
    form.append("city", city);
    form.append("description", description);
    form.append("latitude", location[0]);
    form.append("longitude", location[1]);
    form.append("image", image);

    startLoading();
    
    fetch(".//../controller/updateShopProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            endLoading();
            if (text == 'success') {
                Swal.fire({
                    title: "Success",
                    text: "Shop has been updated.",
                    icon: 'success',
                }).then((result) => {

                    if (result.isConfirmed) {
                        window.location = "./";
                    } else {
                        window.location = "./";
                    }
                })

            } else if (text == 'reload') {
                window.location.reload();
            } else {
                Swal.fire({
                    title: "Error",
                    text: text,
                    icon: 'error',
                })
            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })


}

function markAsDeliveredModalOpen(id) {

    let form = new FormData();
    form.append("id", id);

    fetch(".//../controller/markAsDeliveredModalContent.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('content-modal-1').innerHTML = text;
                openModal1();

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function markAsDelivered(id) {

    var table = document.getElementById('orderProductTable');
    var rows = table.tBodies[0].rows.length;

    let form = new FormData();
    form.append("id", id);

    if (rows > 0) {
        for (var x = 1; x <= (rows); x++) {
            var id = table.tBodies[0].rows[x - 1].cells['0'].getAttribute('data-product');
            form.append("pqty" + id, table.tBodies[0].rows[x - 1].cells['2'].querySelector('input').value);
            form.append("oqty" + id, table.tBodies[0].rows[x - 1].cells['3'].querySelector('input').value);
        }
    }

    fetch(".//../controller/markAsDeliveredProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {

            if (text == 'reload') {
                window.location.reload();
            } else {

                if (text == 'success') {
                    Swal.fire({
                        title: "Success",
                        text: "Mark As Delivered",
                        icon: 'success',
                    }).then((result) => {

                        if (result.isConfirmed) {
                            window.location.reload();
                        } else {
                            window.location.reload();
                        }
                    })

                } else {
                    Swal.fire({
                        title: "Error",
                        text: text,
                        icon: 'error',
                    })
                }
            }

        }).catch(error => {
            console.error('Fetch error:', error);
        })
}


function addPaymentModal(id) {

    var table = document.getElementById('invoiceTable');

    let form = new FormData();
    form.append("id", id);

    fetch(".//../controller/addWholesaleOrderPaymentModalTemp.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('content-modal-1').innerHTML = text;
                openModal1();

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function balanceUpdate(id) {

    var paidAmount = document.getElementById('paidAmount').value.toString().replaceAll(',', '');

    let form = new FormData();
    form.append("id", id);
    form.append("paidAmount", paidAmount);


    fetch(".//../controller/OrderBalanceCalculator.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                var responce = JSON.parse(text);
                if (responce.status == 'success') {
                    document.getElementById('balance').innerHTML = responce.balance;
                }

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function addOrderPayment(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to add payment?",
        icon: "question",
        confirmButtonText: "Yes",
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            var paidAmount = document.getElementById('paidAmount').value.toString().replaceAll(',', '');
            var paymentType = document.getElementById('paymentType').value;

            let form = new FormData();
            form.append("id", id);
            form.append("paymentType", paymentType);
            form.append("paidAmount", paidAmount);


            fetch(".//../controller/OrderPaymentProcess.php", {
                    method: "POST",
                    body: form,
                }).then(response => response.text())
                .then(text => {
                    if (text == 'reload') {
                        window.location.reload();
                    } else {

                        if (text == "success") {
                            Swal.fire({
                                title: "Success",
                                icon: 'success',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                } else {
                                    window.location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: text,
                                icon: 'error',
                            });
                        }

                    }
                }).catch(error => {
                    console.error('Fetch error:', error);
                })
        }
    });


}


function addAdditionalAmountModal(id) {

    var table = document.getElementById('invoiceTable');

    let form = new FormData();
    form.append("id", id);

    fetch(".//../controller/addWholesaleOrderAdditionalAmountModalTemp.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('content-modal-1').innerHTML = text;
                openModal1();

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}


function balanceUpdateInAdditionalAmount(id) {

    var additionalAmount = document.getElementById('additionalAmount').value.toString().replaceAll(',', '');

    let form = new FormData();
    form.append("id", id);
    form.append("additionalAmount", additionalAmount);


    fetch(".//../controller/OrderBalanceCalculatorForAdditionalAmount.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            if (text == 'reload') {
                window.location.reload();
            } else {

                var responce = JSON.parse(text);
                if (responce.status == 'success') {
                    document.getElementById('balance').innerHTML = responce.balance;
                }

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}


function addOrderAdditionalAmount(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to add additional amount?",
        icon: "question",
        confirmButtonText: "Yes",
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            var additionalAmount = document.getElementById('additionalAmount').value.toString().replaceAll(',', '');

            let form = new FormData();
            form.append("id", id);
            form.append("additionalAmount", additionalAmount);


            fetch(".//../controller/OrderAdditionalAmountProcess.php", {
                    method: "POST",
                    body: form,
                }).then(response => response.text())
                .then(text => {
                    if (text == 'reload') {
                        window.location.reload();
                    } else {

                        if (text == "success") {
                            Swal.fire({
                                title: "Success",
                                icon: 'success',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                } else {
                                    window.location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: text,
                                icon: 'error',
                            });
                        }

                    }
                }).catch(error => {
                    console.error('Fetch error:', error);
                })
        }
    });


}

function saveNote(id) {
    let note = document.getElementById('note').value;

    let form = new FormData();
    form.append("id", id);
    form.append("note", note);

    fetch(".//../controller/invoiceNoteUpdate.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {

            if (text == 'reload') {
                window.location.reload();
            } else {
                if (text == "success") {
                    Swal.fire({
                        title: "Success",
                        icon: 'success',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        } else {
                            window.location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: text,
                        icon: 'error',
                    });
                }
            }

        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

// root

function addRootLocation() {
    let city = document.getElementById('city').value;
    let orderedList = document.getElementById('citiesList');
    let childListItems = orderedList.querySelectorAll('li');

    let form = new FormData();
    form.append("city", city);

    childListItems.forEach(item => {
        form.append("cityList[]", item.getAttribute('data-city'));
    });

    fetch(".//../controller/addRootLocation.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {

            if (text == 'reload') {
                window.location.reload();
            } else {

                if (text == "Please select city") {
                    Swal.fire({
                        title: "Error",
                        text: text,
                        icon: 'error',
                    });
                } else if (text == 'Already exist this location') {
                    Swal.fire({
                        title: "Error",
                        text: text,
                        icon: 'error',
                    });
                } else {

                    let li = document.createElement('li');
                    li.classList.add('my-2');
                    li.setAttribute('data-city', city);
                    li.innerHTML = text; -

                    document.getElementById('citiesList').appendChild(li);


                    sortable('#citiesList', {
                        forcePlaceholderSize: true,
                        placeholderClass: 'ph-class',
                        hoverClass: 'bg-maroon yellow',
                    });

                }

            }

        }).catch(error => {
            console.error('Fetch error:', error);
        })


}

let selectedPriceType = 0;

function showInvoiceContent() {
    let priceType = document.getElementById('priceType').value;

        if(priceType == 0 || selectedPriceType == 0){
            
            let form = new FormData();
            form.append("priceType", priceType);
        
            fetch(".//../controller/wholesaleInvoiceContent.php", {
                    method: "POST",
                    body: form,
                }).then(response => response.text())
                .then(text => {
        
                    if (text == 'reload') {
                        window.location.reload();
                    } else {
                        document.getElementById('invoiceContent').innerHTML = text;
                    }
        
                }).catch(error => {
                    console.error('Fetch error:', error);
                })
            
        }else{
            
            let form = new FormData();
            form.append("priceType", priceType);
            
            var table = document.getElementById('invoiceTable');
            var rows = table.tBodies[0].rows.length;
            form.append("rows", rows);
            
            if (rows > 1) {
                for (var x = 1; x < (rows); x++) {
                    var id = table.tBodies[0].rows[x - 1].cells['0'].id;
                    form.append("p" + x, id);
                    form.append("sprice" + x, table.tBodies[0].rows[x - 1].cells['5'].innerHTML.toString().replaceAll(',', ''));
    
                }
            }
             
             fetch(".//../controller/changeOrderPriceType.php", {
                        method: "POST",
                        body: form,
                    })
                    .then(response => response.json())
                    .then(data => {
                        
                        if (data.status == 'reload') {
                            window.location.reload();
                        } else {
                            if(data.status == "success"){
                                
                                data.products.forEach(product => {
                                    let cell5 = table.tBodies[0].rows[product.row - 1].cells[5];

                                    if (!cell5.classList.contains('text-danger')) {
                                        cell5.innerHTML = Number(product.price).toLocaleString('en-US', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                        
                                        let cell4 = table.tBodies[0].rows[product.row - 1].cells[4].innerHTML.toString().replaceAll(',', '');
                                        let cell6 = table.tBodies[0].rows[product.row - 1].cells[6];
                                        
                                        cell6.innerHTML = Number(product.price * cell4).toLocaleString('en-US', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                        
                                    }
                                    

                                });
                                
                                updateOrderSummery();
                            }
                        }
                    }).catch(error => {
                        console.error('Fetch error:', error);
                    })
            
        }

        selectedPriceType = priceType;
}

function addCategoryModalOpen() {
    fetch(".//../controller/addCategoryModalContent.php", {
            method: "POST",
        }).then(response => response.text())
        .then(text => {

            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('content-modal-1').innerHTML = text;
                openModal1();

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}


function addCategory() {

    let name = document.getElementById('name').value;

    let form = new FormData();
    form.append("name", name);

    fetch(".//../controller/addCategoryProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {

            if (text == 'reload') {
                window.location.reload();
            } else {
                if (text == "success") {
                    Swal.fire({
                        title: "Success",
                        text: "Category Added Successfully",
                        icon: 'success',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        } else {
                            window.location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: text,
                        icon: 'error',
                    });
                }
            }

        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function editCategoryModalOpen(id) {

    let form = new FormData();
    form.append("id", id);

    fetch(".//../controller/editCategoryModalContent.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {

            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('content-modal-1').innerHTML = text;
                openModal1();

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}


function editCategory(id) {

    let name = document.getElementById('name').value;

    let form = new FormData();
    form.append("id", id);
    form.append("name", name);

    fetch(".//../controller/editCategoryProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {

            if (text == 'reload') {
                window.location.reload();
            } else {
                if (text == "success") {
                    Swal.fire({
                        title: "Success",
                        text: "Category Updated Successfully",
                        icon: 'success',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        } else {
                            window.location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: text,
                        icon: 'error',
                    });
                }
            }

        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function addUser() {

    let name = document.getElementById('name').value;
    let nic = document.getElementById('nic').value;
    let mobile = document.getElementById('mobile').value;
    let password = document.getElementById('password').value;
    let address = document.getElementById('address').value;
    let city = document.getElementById('city').value;
    let userType = document.getElementById('userType').value;
    let image = document.getElementById('imgchooser0').files[0];
    let nicf = document.getElementById('imgchooser1').files[0];
    let nicb = document.getElementById('imgchooser2').files[0];


    let form = new FormData();
    form.append("name", name);
    form.append("nic", nic);
    form.append("mobile", mobile);
    form.append("password", password);
    form.append("userType", userType);
    form.append("address", address);
    form.append("city", city);
    form.append("image", image);
    form.append("nicf", nicf);
    form.append("nicb", nicb);

    startLoading();

    fetch(".//../controller/addUserProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            endLoading();
            if (text == 'success') {
                Swal.fire({
                    title: "Success",
                    text: "New User has been added to database.",
                    icon: 'success',
                }).then((result) => {

                    if (result.isConfirmed) {
                        window.location = "./";
                    } else {
                        window.location = "./";
                    }
                })

            } else if (text == 'reload') {
                window.location.reload();
            } else {
                Swal.fire({
                    title: "Error",
                    text: text,
                    icon: 'error',
                })
            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })


}


function updateUser(id) {

    let name = document.getElementById('name').value;
    let mobile = document.getElementById('mobile').value;
    let address = document.getElementById('address').value;
    let city = document.getElementById('city').value;
    let userType = document.getElementById('userType').value;
    let image = document.getElementById('imgchooser0').files[0];
    let nicf = document.getElementById('imgchooser1').files[0];
    let nicb = document.getElementById('imgchooser2').files[0];

    let form = new FormData();
    form.append("id", id);
    form.append("name", name);
    form.append("mobile", mobile);
    form.append("userType", userType);
    form.append("address", address);
    form.append("city", city);
    form.append("image", image);
    form.append("nicf", nicf);
    form.append("nicb", nicb);


    startLoading();

    fetch(".//../controller/updateUserProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            endLoading();
            if (text == 'success') {
                Swal.fire({
                    title: "Success",
                    text: "User has been Updated.",
                    icon: 'success',
                }).then((result) => {

                    if (result.isConfirmed) {
                        window.location = "./";
                    } else {
                        window.location = "./";
                    }
                })

            } else if (text == 'reload') {
                window.location.reload();
            } else {
                Swal.fire({
                    title: "Error",
                    text: text,
                    icon: 'error',
                })
            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })


}

function formatPrice(element) {

    let price = element.value.toString().replaceAll(',', '');

    let f_price = price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

    element.value = f_price;

}

function keyBlocker(event, type) {

    if (type == "price") {
        var charCode = (event.which) ? event.which : event.keyCode;

        if ((charCode > 47 && charCode < 58) || (charCode > 95 && charCode < 106) || charCode == 8 || charCode == 110 || charCode == 188) {
            return true;
        }

        event.preventDefault();
    } else if (type == "qty") {
        var charCode = (event.which) ? event.which : event.keyCode;

        if ((charCode > 47 && charCode < 58) || (charCode > 95 && charCode < 106) || charCode == 8 || charCode == 110) {
            return true;
        }

        event.preventDefault();
    }

}


function DeleteOrderForm(id) {

    Swal.fire({
        title: "Warning",
        text: "Are you sure! Do you want to delete this order form?",
        icon: 'warning',
        showCancelButton: true,
    }).then((result) => {

        if (result.isConfirmed) {
            let form = new FormData();
            form.append("id", id);

            fetch(".//../controller/markAsDeleteProcess.php", {
                    method: "POST",
                    body: form,
                }).then(response => response.text())
                .then(text => {

                    if (text == 'reload') {
                        window.location.reload();
                    } else {

                        if (text == 'success') {
                            Swal.fire({
                                title: "Success",
                                text: "Order form has been deleted successfully",
                                icon: 'success',
                            }).then((result) => {

                                if (result.isConfirmed) {
                                    window.location.reload();
                                } else {
                                    window.location.reload();
                                }
                            })

                        } else {
                            Swal.fire({
                                title: "Error",
                                text: text,
                                icon: 'error',
                            })
                        }
                    }

                }).catch(error => {
                    console.error('Fetch error:', error);
                })
        }
    })


}

// SMS Template

function addSMSTemplateModalOpen() {
    fetch(".//../controller/addSMSTemplateModalContent.php", {
            method: "POST",
        }).then(response => response.text())
        .then(text => {

            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('content-modal-1').innerHTML = text;
                openModal1();

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}


function addSMSTemplate() {

    let name = document.getElementById('name').value;
    let template = document.getElementById('template').value;

    let form = new FormData();
    form.append("name", name);
    form.append("template", template);

    fetch(".//../controller/addSMSTemplateProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {

            if (text == 'reload') {
                window.location.reload();
            } else {
                if (text == "success") {
                    Swal.fire({
                        title: "Success",
                        text: "Template Added Successfully",
                        icon: 'success',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        } else {
                            window.location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: text,
                        icon: 'error',
                    });
                }
            }

        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function editSMSTemplateModalOpen(id) {

    let form = new FormData();
    form.append("id", id);

    fetch(".//../controller/editSMSTemplateModalContent.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {

            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('content-modal-1').innerHTML = text;
                openModal1();

            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })
}


function editSMSTemplate(id) {

    let name = document.getElementById('name').value;
    let template = document.getElementById('template').value;

    let form = new FormData();
    form.append("id", id);
    form.append("name", name);
    form.append("template", template);

    fetch(".//../controller/editSMSTemplateProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {

            if (text == 'reload') {
                window.location.reload();
            } else {
                if (text == "success") {
                    Swal.fire({
                        title: "Success",
                        text: "Template Updated Successfully",
                        icon: 'success',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        } else {
                            window.location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: text,
                        icon: 'error',
                    });
                }
            }

        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function updateSendSMSTemplate(element) {

    let form = new FormData();
    form.append("id", element.value);

    fetch(".//../controller/SMSTemplateContent.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {

            if (text == 'reload') {
                window.location.reload();
            } else {

                document.getElementById('msg').innerHTML = text;
            }

        }).catch(error => {
            console.error('Fetch error:', error);
        })
}

function addCity() {

    let name = document.getElementById('name').value;
    let district = document.getElementById('district').value;


    let form = new FormData();
    form.append("name", name);
    form.append("district", district);

    startLoading();

    fetch(".//../controller/addCityProcess.php", {
            method: "POST",
            body: form,
        }).then(response => response.text())
        .then(text => {
            endLoading();
            if (text == 'success') {
                Swal.fire({
                    title: "Success",
                    text: "New city has been added to database.",
                    icon: 'success',
                }).then((result) => {

                    if (result.isConfirmed) {
                        window.location = "add-shop.php";
                    } else {
                        window.location = "add-shop.php";
                    }
                })

            } else if (text == 'reload') {
                window.location.reload();
            } else {
                Swal.fire({
                    title: "Error",
                    text: text,
                    icon: 'error',
                })
            }
        }).catch(error => {
            console.error('Fetch error:', error);
        })


}

function UpdateOrderForm(id) {

    if (!isPressed) {
        isPressed = true;

        let note = document.getElementById("note").value;
        let priceType = document.getElementById('priceType').value;
        let chequeTerm = document.getElementById('chequeTerm').value;

        var table = document.getElementById('invoiceTable');
        var rows = table.tBodies[0].rows.length;

        var return_table = document.getElementById('returnItemTable');
        var return_table_rows = return_table.tBodies[0].rows.length;

        let form = new FormData();
        form.append("id", id);
        form.append("rows", rows);
        form.append("note", note);
        form.append("priceType", priceType);
        form.append("chequeTerm", chequeTerm);
        form.append("return_table_rows", return_table_rows);


        if (document.getElementById('discount') != null) {
            let discount = document.getElementById('discount').value;
            form.append("discount", discount);
        }

        if (rows > 1) {
            for (var x = 1; x < (rows); x++) {
                var id = table.tBodies[0].rows[x - 1].cells['0'].id;
                form.append("p" + x, id);
                form.append("qty" + x, table.tBodies[0].rows[x - 1].cells['4'].innerHTML);
                form.append("fqty" + x, table.tBodies[0].rows[x - 1].cells['3'].innerHTML);
                form.append("sprice" + x, table.tBodies[0].rows[x - 1].cells['5'].innerHTML.toString().replaceAll(',', ''));

            }
        }

        if (return_table_rows > 1) {
            for (var x = 1; x < (return_table_rows); x++) {
                var id = return_table.tBodies[0].rows[x - 1].cells['0'].id;
                form.append("rp" + x, id);
                form.append("rprice" + x, return_table.tBodies[0].rows[x - 1].cells['4'].innerHTML.toString().replaceAll(',', ''));
                form.append("rqty" + x, return_table.tBodies[0].rows[x - 1].cells['3'].innerHTML);
            }
        }

        fetch(".//../controller/updateInvoiceProcess.php", {
                method: "POST",
                body: form,
            }).then(response => response.text())
            .then(text => {
                if (text == 'reload') {
                    window.location.reload();
                } else {
                    isPressed = false;

                    if (text == 'success') {
                        Swal.fire({
                            title: "Success",
                            text: "Order has been updated.",
                            icon: 'success',
                        }).then((result) => {

                            if (result.isConfirmed) {
                                window.location.reload();
                            } else {
                                window.location.reload();
                            }
                        })

                    } else {
                        Swal.fire({
                            title: "Error",
                            text: text,
                            icon: 'error',
                        })
                    }

                }
            }).catch(error => {
                console.error('Fetch error:', error);
            })

    }

}
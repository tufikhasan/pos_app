<!-- Modal -->
<div class="modal fade" id="edit_product_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content" id="edit_product_form">
            <div class="modal-header">
                <h5 class="modal-title">Edit product</h5>
                <button type="button" class="close" onclick="closeModal('#edit_product_modal','edit_product_form')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="up_id" name="id">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="up_up_name">Name</label>
                        <input class="form-control" placeholder="product Name" type="text" name="name"
                            id="up_name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="up_sku">SKU</label>
                        <input class="form-control" placeholder="product Name" type="text" name="sku"
                            id="up_sku">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="up_unit">Unit</label>
                        <input class="form-control" placeholder="product Name" type="text" name="unit"
                            id="up_unit">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="up_stock">Stock</label>
                        <input class="form-control" placeholder="product Name" type="text" name="stock"
                            id="up_stock">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="up_price">Price</label>
                        <input class="form-control" placeholder="product Name" type="text" name="price"
                            id="up_price">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-control-label" for="up_brand_list">Brand</label>
                        <select class="form-control text-uppercase" id="up_brand_list" name="brand_id"></select>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="form-control-label" for="up_category_list">Category</label>
                        <select class="form-control text-uppercase" id="up_category_list" name="category_id"></select>
                    </div>
                    <div class="col-md-12">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="up_image" name="image" lang="en"
                                oninput="up_pro_img_preview.src=window.URL.createObjectURL(this.files[0])">
                            <label class="custom-file-label" for="image"></label>
                        </div>
                        <img width="70" src="{{ asset('assets/img/no_image.jpg') }}" id="up_pro_img_preview">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    onclick="closeModal('#edit_product_modal','edit_product_form')">Close</button>
                <button type="submit" class="btn btn-primary">Update product</button>
            </div>
        </form>
    </div>
</div>

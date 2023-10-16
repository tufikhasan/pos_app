<!-- Modal -->
<div class="modal fade" id="add_product_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content" id="add_product_form">
            <div class="modal-header">
                <h5 class="modal-title">Add New product</h5>
                <button type="button" class="close" onclick="closeModal('#add_product_modal','add_product_form')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="name">Name</label>
                            <input class="form-control" placeholder="product Name" type="text" name="name"
                                id="name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sku">SKU</label>
                            <input class="form-control" placeholder="sku_001" type="text" name="sku"
                                id="sku">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="unit">Unit</label>
                            <input class="form-control" placeholder="Unit(ps,kg,etc)" type="text" name="unit"
                                id="unit">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="stock">Stock</label>
                            <input class="form-control" placeholder="Stock" type="number" name="stock"
                                id="stock">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="price">Price</label>
                            <input class="form-control" placeholder="Price" type="text" name="price"
                                id="price">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-control-label" for="brand_list">Brand</label>
                            <select class="form-control text-uppercase" id="brand_list" name="brand_id">
                                <option value="0">Select Brand</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-control-label" for="category_list">Category</label>
                            <select class="form-control text-uppercase" id="category_list" name="category_id">
                                <option value="0">Select Category</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image" name="image"
                                    lang="en"
                                    oninput="pro_img_preview.src=window.URL.createObjectURL(this.files[0])">
                                <label class="custom-file-label" for="image"></label>
                            </div>
                            <img width="70" src="{{ asset('assets/img/no_image.jpg') }}" id="pro_img_preview">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    onclick="closeModal('#add_product_modal','add_product_form')">Close</button>
                <button type="submit" class="btn btn-primary">Add New product</button>
            </div>
        </form>
    </div>
</div>

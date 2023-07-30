<!-- Modal -->
<div id="edit_category_modal"
    class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="flex items-center justify-center h-screen px-4 text-center sm:block sm:p-0 w-auto">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 opacity-50"></div>

        <!-- Modal content -->
        <form id="edit_category_form"
            class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            enctype="multipart/form-data">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <!-- Modal header -->
                <h3 class="text-lg font-semibold text-gray-900 text-center font-bold mb-6">
                    Edit Category
                </h3>
                <input type="hidden" id="up_id" name="id">
                <!-- Modal body -->
                <div class="form-group">
                    <label>Category Name</label>
                    <input type="text" class="form-control" name="name" id="up_name">
                </div>
                <div class="form-group">
                    <label>Category Image</label>
                    <input type="file" class="form-control" name="image" id="up_image">
                </div>
                <img id="showUpImage" class="w-20" src="{{ asset('assets/no_image.jpg') }}" alt="">
            </div>
            <!-- Modal footer -->
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse">
                <button type="submit" class="btn btn-primary">Update Category</button>
                <button onclick="hiddenModal('edit_category_modal','edit_category_form')" type="button"
                    class="btn btn-danger ml-1">
                    Close
                </button>
            </div>
        </form>
    </div>
</div>
<template>

    <input class="form-control form-control-sm" type="text" placeholder=".form-control-sm"
        aria-label=".form-control-sm example">

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Company Profile Information</h4>
                    </div>
                    <div class="card-body">
                        <form action="#" method="POST">

                            <div class="mb-3">
                                <label for="companyName" class="form-label">Company Name</label>
                                <input type="text" class="form-control" id="companyName" required>
                            </div>
                            <div class="text-center mb-4">
                                <img src="https://placeholder.com" id="preview" class="img-fluid rounded-circle border"
                                    alt="Logo Preview" style="width: 150px; height: 150px; object-fit: cover;">
                                <input class="form-control" type="file" id="license" name="license"
                                    onchange="previewImage(event)">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="industry" class="form-label">Industry</label>
                                    <select class="form-select" id="industry">
                                        <option selected disabled>Choose industry...</option>
                                        <option value="tech">Technology</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="foundedYear" class="form-label">Founded Year</label>
                                    <input type="number" class="form-control" id="foundedYear">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" rows="2"></textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Save Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
function previewImage(event) {
    const file = event.target.files[0]; // Get the file
    if (file) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById('preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(file);
    }
}
</script>
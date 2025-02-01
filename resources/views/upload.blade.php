<form action="{{ route('upload.xlsx') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="xlsx_file" class="form-label">Upload File Excel (.xlsx)</label>
        <input type="file" name="xlsx_file" id="xlsx_file" class="form-control" required>
        @error('xlsx_file')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">Upload</button>
</form>

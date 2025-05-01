<div class="col">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2>{{ $category->name }}</h2>
            <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                    <a href="{{ route('categories.show', $category->id) }}" class="btn btn-sm btn-outline-secondary">View</a>
                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                </div>
            </div>
        </div>
    </div>
</div>

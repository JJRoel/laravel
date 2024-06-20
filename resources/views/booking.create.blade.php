<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Create Booking</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('bookings.store') }}">
            @csrf
            <div class="form-group">
                <label for="item_id">Item:</label>
                <select name="item_id" id="item_id" class="form-control" required>
                    <option value="">Select an item</option>
                    @foreach ($items as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mt-3">
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" id="start_date" class="form-control" required>
            </div>
            <div class="form-group mt-3">
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" id="end_date" class="form-control" required>
            </div>
            <input type="hidden" name="user_id" value="1"> <!-- Voor nu een statische waarde -->
            <button type="submit" class="btn btn-primary mt-3">Create Booking</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

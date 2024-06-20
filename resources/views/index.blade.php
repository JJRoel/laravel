<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toggle Container</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container-wrapper {
            border: 1px solid #000;
            margin-bottom: 10px;
            background-color: #b3d9ff;
        }
        .container-header {
            display: flex;
            padding: 10px;
            cursor: pointer;
        }
        .text {
            flex: 1;
            font-size: 1.25rem;
        }
        .expandable-content {
            overflow: hidden;
            transition: max-height 0.5s ease-out, padding 0.5s ease-out;
            max-height: 0;
            background-color: #e6f2ff;
            padding: 0 10px;
            font-size: 1rem;
        }
        .expanded {
            padding-top: 10px;
        }
        .availability {
            font-size: 0.9rem;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mt-4 mb-4">Items</h1>
        @foreach($items as $name => $group)
            <div class="container-wrapper" onclick="toggleExpand('{{ $name }}')">
                <div class="container-header">
                    <div class="text">{{ $name }}</div>
                </div>
                <div id="expandableContent{{ $name }}" class="expandable-content">
                    <a href="#" onclick="event.preventDefault(); openBookingModal('{{ $name }}');">Book</a>
                    <ul class="list-unstyled">
                        @foreach($group as $item)
                            <li>
                                <div class="text">{{ $item->code }}: {{ $item->name }}</div>
                                <div class="availability" id="availability{{ $item->id }}">
                                    Loading availability...
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Booking Modal -->
    <div id="bookingModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('bookings.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create Booking</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="availability-error" class="alert alert-danger" style="display: none;">Selected date range is not available for the chosen item.</div>
                        <div class="form-group">
                            <label for="item_id">Item:</label>
                            <select name="item_id" id="item_id" class="form-control" required>
                                <option value="">Available</option>
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
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Create Booking</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleExpand(name) {
            const content = document.getElementById('expandableContent' + name);
            if (content.classList.contains('expanded')) {
                content.style.maxHeight = '0';
                content.style.paddingTop = '0';
                setTimeout(() => content.classList.remove('expanded'), 500);
            } else {
                content.classList.add('expanded');
                content.style.maxHeight = content.scrollHeight + 'px';
                content.style.paddingTop = '10px';
                loadAvailability(name);
            }
        }

        function openBookingModal(groupName) {
            const items = @json($items);
            const itemSelect = document.getElementById('item_id');
            itemSelect.innerHTML = ''; // Clear the existing options

            // Add the "Available" option
            const availableOption = document.createElement('option');
            availableOption.value = '';
            availableOption.textContent = 'Available';
            itemSelect.appendChild(availableOption);

            items[groupName].forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = `${item.code}: ${item.name}`;
                itemSelect.appendChild(option);
            });

            document.getElementById('availability-error').style.display = 'none';
            $('#bookingModal').modal('show');
        }

        function loadAvailability(groupName) {
            const items = @json($items);
            const group = items[groupName];
            
            group.forEach(item => {
                fetch(`/api/availability/${item.id}`)
                    .then(response => response.json())
                    .then(data => {
                        const availabilityDiv = document.getElementById(`availability${item.id}`);
                        const availableDates = data.available.map(date => `<span class="text-success">${date}</span>`).join(', ');
                        const bookedDates = data.booked.map(date => `<span class="text-danger">${date}</span>`).join(', ');
                        availabilityDiv.innerHTML = `<strong>Available:</strong> ${availableDates}<br><strong>Booked:</strong> ${bookedDates}`;
                    })
                    .catch(error => {
                        console.error('Error loading availability:', error);
                        document.getElementById(`availability${item.id}`).textContent = 'Error loading availability';
                    });
            });
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

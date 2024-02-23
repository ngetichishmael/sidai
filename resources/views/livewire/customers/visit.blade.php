<div>
<!-- Customer Check-in Timeline -->
<div class="card mb-4">
    <h5 class="card-header">Last Check-in Activity</h5>
    <div class="card-body pb-0">
        <ul class="timeline mb-0">
            @foreach ($checkins as $checkin)
                <li class="timeline-item timeline-item-transparent">
                    <span class="timeline-point timeline-point-primary"></span>
                    <div class="timeline-event">
                        <div class="timeline-header mb-1">
                            <h6 class="mb-0">Check-in Code: {{ $checkin->code }}</h6>
                            <small class="text-muted">Start Time: {{ $checkin->start_time }}</small>
                            <small class="text-muted">End Time: {{ $checkin->start_time }}</small>
                        </div>
                        <p class="mb-2">Sales Agent: {{ $checkin->user?->name }}</p>
                        <div class="d-flex">
                            <div class="me-3">
                                <span class="fw-semibold text-heading">IP Address:
                                    {{ $checkin->ip }}</span>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
<!-- /Customer Check-in Timeline -->
</div>
